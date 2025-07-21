const THEME = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
const API = JSON.stringify(ajax_var.url).replace(/['"]+/g, "");
const LOCATION_ICON = `${THEME}/assets/img/my-location.svg`;
const LOCATION_STORE = `${THEME}/assets/img/icon-map.svg`;
const LOCATION_PIN = `${THEME}/assets/img/pin-suggest.svg`;

const LOCATION_PINMARKER = `${THEME}/assets/img/pin-marker.svg`;

const standaloneMap = document.getElementById('standalone-map');
let dataPuntos = [];
let currentMarker = null;
window.addEventListener("DOMContentLoaded", (e) => {
  let map = L.map("map-single", {
    scrollWheelZoom: false,
  }).setView([-38.416097, -63.616672], 6);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 20,
  }).addTo(map);
  

  
  fetchPuntos().then(() => {
    // Usar forEach para iterar sobre los datos
    addPuntosInList(dataPuntos, map);
    btnMap();
    clickItemMap(map);
    handleAutocomplete (map);
  });
  closeModal();
  openModal(map);
  openModalVentas();
  closeModalVentas();
  clickLocationStandAlone(map);
});

// marcador imagen
const iconMarker = L.icon({
  iconUrl: LOCATION_ICON,
  iconSize: [40, 40],
  autoPanOnFocus: true,
});

const iconMarkerPin = L.icon({
  iconUrl: LOCATION_PINMARKER,
  iconSize: [40, 40],
  autoPanOnFocus: true,
});

const geoLocation = (map) => {
  const options = {
    enableHighAccuracy: true, // Alta precisión
    maximumAge: 0, // No queremos caché
    timeout: 10000, // Esperar hasta 10 segundos
  };

  const success = (pos) => {
    let coords = pos.coords;
    // Remover marcador anterior si existe
    if (currentMarker) {
      map.removeLayer(currentMarker);
    }

    // Crear un nuevo marcador en la ubicación actual
    currentMarker = L.marker([coords.latitude, coords.longitude], {
      icon: iconMarker,
    }).addTo(map);

    currentMarker.bindPopup("Tu ubicación").openPopup();

    map.flyTo([coords.latitude, coords.longitude], 10); // Aumentar zoom para mayor precisión
    clickLocation(coords.latitude, coords.longitude, map);
  };

  const error = (err) => {
    alert("Por favor, activa la ubicación en la configuración del navegador y recarga la página");
  };

  navigator.geolocation.getCurrentPosition(success, error, options);
};

const clickLocation = (lat, long, map) => {
  const button = document.getElementById("mapFlyLocation");

  button.addEventListener("click", (e) => {
    map.flyTo([lat, long], 10);
  });
};


const clickLocationStandAlone = (map) => {
  if(standaloneMap){
    geoLocation(map)

  }
}


const fetchPuntos = async () => {
  try {
    const response = await fetch(`${API}?action=get_pdv_ofertas`
    );

    if (!response.ok) {
      throw new Error("Error en la solicitud");
    }

    const data = await response.json();
    // Aquí puedes trabajar con los datos obtenidos
    dataPuntos = dataPuntos.concat(data.data.pdv);
  } catch (error) {
    // Aquí manejas los errores de la solicitud fetch
    console.error("Error en la solicitud:", error);
  }
};

const addPuntosInList = (puntos, map) => {
  const listContainer = document.getElementById("listContainer");
  let listItemHTMLAdherido = "";
  let listItemHTMLNormal = "";

  puntos.forEach((punto) => {
    const { direccion, nombre, lat, long, web, telefono, mail, promo, is_adherido } = punto;
    let name = nombre ? `<p class="list__item-name" data-label="${nombre}">${nombre}</p>` : "";
    let desc = direccion ? `<p style="text-transform: capitalize;" data-label="${nombre} - ${direccion}">${direccion}</p>` : "";
    let website = web ? `<p style="text-transform: lowercase;" data-label="${nombre} - ${web}">${web}</p>` : "";
    let tel = telefono ? `<p style="text-transform: lowercase;" data-label="${nombre} - ${telefono}">${telefono}</p>` : "";
    let email = mail ? `<p style="text-transform: lowercase;" data-label="${nombre} - ${mail}">${mail}</p>` : "";
    let adherido_class = '';

    if (is_adherido && promo) {
      adherido_class = ' local-adherido';
      name = nombre ? `<p class="adherido">*Adherido a la promoción</p><p class="list__item-name"><strong>${nombre}</strong></p>` : "";
    }

    if (lat !== '' && long !== '') {
      const itemHTML = `
        <li class="list__item${adherido_class}" data-lat="${lat}" data-long="${long}">
          <div class="list__item-container">
            ${name}
            ${desc}
            ${tel}
            ${email}
            ${website}
          </div>
        </li>
      `;

      if (is_adherido && promo) {
        listItemHTMLAdherido += itemHTML;
      } else {
        listItemHTMLNormal += itemHTML;
      }

      if (lat !== "-" && long !== "-") {
        let storeIcon = L.icon({ iconUrl: LOCATION_STORE, iconSize: [40, 40] });
        let store = L.marker([lat, long], { icon: storeIcon }).addTo(map);
        store.bindPopup(`<strong style="color: #000;">${nombre || ''}</strong><br>${direccion || ''}<br>Tel: ${telefono || ''}`);
      }
    }
  });

  listContainer.innerHTML = listItemHTMLAdherido + listItemHTMLNormal;
};

const closeModal = (e) => {
  let close = document.querySelector(".modal-close");
  close.addEventListener("click", (e) => {
    let modal = document.querySelector(".modal-backdrop");
    modal.classList.remove("active");
  });
};

const openModal = (map) => {
  let btn = document.getElementById("openModal");
  let modal = document.querySelector(".modal-backdrop");
  if(btn){
    btn.addEventListener("click", (e) => {
      geoLocation(map);
      modal.classList.add("active");
    });
  }
};

const openModalVentas = (e) => {
  let btn = document.getElementById("openModalVentas");
  let modal = document.querySelector(".ventas-backdrop");
  btn.addEventListener("click", (e) => {
  
    modal.classList.add("active");
  });
};

const closeModalVentas = () => {
  let close = document.getElementById("closeModalVentas");
  close.addEventListener("click", (e) => {
    let modal = document.querySelector(".ventas-backdrop");
    modal.classList.remove("active");
  });
};

const btnMap = () => {
  const button = document.getElementById("btnMap");
  const listContainer = document.querySelector(".list__container");
  const svgLista = `
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 32 22" fill="none">
        <rect x="4" y="4.29999" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
        <rect x="4" y="14.3" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
        <rect x="14" y="4.29999" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
        <rect x="14" y="14.3" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
    </svg>
    `;
  const svgMapa = `
    <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M15.5 10.2219V1.22812C15.5 0.893593 15.148 0.67602 14.8488 0.825623L11.6566 2.42172C11.5559 2.47207 11.4399 2.48284 11.3317 2.45191L4.66831 0.548088C4.56005 0.517158 4.44414 0.527931 4.34344 0.578281L0.748754 2.37562C0.596301 2.45185 0.5 2.60767 0.5 2.77812V11.7719C0.5 12.1064 0.85204 12.324 1.15125 12.1744L4.34344 10.5783C4.44414 10.5279 4.56005 10.5172 4.66831 10.5481L11.3317 12.4519C11.4399 12.4828 11.5559 12.4721 11.6566 12.4217L15.2512 10.6244C15.4037 10.5482 15.5 10.3923 15.5 10.2219Z" stroke="currentColor"/>
    </svg>
    `;

  button.addEventListener("click", (e) => {
    listContainer.classList.toggle("show");
    if (listContainer.classList.contains("show")) {
      btnHTML = `
                ${svgMapa}
                <span>MAPA</span>
            `;
    } else {
      btnHTML = `
                ${svgLista}
                <span>LISTA</span>
            `;
    }
    button.innerHTML = btnHTML;
  });
};

const clickItemMap = (map) => {
  let items = document.querySelectorAll(".list__item");
  const button = document.getElementById("btnMap");
  const listContainer = document.querySelector(".list__container");
  const svgLista = `
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 32 22" fill="none">
            <rect x="4" y="4.29999" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
            <rect x="4" y="14.3" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
            <rect x="14" y="4.29999" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
            <rect x="14" y="14.3" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
        </svg>
    `;
  if (items.length > 0) {
    items.forEach((item) => {
      item.addEventListener("click", (e) => {
        const lat = parseFloat(item.dataset.lat)
        const long = parseFloat(item.dataset.long)
        if (listContainer.classList.contains("show")) {
          listContainer.classList.remove("show");
          button.innerHTML = `
                        ${svgLista}
                        <span>LISTA</span>
                    `;
        }
        map.setView([lat, long], 15);

      });
    });
  }
};

const normalizeText = (text) => {
    return text.toLowerCase().replace(/[^\w\sñ]/gi, ""); // Convertir a minúsculas y eliminar caracteres no alfanuméricos
  };

  const calculateDistance = (lat1, lon1, lat2, lon2) => {
    const toRad = (angle) => (angle * Math.PI) / 180; // Convierte grados a radianes
    const R = 6371; // Radio de la Tierra en kilómetros
    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
      Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c; // Distancia en kilómetros
    return distance;
  };

// ACOMODAR ESTO
const fetchAutocompleteSuggestions = async (inputValue) => {
  const url = `${THEME}/proxy-address.php?input=${inputValue}`;
  try {
    const response = await fetch(url);
    const data = await response.json();

    if (data.status === 'OK') {
      return data.predictions;
    } else {
      console.error('Error al obtener sugerencias:', data.status);
      return [];
    }
  } catch (error) {
    console.error('Error en la solicitud de Autocomplete:', error);
    return [];
  }
};

const handleAutocomplete  =  (map) => {
  const input = document.getElementById("buscadorMap");
  const listContainer = document.querySelector(".list__container");
  const button = document.getElementById("btnMap");
  const suggestionBox = document.querySelector('.search-suggest');
  const suggestionBoxContainer = document.getElementById('search-suggest-container');
  const searchContainer = document.querySelector('.search__container');
  const THEME = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");

  let debounceTimeout;

  input.addEventListener("input", (e) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout( async () => {
      // console.log(input.value.length)
      if (input.value.length > 0) {
        suggestionBox.classList.add('show');
        const searchTerm = normalizeText(input.value.trim()); // Normalizar el término de búsqueda  
        let placesSuggest = await fetchAutocompleteSuggestions(searchTerm);

        if (placesSuggest.length > 0) {
          let placeSuggestHTML = "";
          placesSuggest.forEach(place => {
            const { description, place_id }  = place
            placeSuggestHTML += `
              <li class="item-search"  data-key=${place_id}>
                <div class="item-search-img">
                    <img width=24 height="24" loading="lazy" src="${LOCATION_PIN}"></img>
                </div>
                <div>
                  <p>${description}</p>
                </div>
              </li>
            `
          });
          suggestionBoxContainer.innerHTML = placeSuggestHTML

          selectSuggest(map);
        }
      } else {
        console.log('aquiii')
        suggestionBox.classList.remove('show');
        showAllItems();
      }
    }, 800); // Espera 500ms después de que el usuario haya dejado de escribir
  });

  input.addEventListener("click", (e) => {
    if (input.value.length > 0) {
      suggestionBox.classList.add('show');
    }
  });

  window.addEventListener("click", (e) => {
    if (!searchContainer.contains(e.target)) {
      suggestionBox.classList.remove('show');
    }
  });
};

const addMarker = (map, lat, lng, value) => {
  // Crear una marca en la ubicación dada
  if (currentMarker) {
    map.removeLayer(currentMarker);
  }
 
  currentMarker = L.marker([lat, lng],{icon: iconMarkerPin}).addTo(map);
  // Opcional: agregar un popup o tooltip a la marca
  currentMarker.bindPopup(`${value}`).openPopup();
};


const selectSuggest = (map) => {
  const itemsSearch = document.querySelectorAll('.item-search');
  const suggestionBox = document.querySelector('.search-suggest');
  const input = document.getElementById("buscadorMap");
  if(itemsSearch.length > 0){
    itemsSearch.forEach((item) => {
      item.addEventListener('click', async e => {
          suggestionBox.classList.remove('show');
          let data = await fetchSuggestDetails(item.dataset.key);
          let value = item.firstElementChild.nextElementSibling.firstElementChild.textContent
          let latData = data.lat
          let lngData = data.lng

          map.flyTo([latData, lngData], 10);
          addMarker(map, latData, lngData,value );
          input.value = value
          await filterNearbyItems(latData, lngData, 80);

      })
    })
  }
}
// ACOMODAR ESTO
const fetchSuggestDetails = async (id) => {
  const url = `${THEME}/proxy-details.php?place_id=${id}`;
  try {
    const response = await fetch(url);
    const data = await response.json();
    if (data.status === 'OK') {
      return data.result.geometry.location
    } else {
      console.error('Error al obtener sugerencias:', data.status);
      return [];
    }
  } catch (error) {
    console.error('Error en la solicitud de Autocomplete:', error);
    return [];
  }
}

const filterNearbyItems = async (searchLat, searchLng, maxDistanceKm) => {
    let listItem = document.querySelectorAll('.list__item');
    let msgSuccess = document.querySelector('.msg-map');
    let msgError = document.querySelector('.error-msg-map');
    let mapButton = document.getElementById('btnMap');
    let itemsFound = false;  // Inicializamos el flag para verificar si se encontraron elementos
    let itemsWithDistances = [];

    // Si hay elementos en la lista
    if (listItem.length > 0) {
        listItem.forEach(item => {
            let itemLat = parseFloat(item.dataset.lat);
            let itemLng = parseFloat(item.dataset.long);
            let distance = calculateDistance(searchLat, searchLng, itemLat, itemLng);

            // Agregar todos los elementos con su distancia al array
            itemsWithDistances.push({ item, distance });
        });

        // Ordenar los elementos por distancia de menor a mayor
        itemsWithDistances.sort((a, b) => a.distance - b.distance);

        // Crear un fragmento de documento para reorganizar los elementos
        let fragment = document.createDocumentFragment();

        // Iterar por los elementos y gestionar la visibilidad y reordenación
        itemsWithDistances.forEach(({ item, distance }) => {
            // Mostrar u ocultar los elementos según la distancia
            if (distance <= maxDistanceKm) {
                item.classList.remove('d-none');  // Mostrar el elemento si está dentro del rango
                itemsFound = true;  // Se encontró al menos un elemento
            } else {
                item.classList.add('d-none');  // Ocultar el elemento si está fuera del rango
            }

            // Añadir el elemento al fragmento (para reordenarlo en el DOM)
            fragment.appendChild(item);
        });

        // Reordenar los elementos en el contenedor sin eliminar ninguno
        let container = document.getElementById('listContainer'); // Asegúrate de que el selector sea correcto para tu contenedor
        container.innerHTML = ''; // Vaciar el contenedor actual
        container.appendChild(fragment); // Añadir los elementos reordenados
    }

    // Mostrar el mensaje correcto en función de si se encontraron o no elementos
    if (itemsFound) {
        msgSuccess.classList.remove('d-none');
        msgError.classList.add('d-none');
        mapButton.click();  // Llamar al botón del mapa si hay elementos
    } else {
        mapButton.click();
        msgSuccess.classList.add('d-none');
        msgError.classList.remove('d-none');
    }
};

const showAllItems = () => {
  const items = document.querySelectorAll(".list__item"); 

  let msgSuccess = document.querySelector('.msg-map').classList.add('d-none');
  let msgError = document.querySelector('.error-msg-map')
//   msgError.classList.remove('d-none');
//   msgError.textContent = "Ingresar una dirección."
  if(items.length > 0 ){items.forEach(item => {item.classList.remove('d-none')});}
}
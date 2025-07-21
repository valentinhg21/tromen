const THEME = ajax_var.theme;
const API = ajax_var.url;
const LOCATION_ICON = `${THEME}/assets/img/my-location.svg`;
const LOCATION_STORE = `${THEME}/assets/img/icon-map.svg`;
const LOCATION_PIN = `${THEME}/assets/img/pin-suggest.svg`;
const LOCATION_PINMARKER = `${THEME}/assets/img/pin-marker.svg`;

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

const normalizeText = (text) => text.toLowerCase().replace(/[^\w\sñ]/gi, '');

const calculateDistance = (lat1, lon1, lat2, lon2) => {
  const toRad = (angle) => (angle * Math.PI) / 180;
  const R = 6371;
  const dLat = toRad(lat2 - lat1);
  const dLon = toRad(lon2 - lon1);
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
};

let dataPuntos = [];
let currentMarker = null;

const fetchPuntos = async () => {
  try {
    const response = await fetch(`${API}?action=get_pdv`);
    if (!response.ok) throw new Error("Error en la solicitud");
    const data = await response.json();
    dataPuntos = data.data.pdv;
  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
};

const fetchAutocompleteSuggestions = async (inputValue) => {
  try {
    const response = await fetch(`${THEME}/proxy-address.php?input=${inputValue}`);
    const data = await response.json();
    return data.status === "OK" ? data.predictions : [];
  } catch (error) {
    console.error("Error en la solicitud de Autocomplete:", error);
    return [];
  }
};

const fetchSuggestDetails = async (id) => {
  try {
    const response = await fetch(`${THEME}/proxy-details.php?place_id=${id}`);
    const data = await response.json();
    return data.status === "OK" ? data.result.geometry.location : null;
  } catch (error) {
    console.error("Error en la solicitud de detalles:", error);
    return null;
  }
};

const closeModal = () => {
  const close = document.querySelector(".modal-close");
  if (!close) return;
  close.addEventListener("click", () => {
    document.querySelector(".modal-backdrop")?.classList.remove("active");
  });
};

const openModal = (map) => {
  const btn = document.getElementById("openModal");
  if (!btn) return;
  const modal = document.querySelector(".modal-backdrop");
  btn.addEventListener("click", () => {
    geoLocation(map);
    modal?.classList.add("active");
  });
};

const openModalVentas = () => {
  const btn = document.getElementById("openModalVentas");
  if (!btn) return;
  const modal = document.querySelector(".ventas-backdrop");
  btn.addEventListener("click", () => {
    modal?.classList.add("active");
  });
};

const closeModalVentas = () => {
  const close = document.getElementById("closeModalVentas");
  if (!close) return;
  close.addEventListener("click", () => {
    document.querySelector(".ventas-backdrop")?.classList.remove("active");
  });
};

const btnMap = () => {
  const button = document.getElementById("btnMap");
  const listContainer = document.querySelector(".list__container");
  if (!button || !listContainer) return;

  const svgLista = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" ... </svg>`;
  const svgMapa = `<svg width="16" height="13" ... </svg>`;

  button.addEventListener("click", () => {
    listContainer.classList.toggle("show");
    button.innerHTML = listContainer.classList.contains("show")
      ? `${svgMapa}<span>MAPA</span>`
      : `${svgLista}<span>LISTA</span>`;
  });
};

const geoLocation = (map) => {
  if (!navigator.geolocation) {
    alert("Geolocalización no soportada por tu navegador");
    return;
  }

  const options = {
    enableHighAccuracy: true,
    maximumAge: 0,
    timeout: 10000,
  };

  const success = (pos) => {
    const { latitude, longitude } = pos.coords;
    if (currentMarker) map.removeLayer(currentMarker);
    currentMarker = L.marker([latitude, longitude], { icon: iconMarker }).addTo(map);
    currentMarker.bindPopup("Tu ubicación").openPopup();
    map.flyTo([latitude, longitude], 10);
    clickLocation(latitude, longitude, map);
  };

  const error = () => {
    alert("Por favor, activa la ubicación en la configuración del navegador y recarga la página");
  };

  navigator.geolocation.getCurrentPosition(success, error, options);
};

const clickLocation = (lat, long, map) => {
  const button = document.getElementById("mapFlyLocation");
  if (!button) return;
  button.addEventListener("click", () => {
    map.flyTo([lat, long], 10);
  });
};

const clickLocationStandAlone = (map) => {
  if (document.getElementById("standalone-map")) {
    geoLocation(map);
  }
};

const addMarker = (map, lat, lng, value) => {
  if (currentMarker) map.removeLayer(currentMarker);
  currentMarker = L.marker([lat, lng], { icon: iconMarkerPin }).addTo(map);
  currentMarker.bindPopup(value).openPopup();
};

const addPuntosInList = (puntos, map) => {
  const listContainer = document.getElementById("listContainer");
  if (!listContainer) return;

  map.eachLayer((layer) => {
    if (layer instanceof L.Marker && layer !== currentMarker) {
      map.removeLayer(layer);
    }
  });

  listContainer.innerHTML = "";

  const batchSize = 50;
  let index = 0;

  const processBatch = () => {
    const fragment = document.createDocumentFragment();

    for (let i = 0; i < batchSize && index < puntos.length; i++, index++) {
      const { direccion, nombre, lat, long, web, telefono, mail } = puntos[index];
      if (!lat || !long) continue;

      const li = document.createElement("li");
      li.className = "list__item";
      li.dataset.lat = lat;
      li.dataset.long = long;

      const container = document.createElement("div");
      container.className = "list__item-container";

      if (nombre) {
        const p = document.createElement("p");
        p.className = "list__item-name";
        p.dataset.label = nombre;
        p.textContent = nombre;
        container.appendChild(p);
      }

      if (direccion) {
        const p = document.createElement("p");
        p.style.textTransform = "capitalize";
        p.dataset.label = `${nombre} - ${direccion}`;
        p.textContent = direccion;
        container.appendChild(p);
      }

      if (telefono) {
        const p = document.createElement("p");
        p.style.textTransform = "lowercase";
        p.dataset.label = `${nombre} - ${telefono}`;
        p.textContent = telefono;
        container.appendChild(p);
      }

      if (mail) {
        const p = document.createElement("p");
        p.style.textTransform = "lowercase";
        p.dataset.label = `${nombre} - ${mail}`;
        p.textContent = mail;
        container.appendChild(p);
      }

      if (web) {
        const p = document.createElement("p");
        p.style.textTransform = "lowercase";
        p.dataset.label = `${nombre} - ${web}`;
        p.textContent = web;
        container.appendChild(p);
      }

      li.appendChild(container);
      fragment.appendChild(li);

      const icon = L.icon({ iconUrl: LOCATION_STORE, iconSize: [40, 40] });
      const marker = L.marker([lat, long], { icon });
      marker.bindPopup(
        `<strong style="color: #000;">${nombre || ""}</strong><br>${direccion || ""}<br>Tel: ${telefono || ""}`
      );
      marker.addTo(map);
    }

    listContainer.appendChild(fragment);

    if (index < puntos.length) {
      if ("requestIdleCallback" in window) {
        requestIdleCallback(processBatch);
      } else {
        setTimeout(processBatch, 30);
      }
    }
  };

  processBatch();
};

const filterNearbyItems = (searchLat, searchLng, maxDistanceKm) => {
  const listItems = document.querySelectorAll(".list__item");
  const msgSuccess = document.querySelector(".msg-map");
  const msgError = document.querySelector(".error-msg-map");
  const mapButton = document.getElementById("btnMap");
  let itemsFound = false;

  if (listItems.length === 0) return;

  const itemsWithDistances = Array.from(listItems).map((item) => {
    const itemLat = parseFloat(item.dataset.lat);
    const itemLng = parseFloat(item.dataset.long);
    const distance = calculateDistance(searchLat, searchLng, itemLat, itemLng);
    return { item, distance };
  });

  itemsWithDistances.sort((a, b) => a.distance - b.distance);

  itemsWithDistances.forEach(({ item, distance }) => {
    if (distance <= maxDistanceKm) {
      item.classList.remove("d-none");
      itemsFound = true;
    } else {
      item.classList.add("d-none");
    }
  });

  if (itemsFound) {
    msgSuccess?.classList.remove("d-none");
    msgError?.classList.add("d-none");
    mapButton?.click();
  } else {
    mapButton?.click();
    msgSuccess?.classList.add("d-none");
    msgError?.classList.remove("d-none");
  }
};

const showAllItems = () => {
  const items = document.querySelectorAll(".list__item");
  document.querySelector(".msg-map")?.classList.add("d-none");
  document.querySelector(".error-msg-map")?.classList.add("d-none");
  items.forEach((item) => item.classList.remove("d-none"));
};

const handleAutocomplete = (map) => {
  const input = document.getElementById("buscadorMap");
  const suggestionBox = document.querySelector(".search-suggest");
  const suggestionBoxContainer = document.getElementById("search-suggest-container");
  const searchContainer = document.querySelector(".search__container");

  if (!input || !suggestionBox || !suggestionBoxContainer || !searchContainer) return;

  let debounceTimeout;

  input.addEventListener("input", () => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(async () => {
      if (input.value.length > 0) {
        suggestionBox.classList.add("show");
        const searchTerm = normalizeText(input.value.trim());
        const placesSuggest = await fetchAutocompleteSuggestions(searchTerm);
        if (placesSuggest.length > 0) {
          let placeSuggestHTML = "";
          placesSuggest.forEach(({ description, place_id }) => {
            placeSuggestHTML += `
              <li class="item-search" data-key="${place_id}">
                <div class="item-search-img">
                  <img width="24" height="24" loading="lazy" src="${LOCATION_PIN}" />
                </div>
                <div><p>${description}</p></div>
              </li>
            `;
          });
          suggestionBoxContainer.innerHTML = placeSuggestHTML;
          selectSuggest(map);
        } else {
          suggestionBoxContainer.innerHTML = "";
        }
      } else {
        suggestionBox.classList.remove("show");
        showAllItems();
      }
    }, 800);
  });

  input.addEventListener("click", () => {
    if (input.value.length > 0) {
      suggestionBox.classList.add("show");
    }
  });

  window.addEventListener("click", (e) => {
    if (!searchContainer.contains(e.target)) {
      suggestionBox.classList.remove("show");
    }
  });
};

const selectSuggest = (map) => {
  const suggestionBox = document.querySelector(".search-suggest");
  if (!suggestionBox) return;

  const updateListeners = () => {
    const itemsSearch = document.querySelectorAll(".item-search");
    itemsSearch.forEach((item) => {
      item.addEventListener("click", async () => {
        suggestionBox.classList.remove("show");
        const placeId = item.dataset.key;
        const data = await fetchSuggestDetails(placeId);
        if (!data) return;
        const { lat, lng } = data;
        const value = item.querySelector("p").textContent;
        map.flyTo([lat, lng], 10);
        addMarker(map, lat, lng, value);
        document.getElementById("buscadorMap").value = value;
        filterNearbyItems(lat, lng, 80);
      });
    });
  };

  updateListeners();
};

const clickItemMap = (map) => {
  const items = document.querySelectorAll(".list__item");
  const button = document.getElementById("btnMap");
  const listContainer = document.querySelector(".list__container");
  const svgLista = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" ... </svg>`;

  items.forEach((item) => {
    item.addEventListener("click", () => {
      const lat = parseFloat(item.dataset.lat);
      const long = parseFloat(item.dataset.long);
      if (listContainer.classList.contains("show")) {
        listContainer.classList.remove("show");
        if (button) button.innerHTML = `${svgLista}<span>LISTA</span>`;
      }
      map.setView([lat, long], 15);
    });
  });
};

window.addEventListener("DOMContentLoaded", async () => {
  const map = L.map("map-single", { scrollWheelZoom: false }).setView([-38.416097, -63.616672], 6);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 20,
  }).addTo(map);

  await fetchPuntos();
  addPuntosInList(dataPuntos, map);

  btnMap();
  clickItemMap(map);
  handleAutocomplete(map);
  closeModal();
  openModal(map);
  openModalVentas();
  closeModalVentas();
  clickLocationStandAlone(map);
});



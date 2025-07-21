

// ----------------------------------------------------------------------
// FILTROS APLICADOS
let filtersGroup = [];

const handleFilterClick = () => {
  const filterBtnPanel = document.querySelectorAll('.filter-btn-panel');

  // Botones Normales
  if (filterBtnPanel.length > 0) {
    filterBtnPanel.forEach(filter => {
      filter.addEventListener('click', e => {
       
        if (filter.classList.contains('active')) {
          // Si está activado
          removeFilterApply(e.target);
        } else {
          // No está activo
          addFilterApply(e.target);
      
        }
        updateFilterApply();
        deletePageFromUrl();
     
      });
    });
  }

}

const removeFilterApply = (e) => {
  let filterName = e.textContent;
  let filterGroup = e.dataset.group;

  // Lo elimino del array y actualizo
  filtersGroup = filtersGroup.filter(filter => {
    return filter.textContent !== filterName || filter.dataset.group !== filterGroup;
  });


 
}

const addFilterApply = (e) => {

  let filterName = e.textContent;
  let filterGroup = e.dataset.group;

  if (filterGroup === 'order') {
    // Eliminar cualquier otro filtro de tipo "order"
    filtersGroup = filtersGroup.filter(filter => filter.dataset.group !== 'order');
  }
  if(filterGroup === 'range'){
    filtersGroup = filtersGroup.filter(filter => filter.dataset.group !== 'range');
  }

  // Verificar si el filtro ya está presente en el array
  const index = filtersGroup.findIndex(filter => filter.textContent === filterName && filter.dataset.group === filterGroup);
  if (index === -1) {
    // Si no está presente, lo agregamos
    filtersGroup.push(e);
  } else {
    // Si está presente, lo eliminamos
    filtersGroup.splice(index, 1);
  }

 
}

const updateFilterApply = () => {
  const appliedFiltersContainer = document.getElementById("apply");
  
  // Verificación adicional para asegurarse de que el contenedor existe
  if (!appliedFiltersContainer) {
    console.error("El contenedor de filtros aplicados no existe en el DOM.");
    return;
  }
  
  let html = '';
  filtersGroup.forEach(element => {
    let name = element.dataset.group === 'range' ? element.dataset.value : element.textContent;
    let group = element.dataset.group;


    html += `
      <li class="element-remove" data-min="${element.dataset.min ? element.dataset.min : ''}" data-max="${element.dataset.max ? element.dataset.max : ''}" data-group="${group}" data-name="${element.dataset.name}" data-order="${element.dataset.order}">
        <button type="button" data-name="${element.dataset.name}" data-order="${element.dataset.order}">
          <i class="fa-solid fa-xmark"></i>${name}
        </button>
      </li>
    `;
  });

  appliedFiltersContainer.innerHTML = html;
  rmFilterClick(); // Mover la llamada aquí para asegurar que siempre se añadan los event listeners
}

const rmFilterClick = () => {
  let btns = document.querySelectorAll('.element-remove');
  let existingParams = {};
  let selectedCategories = [];
  
  if (btns.length > 0) {
    btns.forEach(btn => {
      btn.addEventListener('click', e => {
        const params = getURLParameters();
        selectedCategories = params.tipo || [];
        // Remover el filtro 'tipo' de la URL y del array de categorías seleccionadas
        if (btn.dataset.group === 'tipo') {
          let desactiveFilter = document.querySelector(`.filter-btn-panel[data-name="${btn.dataset.name}"]`);
          if (desactiveFilter) desactiveFilter.classList.remove('active');
          selectedCategories = selectedCategories.filter(category => category !== btn.dataset.name);
          removeMultiplyFilterFormURL("tipo", btn.dataset.name);
        } 
        // Remover los filtros 'min_price' y 'max_price' de la URL
        else if (btn.dataset.group === 'range') {
          removeFilterFromURL('max_price', btn.dataset.max);
          removeFilterFromURL('min_price', btn.dataset.min);
          handlePrice(true);
          filtersGroup = filtersGroup.filter(filter => filter.dataset.group !== btn.dataset.group);
          delete params.max_price;
          delete params.min_price;
        } 
        // Remover otros filtros de la URL según el tipo
        else {
          if (btn.dataset.name === 'precio') {
            let desactiveFilter = document.querySelector(`.filter-btn-panel[data-order="${btn.dataset.order}"]`);
            if (desactiveFilter) desactiveFilter.classList.remove('active');
            delete params.precio;
          } else {
            let desactiveFilter = document.querySelector(`.filter-btn-panel[data-name="${btn.dataset.name}"]`);
            if (desactiveFilter) desactiveFilter.classList.remove('active');
            delete params.destacados;
          }
          removeFilterFromURL(btn.dataset.name, btn.dataset.order);
        }
        
        // Remover el filtro actual de 'filtersGroup'
        filtersGroup = filtersGroup.filter(filter => filter.dataset.name !== btn.dataset.name);
        btn.remove();
    
        // Actualizar el objeto 'existingParams' con los parámetros restantes
        existingParams = { ...params };
        delete existingParams["tipo"];
 
        // Generar 'customParams' para otros filtros seleccionados
        let customParams = { ...existingParams, tipo: selectedCategories };

     
        applyFilters(customParams);
        window.location.reload();
      });
    });
  }
}

// PARA HACER
// CARGAR LOS FILTROS EN PAGINA -----> MOSTRAR LOS FILTROS APLICADOS
// AGREGAR LAZY LOADING PRODUCTOS Y FILTROS 
// FILTROS MOBILE Y DE ENTRADA


// ----------------------------------------------------------------------
// URLS FUNC
const applyFilterFromURL = (urlParams) => {
  const filters = ["tipo", "precio", "destacados", "min_price", "max_price"];
  return filters.some((param) => urlParams[param]);
};

const insertFilterFromURL = (type, name) => {
  updateURLParams(type, name);
};

const removeFilterFromURL = (type) => {
  updateURLParams(type, null);
};

const insertMultiplyFilterFormURL = (type, name) => {
  updateURLParams(type, name, true, false);
};

const removeMultiplyFilterFormURL = (type, name) => {
  updateURLParams(type, name, true, true);
};

const insertPriceFromURL = (minPrice, maxPrice) => {
  updateURLParams("min_price", minPrice);
  updateURLParams("max_price", maxPrice);
};

const updateURLParams = (type, value, multiple = false, remove = false) => {
  const currentURL = window.location.href;
  const searchParams = new URLSearchParams(window.location.search);

  if (multiple) {
    let currentValues = searchParams.getAll(type);
    if (remove) {
      // Remove the value if it exists
      currentValues = currentValues.filter((val) => val !== value);
    } else {
      // Add the value if it doesn't exist
      if (!currentValues.includes(value)) {
        currentValues.push(value);
      }
    }
    // Remove all current parameters of this type
    searchParams.delete(type);
    // Re-add each value as a separate parameter
    currentValues.forEach((val) => {
      if (val) {
        searchParams.append(type, val);
      }
    });
  } else {
    if (value) {
      searchParams.set(type, value);
    } else {
      searchParams.delete(type);
    }
  }

  const newURL = currentURL.split("?")[0] + "?" + searchParams.toString();
  window.history.pushState({ path: newURL }, "", newURL);
};

const getURLParameters = () => {
  const searchParams = new URLSearchParams(window.location.search);
  const params = {};
  searchParams.forEach((value, key) => {
    if (!params[key]) {
      params[key] = [];
    }
    params[key].push(value);
  });
  return params;
};

// Obtener los parámetros de la URL al cargar la página
const dropdownFilters = () => {
  const filtersContainers = document.querySelectorAll(".dropdown-filter");
  if (filtersContainers.length > 0) {
    filtersContainers.forEach((filterContainer) => {
      const button = filterContainer.firstElementChild;
      const ul = filterContainer.firstElementChild.nextElementSibling;
      button.addEventListener("click", (e) => {
        ul.classList.toggle("active");
      });
    });
  }
};


// FILTROS ACTIVOS CUANDO HAY PARAMETROS
const activeFiltersByParams = () => {
  const urlParams = getURLParameters();
  const { max_price, min_price, tipo, precio, destacados } = urlParams;
  if (Array.isArray(tipo) && tipo.length > 0) {
    const btnTipo = document.querySelectorAll('.filter-tipo.tipo')
    btnTipo.forEach(filtro => {
      tipo.forEach(slug => {
        if(filtro.dataset.name === slug){
          filtro.parentElement.parentElement.classList.add('active')
          filtro.classList.add('active')
          addFilterApply(filtro)
        }
      });
    });
  }
  if (precio) {
    const btnPrecio = document.querySelector(`.filter-order-btn.order[data-order="${precio}"]`);
    btnPrecio.classList.add('active')
    addFilterApply(btnPrecio)
  
  }
  if (destacados) {
    const btnDestacados = document.querySelector(`.filter-order-btn.order[data-order="${destacados}"]`);
    btnDestacados.classList.add('active')
    addFilterApply(btnDestacados)
  }

  if(min_price && max_price){
    const priceOutput = document.getElementById("price-output");
    addFilterApply(priceOutput)
  }
  
  updateFilterApply();
}

// CLEAR ALL FILTERS
const btnClearAll = () => {
  const clearBtn = document.getElementById('clear-filter')
  if(clearBtn){
    clearBtn.addEventListener('click', e => {
      // BORRO LOS PARAMS
      history.replaceState({}, document.title, window.location.pathname);
      // DESACTIVO LOS FILTROS
      filterBtnPanel.forEach(filter => {
        filter.classList.remove('active')
      });

      // RESETEO LOS PRECIOS
      handlePrice(true);

      // SACO LOS FILTROS APLICADOS
      filtersGroup = []
      updateFilterApply();


      // VUELVO A OBTENER LOS PRODUCTOS
      let resetParams = {
        tipo: catSlug
      }
      applyFilters(resetParams);
    })
  }
}

// NUEVO CODIGO
window.addEventListener("DOMContentLoaded", async () => {
  try {
    const urlParams = getURLParameters();
    if (pageSubCategoryContainer) {

      const filtersApplied = applyFilterFromURL(urlParams);
      if (filtersApplied) {
   
        showProductsLoader("listProducts");
        let currentPage = urlParams.page ? urlParams.page[0] : 1
        
        applyFilters(urlParams, currentPage);
        
      } else {
    
        const totalProducts = await getTotalProductsInCategory(catID, '');
        if (totalProducts !== undefined) {
          
          updatePagination(totalProducts, 'listProducts');
        } 
   
      }

      activeFiltersByParams();
      btnClearAll();
      openModalFilter();
      closeModalFilter();
      handleFilters();
      dropdownFilters();
      filterOrderClick();
      filterTipoClick();
      handlePrice();

    }
  } catch (error) {
    console.error(error);
  }
});
// FILTROS DE CATEGORIAS FUERA DEL PANEL DE FILTROS
const handleFilters = () => {
  const filtrosContainer = document.querySelector(".filtros");
  const filtros = filtrosContainer.querySelectorAll("button");
  let totalProducts = 0;
  filtros.forEach((filtro) => {
    filtro.addEventListener("click", async (e) => {
      filtros.forEach((filtro) => {
        filtro.classList.remove("active");
      });
      filtro.classList.add("active");
      const nombre = filtro.dataset.name.toLocaleLowerCase();
      insertFilterFromURL("tipo", nombre);
      showProductsLoader("listProducts");
      const catID = filtro.dataset.id;
      totalProducts = await getTotalProductsInCategory(catID, '');
      if (totalProducts === 0) {
        showError("listProducts", "no-productos");
      } else {
        getAllProductsByCategory(
          catID,
          perPage,
          currentPage,
          totalProducts
        );

       

       
      }
    });
  });
};
// FILTROS DE ORDEN FILTRO TODO OK
const filterOrderClick = () => {
  const filterOrders = document.querySelectorAll(".filter-order-btn");
  if (filterOrders.length > 0) {
    filterOrders.forEach((filter) => {
      filter.addEventListener("click", async (e) => {
        e.preventDefault();
        let urlParams = getURLParameters();
        const filterName = filter.dataset.name;
        const filterOrder = filter.dataset.order;
        const clickedFilter = e.currentTarget;
        if(clickedFilter.classList.contains('active')){
          // Remover parámetros de la URL
          clickedFilter.classList.remove('active')
          if (filterName === "precio") {
            delete urlParams.precio;
          } else if (filterName === "destacados") {
            delete urlParams.destacados;
          }
          removeFilterFromURL(clickedFilter.dataset.name);
        }else{
          filterOrders.forEach((filter2) => {
            filter2.classList.remove("active");
            removeFilterFromURL(filter2.dataset.name);
          });
          clickedFilter.classList.add('active')

          if (filterName === "precio") {
            urlParams.precio = filterOrder;
            delete urlParams.destacados;
          } else if (filterName === "destacados") {
            urlParams.destacados = true;
            delete urlParams.precio;
          }
          insertFilterFromURL(filterName, filterOrder);
        }
        applyFilters(urlParams);
        deletePageFromUrl();
        window.location.reload();
      });
    });
  }
}; 

// FILTROS DE CATEGORIA TODO OK
const filterTipoClick = () => {
  const filterTipo = document.querySelectorAll(".filter-tipo");
  let selectedCategories = [];
  let existingParams = {}; // Initialize existingParams as an empty object

  if (filterTipo.length > 0) {
    filterTipo.forEach((filter) => {
      filter.addEventListener("click", async (e) => {
        // Obtiene todos los parámetros de la URL en cada clic
        const urlParams = getURLParameters();
        selectedCategories = urlParams.tipo || [];
        let slug = filter.dataset.name;
        if (filter.classList.contains("active")) {
          // Si el filtro ya está activo, desactívalo y remuévelo de la URL
          filter.classList.remove("active");
          selectedCategories = selectedCategories.filter(
            (category) => category !== slug
          );
          removeMultiplyFilterFormURL("tipo", slug);
        } else {
          // Si el filtro no está activo, actívalo y añádelo a la URL
          filter.classList.add("active");
          if (!selectedCategories.includes(slug)) {
            selectedCategories.push(slug);
          }
          insertMultiplyFilterFormURL("tipo", slug);
        }

        // Copia todos los parámetros de la URL excepto 'tipo'
        existingParams = { ...urlParams };
        delete existingParams["tipo"];

        // Aplica los filtros con los parámetros actualizados
        let customParams = { ...existingParams, tipo: selectedCategories };
     
        applyFilters(customParams);
        deletePageFromUrl();
        window.location.reload();
      });
    });
  }
};



// FILTROS DE PRECIOS
let priceSlider = null; // Variable para almacenar la instancia del slider
const handlePrice = (reset = false) => {
  const priceRange = document.getElementById("price-range");
  const priceOutput = document.getElementById("price-output");
  const urlParams = getURLParameters();
  let defaultMinPrice = priceOutput.dataset.min;
  let defaultMaxPrice = parseInt(priceOutput.dataset.max);

  // Verificar si hay valores de precio en urlParams
  if (urlParams.min_price || urlParams.max_price) {
    defaultMinPrice = parseInt(urlParams.min_price) || defaultMinPrice;
    defaultMaxPrice = parseInt(urlParams.max_price) || defaultMaxPrice;
  }

  const sliderOptions = {
    type: "double",
    skin: "flat",
    min: 0,
    max: defaultMaxPrice,
    from: defaultMinPrice,
    to: defaultMaxPrice,
    prefix: "$",
    onChange: function (data) {
      priceOutput.textContent = `Precio: de ${formatCurrency(
        data.from
      )} - ${formatCurrency(data.to)}`;
      priceOutput.dataset.min = data.from
      priceOutput.dataset.value = `${formatCurrency(
        data.from
      )} - ${formatCurrency(data.to)}`;
    },
    onFinish: function (data) {
      
      insertPriceFromURL(data.from, data.to);
      const urlParams = getURLParameters();

      // Combinar los parámetros de precio con los otros filtros aplicados
      let customParams = {
        ...urlParams,
        min_price: data.from,
        max_price: data.to,
      };

      applyFilters(customParams);
      addFilterApply(priceOutput);
      updateFilterApply();
      deletePageFromUrl();
    },
  };

  if (!reset) {
    // Crear la instancia del slider solo si reset es false
    priceSlider = ionRangeSlider(priceRange, sliderOptions);
  } else {
    // Actualizar la instancia del slider si reset es true
    priceSlider.update({
      from: defaultMinPrice,
      to: defaultMaxPrice
    });
    priceOutput.textContent = `Precio: de ${formatCurrency(
      defaultMinPrice
    )} - ${formatCurrency(defaultMaxPrice)}`;
  }
};


// FILTRO DE DIMENSIONES
const applyFilters = async (urlParams, currentPage) => {

  currentPagePagination = currentPage || 1; // Reinicia la página actual a 1 si no se proporciona una página específica

  showProductsLoader('listProducts');
  const { max_price, min_price, tipo, precio, destacados } = urlParams;
  const params = [];
  const ids = [];
  let totalProductosCat = 0;
  if (Array.isArray(tipo) && tipo.length > 0) {
  
    // Usamos Promise.all para esperar todas las llamadas asíncronas
    await Promise.all(
      tipo.map(async (cat) => {
        try {
          const resCat = await getIdBySlugCat(cat);
          // Aquí deberías añadir el ID a la lista `ids`
          ids.push(resCat[0].id);
          const count = parseFloat(resCat[0].count) || 0;
          totalProductosCat += count;
     
        } catch (error) {
          console.error("Error obteniendo ID de categoría:", error);
        }
      })
    );
    // Una vez que se han obtenido todos los IDs, los agregamos a los parámetros
    params.push(`category=${ids.join(",")}&orderby=menu_order&order=asc`);
  }else{
    params.push(`category=${catID}&orderby=menu_order&order=asc`);
    const paramsCustom = new URLSearchParams(window.location.search);
    if (paramsCustom.has("destacados")) {
      paramsCustom.set("featured", paramsCustom.get("destacados"));
      paramsCustom.delete("destacados"); // Eliminar el parámetro original
    }
    const queryParams = paramsCustom.toString();
    const resCat = await getTotalProductsInCategory(catID, queryParams);
    totalProductosCat = parseFloat(resCat)
  }
  if (precio) {
    params.push(`orderby=price&order=${precio}`);
  }
  if (destacados) {
    params.push(`featured=${destacados}`);
  }
  if (max_price) {
    params.push(`max_price=${max_price}`);
  }
  if (min_price) {
    params.push(`min_price=${min_price}`);
    if (precio) {
      params.push(`order=${precio}`);
    } else {
      params.push(`order=asc`);
    }
  }

  const query = params.length > 0 ? "?" + params.join("&") : "";
  // const data = await woocommerceAPI(API, `products${query}&status=publish&per_page=350`);
  
  // console.log('PRODUCTOS COUNT' + totalProductosCat)
  if (totalProductosCat.length <= 0) {
    showError("listProducts", "no-productos");
  } else {
 
    const data2 = await woocommerceAPI(API, `products${query}&status=publish&per_page=${perPage}&page=${currentPagePagination}`);

   

    await showProductsHTML(data2, "listProducts");

    updatePagination(totalProductosCat, "listProducts");
  }
};

const openModalFilter = () => {
  const btn = document.querySelector(".filter-order");
  if (btn) {
    let modal = document.querySelector(".modal-backdrop-filter");
    btn.addEventListener("click", (e) => {
      modal.classList.add("active");
    });
  }
};

const closeModalFilter = () => {
  const btn = document.getElementById("closeModal");
  if (btn) {
    let modal = document.querySelector(".modal-backdrop-filter");
    btn.addEventListener("click", (e) => {
      modal.classList.remove("active");
    });
  }
};







let carrArchive = document.querySelectorAll('.splide-archive')
if(carrArchive.length > 0){
  carrArchive.forEach(slide => {
    splideCarrousel = new Splide( slide,{
      perPage: 1,
      arrows: true,
      pagination: false,
      breakpoints: {
          878: {
              perPage: 1,
              pagination: true,
          },
          720: {
              perPage: 1,
          }
    }
    }).mount();
  })
}



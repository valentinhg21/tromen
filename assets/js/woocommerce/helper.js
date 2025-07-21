const pageSubCategoryContainer = document.querySelector(".archive-subcategory") || document.querySelector(".archive-product");
const catID = pageSubCategoryContainer.id;
const catSlug = pageSubCategoryContainer.dataset.slug;
const currentPageInput = document.getElementById('currentPage').value || 1;
const urlParamsQueryPagination = new URLSearchParams(window.location.search);

let currentPage = 1; // Página actual
let currentPagePagination = parseInt(urlParamsQueryPagination.get('page'), 10) || 1; // Si no hay `page`, usa 1

let totalProducts = 0; // Total de productos

const perPage = 9; // Productos por página
const API = JSON.stringify(ajax_var.site).replace(/['"]+/g, "");
const THEME = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");

// Constantes Filtros
const filterBtnPanel = document.querySelectorAll('.filter-btn-panel');


const getProductsByCategories = async (cat) => {
    try {
        const data = await woocommerceAPI(API, `products?category=${cat}&status=publish&per_page=${perPage}&featured=true`);
        await showProductsHTML(data);

      } catch (error) {
        console.error('Error:', error);
      }
}
// API WOOCOMMERCE
const woocommerceAPI = async (API, prefix) => {
    const requestOptions = {
        method: 'GET',
        headers: {
          'Authorization': 'Basic ' + btoa('ck_89fc0a59a920102e7a2b7ef2f333c0810a270fc1' + ':' + 'cs_95a4458a47202c9daa33c041e00bbdde492bc398'),
          'Content-Type': 'application/json'
        }
    };
    try {
        const response = await fetch(`${API}wc/v3/${prefix}`, requestOptions);
        if (!response.ok) {
            throw new Error('Failed to fetch data from WooCommerce API');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data from WooCommerce API:', error);
        console.error(error)
    }

}

// LOADER PRODUCTS
const showProductsLoader = (productContainer) => {
    let container = document.getElementById(productContainer);
    let productHTML = ``
    productHTML += `
        <li class="splide__slide">
            <div class="loading-product">
                <div class="product-image"></div>
                <div class="product-body p-relative">
                        <p class="load-name"></p>
                        <p class="text-red load-cat"></p>
                        <button class="permalink load-perma">
                            
                        </button>
                </div>
            </div>
        </li>
        <li class="splide__slide">
            <div class="loading-product">
                <div class="product-image"></div>
                <div class="product-body p-relative">
                        <p class="load-name"></p>
                        <p class="text-red load-cat"></p>
                        <button class="permalink load-perma">
                            
                        </button>
                </div>
            </div>
        </li>
        <li class="splide__slide">
            <div class="loading-product">
                <div class="product-image"></div>
                <div class="product-body p-relative">
                        <p class="load-name"></p>
                        <p class="text-red load-cat"></p>
                        <button class="permalink load-perma">
                            
                        </button>
                </div>
            </div>
        </li>
        <li class="splide__slide">
            <div class="loading-product">
                <div class="product-image"></div>
                <div class="product-body p-relative">
                        <p class="load-name"></p>
                        <p class="text-red load-cat"></p>
                        <button class="permalink load-perma">
                            
                        </button>
                </div>
            </div>
        </li>
        <li class="splide__slide">
            <div class="loading-product">
                <div class="product-image"></div>
                <div class="product-body p-relative">
                        <p class="load-name"></p>
                        <p class="text-red load-cat"></p>
                        <button class="permalink load-perma">
                            
                        </button>
                </div>
            </div>
        </li>
        <li class="splide__slide">
            <div class="loading-product">
                <div class="product-image"></div>
                <div class="product-body p-relative">
                        <p class="load-name"></p>
                        <p class="text-red load-cat"></p>
                        <button class="permalink load-perma">
                            
                        </button>
                </div>
            </div>
        </li>
    `
    container.innerHTML = productHTML;
}

const showProductsHTML = async (data, productContainer) => {
    showProductsLoader(productContainer)
    let productHTML = '';
    const container = document.getElementById(productContainer);
    let price_feeHTML = '';
    if(data.length > 0){
        for (const product of data) {
            // Validar los atributos del producto
            const { name, categories, url, images, price_html, price, discount_html, price_fee} = product;
            // Verificar si el producto tiene imágenes
            let imageUrl = '';
            let precio = '';
            
            if(price_html.length > 0){
                precio = price_html
            }else{
                precio = '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>&nbsp;0</bdi></span>'
            }
            if (images.length > 0) {
                imageUrl = images[0].src;
            } else {
                // URL de la imagen de referencia en caso de que el producto no tenga imágenes
                imageUrl = `${THEME}/assets/img/productos/default.png`;
            }
            // Fecha objetivo (2 de enero de 2025)


            // MODIFICAR ACÁ CAMBIAR A >= Para que se active
        
            price_feeHTML = price_fee



            // Obtener el tamaño de la imagen
            const imageSize = await getImageSize(imageUrl);
            // Construir el atributo srcset para la imagen
            const imageSrcset = imageUrl ? `${imageUrl} ${imageSize.width}w` : '';

            // Construir el HTML del producto
            productHTML += `
                <li class="splide__slide">
                    <a href="${url}" class="product-card fade-in-bottom" aria-label="Ver detalles de ${name}">
                        <div class="product-image">
                            <img fetchpriority="high" decoding="async" class="attachment-large size-large" alt="${name}" src="${imageUrl}" srcset="${imageSrcset}" width="${imageSize.width}" height="${imageSize.height}">
                            ${discount_html}
                        </div>
                        <div class="product-body p-relative">
                            <p>${name}</p>
                            <p class="product-price-slide">${precio}</p>
                            ${price_feeHTML}
                            <p class="text-red">${categories[0].name}</p>
                            <button class="permalink">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </a>
                </li>
            `;
        }
        container.innerHTML = productHTML;
    }else{
        const current_lang = document.getElementById('current-lang-tromen').value

        container.innerHTML = `<p class="no-products show active">${current_lang === 'en' ? 'There is no product.' : 'No hay ningún producto.'}</p>
        `;
    }
}


// IMAGES PRODUCTS 
async function getImageSize(url) {
    return new Promise(resolve => {
        const img = new Image();
        img.onload = () => {
            resolve({ width: img.width, height: img.height });
        };
        img.src = url;
    });
}

// TOTAL PRODUCTS BY CATEGORY
const getTotalProductsInCategory = async (cat, queryParams = '') => {   
    try {
        // const data = await woocommerceAPI(API, `products/categories?include=${cat}&status=publish`);
      
        let URL = `products?category=${cat}&orderby=menu_order&order=asc&status=publish&per_page=100&${queryParams}`
        console.log(URL)
        const data = await woocommerceAPI(API, `${URL}`);
     
        return data.length
        // return data.count;
    } catch (error) {
        console.error('Error:', error);
        return 0;
    }
}
// GET PRODUCTS PER PAGE By Category
const getAllProductsByCategory = async (cat, perPage = 9, page = 1, total) => {
    try { 
        const data = await woocommerceAPI(API, `products?category=${cat}&status=publish&per_page=${perPage}&page=${page}&orderby=menu_order`);
        console.log(data + 'ByCategory')
        await showProductsHTML(data, 'listProducts');
        updatePagination(total, 'listProducts');
       
    } catch (error) {
        console.error('Error:', error);
    }
}

// GET ORDER PRODUCTS PRICE - ASC , DESC - BY CATEGORY
const getOrderProductsByPrice = async (cat, perPage, page, total, order = 'asc') => {
    try {
        const data = await woocommerceAPI(API, `products?category=${cat}&status=publish&per_page=${perPage}&page=${page}&orderby=price&order=${order}`);
        await showProductsHTML(data, 'listProducts');
        updatePagination(total, 'listProducts');
      
    } catch (error) {
        console.error('Error:', error);
    }
}

//GET PRODUCTS FEATURES BY CAT
const getOrderProductsFeature = async (cat, perPage, page, total) => {
    try {
        const data = await woocommerceAPI(API, `products?category=${cat}&status=publish&per_page=${perPage}&page=${page}&featured=true&orderby=menu_order&order=asc`);
        await showProductsHTML(data, 'listProducts');
        updatePagination(total, 'listProducts');
      
    } catch (error) {
        console.error('Error:', error);
    }
}

// GET PRICE MAX AND MIN PRODUCTS 
const getProductsMaxMinPrice = async (cat, perPage, page, total, min, max) => {

    try {
        const data = await woocommerceAPI(API, `products?category=${cat}&status=publish&per_page=${perPage}&page=${page}&min_price=${min}&max_price=${max}&order=asc`);
     
        await showProductsHTML(data, 'listProducts');
        updatePagination(total, 'listProducts');
      
    } catch (error) {
        console.error('Error:', error);
    }
}

// ERROR MESSAGE
const showError = (containerError, type) => {
    let container = document.getElementById(containerError);
    if(type === 'no-productos'){
        let errorMSG = '';
        errorMSG += `
            <p class="no-products active">No se encontró productos.</p>
        `
        container.innerHTML = errorMSG
   
    }
}
const formatCurrency = (number) => {
  
    let price = number.toLocaleString('us-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0, // Elimina los decimales
        maximumFractionDigits: 0  // Elimina los decimales
    });
    return price.replace('US$', '$');
 
}

const getIdBySlugCat = async (slug) => {
   
    try {
        const data = await woocommerceAPI(API, `products/categories?slug=${slug}&status=publish`);
        return data

        
    } catch (error) {
        console.error('Error:', error);
    }
}


// PAGINATIONupdatePagination
const updatePagination = (totalPages, listContainer) => {

    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = ''; // Limpiar el contenedor de paginación    
    const totalPagesToShow = Math.ceil(totalPages / perPage); // Calcular el número total de páginas que se mostrarán
    const maxPages = 5; // Número máximo de páginas a mostrar antes de los puntos suspensivos
    let startPage = Math.max(1, currentPagePagination - Math.floor(maxPages / 2));
    let endPage = Math.min(totalPagesToShow, startPage + maxPages - 1);

    
    if (totalPagesToShow > maxPages) {
        if (endPage - startPage < maxPages - 1) {
            startPage = endPage - maxPages + 1;
        }
    }
    if (currentPagePagination > 1) {
        addArrowButton('previous', currentPagePagination - 1);
    }
    
    if (startPage > 1) {

        addPageButton(1, totalPages);
       
        if (startPage > 2) {
            addEllipsis();
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        addPageButton(i, totalPages);
    }
    
    if (endPage < totalPagesToShow) {
        if (endPage < totalPagesToShow - 1) {
            addEllipsis();
        }
        addPageButton(totalPagesToShow, totalPages);
    }
    
    if (currentPagePagination < totalPagesToShow) {
        addArrowButton('next', currentPagePagination + 1);
    }

    // Función para agregar botones de página
    function addPageButton(pageNumber, totalPages) {
        const button = document.createElement('a');
        const currentUrl = new URL(window.location.href);
        const params = currentUrl.searchParams;
        if (params.has("page")) {
            params.set("page", pageNumber); // Reemplazar el valor existente
        } else {
            params.append("page", pageNumber); // Agregarlo si no existe
        }
        const newUrl = `${currentUrl.origin}${currentUrl.pathname}?${params.toString()}`;

        button.href = newUrl
        button.textContent = pageNumber;
        button.setAttribute('data-page', pageNumber);
        button.addEventListener('click', (e) => handlePaginationClick(e , listContainer, totalPages));
        if (Number(pageNumber) === Number(currentPagePagination)) {
            button.classList.add('active');
        }
        paginationContainer.appendChild(button);
    }

    // Función para agregar puntos suspensivos
    function addEllipsis() {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        paginationContainer.appendChild(ellipsis);
    }

    // Función para agregar botones de flecha
    function addArrowButton(type, pageNumber) {
        const svgString = '<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#606060"/></svg>';
        const button = document.createElement('a');
        const currentUrl = new URL(window.location.href);
        const params = currentUrl.searchParams;
        if (params.has("page")) {
            params.set("page", pageNumber); // Reemplazar el valor existente
        } else {
            params.append("page", pageNumber); // Agregarlo si no existe
        }
        const newUrl = `${currentUrl.origin}${currentUrl.pathname}?${params.toString()}`;
        button.href = newUrl
        button.innerHTML = svgString;
        button.classList.add(type);
        button.rel = type
        button.addEventListener('click', () => {
            const productsContainer = document.getElementById('listProducts');
            currentPagePagination = pageNumber;
            productsContainer.scrollIntoView({ behavior: 'smooth' });
            const urlParams = getURLParameters();
            showProductsLoader('listProducts');
            let urlPage = new URL(window.location);
            urlPage.searchParams.set("page", pageNumber); // Agregar o actualizar query
            window.history.pushState({}, "", urlPage);
            if (Object.keys(urlParams).length !== 0){
                applyFilters(urlParams, currentPagePagination);
            }else{
                urlParams.tipo = catSlug
                applyFilters(urlParams, currentPagePagination);
            }
            window.location.reload();
        });
        paginationContainer.appendChild(button); // Agrega al final del contenedor
    }
}
// PAGINATION CLICK
const handlePaginationClick = (event, container) => {
    const productsContainer = document.getElementById(container);
    productsContainer.scrollIntoView({ behavior: 'smooth' });
    const buttons = document.querySelectorAll('#pagination button');
    buttons.forEach(btn => btn.classList.remove('active'));
    currentPagePagination = parseInt(event.target.getAttribute('data-page'));
    event.target.classList.add('active');
    showProductsLoader('listProducts');
    let urlPage = new URL(window.location);
    urlPage.searchParams.set("page", currentPagePagination); // Agregar o actualizar query
    window.history.pushState({}, "", urlPage);
    // Obtener los productos
    const urlParams = getURLParameters();

    if (Object.keys(urlParams).length !== 0){
        applyFilters(urlParams, currentPagePagination);
    }else{
        urlParams.tipo = catSlug
        applyFilters(urlParams, currentPagePagination);
    }
}

const deletePageFromUrl = () => {
    const newUrl = new URL(window.location);
    if (newUrl.searchParams.has("page")) { // Verifica si 'page' existe
        newUrl.searchParams.delete("page"); // Eliminar el parámetro 'page'
        window.history.pushState({}, "", newUrl); // Actualizar la URL sin recargar
    }
}
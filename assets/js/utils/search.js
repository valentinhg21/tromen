import { header, searchContainer, search, AJAX_URL, searchBtn, searchInput } from './helper.js'
export const searchHeader = () => {
    try {
        let splideSearch;
        let typingTimeout;

        const searchListContainer = document.getElementById('search-list-container')
        // Splide Results
        const initializeSplide = () => {
            splideSearch = new Splide('.splide.splide-search', {
                perPage: 3,
                arrows: true,
                pagination: false,
                breakpoints: {
                    878: {
                        perPage: 2,
                        pagination: false,
                        arrows: false,
                    },
                    720: {
                        perPage: 10,
                    }
                }
            }).mount();
        };
        initializeSplide();
        const handleResize = () => {
            if (window.innerWidth < 760) {
                if (splideSearch) {
                    splideSearch.destroy();
                    splideSearch = null;
                }
            } else {
                if (!splideSearch) {
                    initializeSplide();
                }
            }
        };
        window.addEventListener('resize', handleResize);
        handleResize();
        // Search Active
        searchBtn.addEventListener('click', e => {
            if(header.classList.contains('scrolling-default') || header.classList.contains('scrolling') ){
                header.classList.remove('search-transparent');
                searchContainer.classList.toggle('show')
                search.classList.toggle('show')
        
                // Blanco
            
            }else{
                header.classList.add('search-transparent');
                searchContainer.classList.toggle('show')
                search.classList.toggle('show')
            }
        })

        // Text Search
        searchInput.addEventListener('input', async e => {
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                const query = searchInput.value;
                if (query.trim().length > 0 ) {
                    searchProduct(query, searchListContainer, splideSearch);
                }
            }, 500); 
        })

        window.addEventListener('click', e => {
            let target = e.target
            if (!header.contains(target)) {
              header.classList.remove('search-transparent');
              searchContainer.classList.remove('show')
              search.classList.remove('show')
            }
        })
    } catch (error) {
        console.error(`${error}`);
    }

}

const loading = () => {
    let loading = "";
    loading += `
    <div class="w-100 d-flex justify-center py-lg-0 py-sm-2 py-4">
        <span class="loader"></span>
    </div>`;
    let container = document.getElementById('search-list-container');
    container.innerHTML = loading
}

const searchProduct = async (query, container, slide) => {
    try {
        loading();
        const data =  await API({term: query});
        await showProductsHTML(data, container, slide);
        
      } catch (error) {
        console.error('Error:', error);
      }
}

const showProductsHTML = async (data, container, slide) => {
    let productHTML = '';
    let errorHTML = '';
    const searchListContainer = document.getElementById('search-list-container')
    if(data.length > 0){
        for (const product of data) {
            // Validar los atributos del producto
            const { name, category, url, image, main_category, price, fee, discount, tags } = product;
            // Verificar si el producto tiene imágenes
            let categoryProduct = category ? category : main_category
            // Construir el HTML del producto
            productHTML += `
                <li class="splide__slide">
                    <a href="${url}" class="product-card" aria-label="Ver detalles de ${name}">
                        <div class="product-image">
                            ${image}
                        </div>
                         ${discount}
                         ${tags}
                        <div class="product-body p-relative">
                            <p>${name}</p>
                            <p class="product-price-slide" >${price}</p>
                            ${fee}
                            <p class="text-red">${categoryProduct}</p>
                            <button class="permalink">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </a>
                </li>
            `;
        }
        container.innerHTML = productHTML;

        // Actualizar el contenido del carrusel
        if (window.innerWidth < 760) {
            if (slide) {
                slide.destroy();
                slide = null;
            }
        } else {
            if (!slide) {
                slide.refresh();
            }
            slide.refresh();
        }


        searchListContainer.parentElement.parentElement.classList.add('active-arrows')

        
    }else{
        const current_lang = document.getElementById('current-lang-tromen').value
        if (window.innerWidth < 760) {
            if (slide) {
                slide.destroy();
                slide = null;
            }
        } else {
            if (!slide) {
                slide.refresh();
            }
            slide.refresh();
        }
        errorHTML += 
        `<p class="no-products show active">${current_lang === 'en' ? 'There is no product.' : 'No hay ningún producto.'}</p>
        `
        container.innerHTML = errorHTML;
        searchListContainer.parentElement.parentElement.classList.remove('active-arrows')

    }
}

const API = async (query = {}) => {
    try {
        const params = new URLSearchParams({ action: 'search_products', ...query });
        const url = `${AJAX_URL}?${params.toString()}`; // Construir la URL con los parámetros
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }
        const products = await response.json();
        return products.data;
    } catch (error) {
        console.error('Error fetching data', error);
        throw error; // Propagar el error para manejarlo en el llamador
    }
}


let currentPagePagination = 1;
const products = document.querySelectorAll('.splide__slide');
const totalProductos = products.length || 0;
window.addEventListener('DOMContentLoaded', () => {

    initialProductos(products);
    updatePagination(totalProductos, 'listProducts');
});

const updatePagination = (totalPages, listContainer) => {
    const perPage = 9;
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = '';
    const totalPagesToShow = Math.ceil(totalPages / perPage);
    const maxPages = 5;
    let startPage = Math.max(1, currentPagePagination - Math.floor(maxPages / 2));
    let endPage = Math.min(totalPagesToShow, startPage + maxPages - 1);

    // Ajustar las páginas de inicio y fin
    if (totalPagesToShow > maxPages && endPage - startPage < maxPages - 1) {
        startPage = endPage - maxPages + 1;
    }

    // Mostrar botón "previous" solo si no estamos en la primera página
    if (currentPagePagination > 1) {
        addArrowButton('previous', currentPagePagination - 1);
    }

    // Mostrar los botones de las páginas, incluidos los elipsis si es necesario
    if (startPage > 1) {
        addPageButton(1);
        if (startPage > 2) {
            addEllipsis();
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        addPageButton(i);
    }
    
    if (endPage < totalPagesToShow) {
        if (endPage < totalPagesToShow - 1) {
            addEllipsis();
        }
        addPageButton(totalPagesToShow);
    }

    // Mostrar botón "next" solo si no estamos en la última página
    if (currentPagePagination < totalPagesToShow) {
        addArrowButton('next', currentPagePagination + 1);
    }

    function addPageButton(pageNumber) {
        const button = document.createElement('button');
        button.textContent = pageNumber;
        button.setAttribute('data-page', pageNumber);
        button.addEventListener('click', (e) => handlePaginationClick(e, listContainer));
        if (pageNumber === currentPagePagination) {
            button.classList.add('active');
        }
        paginationContainer.appendChild(button);
    }

    function addEllipsis() {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        paginationContainer.appendChild(ellipsis);
    }

    function addArrowButton(type, pageNumber) {
        const svgString = '<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#606060"/></svg>';
        const button = document.createElement('button');
        button.innerHTML = svgString;
        button.classList.add(type);
        button.addEventListener('click', () => {
            const productsContainer = document.getElementById('listProducts');
            productsContainer.scrollIntoView({ behavior: 'smooth' });
            currentPagePagination = pageNumber; // Actualizar la página actual
            loadProducts(currentPagePagination);
            updatePagination(totalPages, listContainer); // Actualizar la paginación
        });
        paginationContainer.appendChild(button);
    }
};

const handlePaginationClick = (event, container) => {
    const productsContainer = document.getElementById(container);
    productsContainer.scrollIntoView({ behavior: 'smooth' });

    const buttons = document.querySelectorAll('#pagination button');
    buttons.forEach(btn => btn.classList.remove('active'));

    currentPagePagination = parseInt(event.target.getAttribute('data-page')); // Actualizar la página actual
    event.target.classList.add('active');

    loadProducts(currentPagePagination);
    updatePagination(products.length, container); // Actualiza la paginación después de cargar los productos
};

const loadProducts = (pageNumber) => {
    const products = document.querySelectorAll('.splide__slide');
    const perPage = 9;

    const startIndex = (pageNumber - 1) * perPage;
    const endIndex = startIndex + perPage;

    products.forEach((product, index) => {
        if (index >= startIndex && index < endIndex) {
            product.classList.remove('d-none');
        } else {
            product.classList.add('d-none');
        }
    });
};

const initialProductos = (products) => {
    const perPage = 9;
    setTimeout(() => {
        products.forEach((product, index) => {
            if (index < perPage) {
                product.classList.remove('d-none');
            } else {
                product.classList.add('d-none');
            }
        });
    }, 200);
};
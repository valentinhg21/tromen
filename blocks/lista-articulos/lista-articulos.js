const AJAX_URL = ajax_var.url;
const listaContainer = document.getElementById("lista-articulos");
const THEME = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
let perPage = 9;
let currentPagePagination = parseInt(document.getElementById('currentPageBlog').value) || 1;
let totalArticles = parseInt(document.getElementById('total-articles').value) || 0; 

window.addEventListener("DOMContentLoaded", async () => {
  try {
    updatePagination(totalArticles);
  } catch (error) {
    console.error("Error on DOMContentLoaded", error);
  }
});

// Función para hacer la llamada a la API
const fetchArticles = async (query = {}) => {
  const params = new URLSearchParams({ action: "get_article_data", ...query });
  const url = `${AJAX_URL}?${params.toString()}`;
  const response = await fetch(url);
  if (!response.ok) throw new Error("Failed to fetch data");
  const articles = await response.json();
  totalArticles = articles.data.total_posts;
  return articles.data.articles;
};

// Función para actualizar la paginación
const updatePagination = (totalPages) => {
  const paginationContainer = document.getElementById("pagination");
  paginationContainer.innerHTML = "";
  const totalPagesToShow = Math.ceil(totalPages / perPage);
  const maxPages = 5;
  let startPage = Math.max(1, currentPagePagination - Math.floor(maxPages / 2));
  let endPage = Math.min(totalPagesToShow, startPage + maxPages - 1);

  if (totalPagesToShow > maxPages && endPage - startPage < maxPages - 1) {
    startPage = endPage - maxPages + 1;
  }

  if (currentPagePagination > 1) addArrowButton("previous", currentPagePagination - 1);
  if (startPage > 1) addPageButton(1);
  if (startPage > 2) addEllipsis();
  for (let i = startPage; i <= endPage; i++) addPageButton(i);
  if (endPage < totalPagesToShow - 1) addEllipsis();
  if (endPage < totalPagesToShow) addPageButton(totalPagesToShow);
  if (currentPagePagination < totalPagesToShow) addArrowButton("next", currentPagePagination + 1);

  function addPageButton(pageNumber) {
    const button = document.createElement("a");
    button.href = updateUrl(pageNumber);
    button.textContent = pageNumber;
    button.setAttribute("data-page", pageNumber);
    button.addEventListener("click", handlePaginationClick);
    if (pageNumber === currentPagePagination) button.classList.add("active");
    paginationContainer.appendChild(button);
  }

  function addEllipsis() {
    const ellipsis = document.createElement("span");
    ellipsis.textContent = "...";
    paginationContainer.appendChild(ellipsis);
  }

  function addArrowButton(type, pageNumber) {
    const svgString = '<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#606060"/></svg>';
    const button = document.createElement("a");
    button.innerHTML = svgString;
    button.href = updateUrl(pageNumber);
    button.classList.add(type);
    button.rel = type;
    button.addEventListener("click", handlePaginationClick);
    paginationContainer.appendChild(button);
  }
};

// Función para manejar clics de paginación y recargar la página
const handlePaginationClick = (event) => {
  event.preventDefault();
  const page = parseInt(event.currentTarget.getAttribute("data-page")) || parseInt(new URL(event.currentTarget.href).searchParams.get("paged"));
  if (page !== currentPagePagination) {
    window.location.href = updateUrl(page);
  }
};

// Función para actualizar la URL con el nuevo número de página
const updateUrl = (pageNumber) => {
  const currentUrl = new URL(window.location.href);
  const params = currentUrl.searchParams;
  if (params.has("paged")) {
    params.set("paged", pageNumber);
  } else {
    params.append("paged", pageNumber);
  }
  return `${currentUrl.origin}${currentUrl.pathname}?${params.toString()}`;
};

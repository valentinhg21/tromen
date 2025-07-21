window.addEventListener('DOMContentLoaded', () => {
    let items = document.querySelectorAll('.faq');
    const faqContainer = document.getElementById('list')
    const filters = document.querySelectorAll('.tab-button');
    const search = document.getElementById('search-faq');
    const tabContainer = document.querySelector('.tab');
    let searchResults = document.querySelector('.resultsList');
    const searchContainer = document.querySelector('.search-container')
    let typingTimeout;
    const current_lang = document.getElementById('current-lang-tromen').value

    // Search
    if (search) {
      search.addEventListener('input', e => {
          let value = normalizeText(search.value);
          clearTimeout(typingTimeout);
          typingTimeout = setTimeout(() => {
              if (value.length > 0) {
                  searchContainer.classList.add('active')
                  tabContainer.classList.add('d-none');
                  removeSearch(search);
                  searchResults.innerHTML = "";
                  let matches = 0;
                  items.forEach(item => {
                      let titleElement = item.querySelector('.title h2');
                      if (titleElement) {
                          let titleText = normalizeText(titleElement.textContent);
                          if (titleText.includes(value)) {
                      
                              item.classList.remove('d-none');
                            
                              matches++;
                          } else {
                              item.classList.add('d-none');
                        
                          }
                      }
                  });
                  faqContainer.classList.remove('d-none');
                  if (matches > 0) {
                      if(matches === 1){
                        searchResults.insertAdjacentHTML('afterbegin', `
                          <p>${current_lang === 'en' ? `${matches} questions found.` : `Se encontraron ${matches} pregunta.`}</p>
               
                        `);

                      }else{
                        searchResults.insertAdjacentHTML('afterbegin', `<p>${current_lang === 'en' ? `${matches} questions found.` : `Se encontraron ${matches} preguntas.`}</p>`);

                      }
                  } else {
                      searchResults.insertAdjacentHTML('afterbegin', `<p>${current_lang === 'en' ? `No questions found.` : `Se encontaron preguntas.`}</p>`);
                  }
              } else {
                  searchContainer.classList.remove('active')
                  searchResults.innerHTML = "";
                  tabContainer.classList.remove('d-none');

         
       
                  firstFilter(filters, items, true);
              }
          }, 800);
      });
  }

    // According
    if(items.length > 0){  
        items.forEach(item => {
            let button = item.querySelector('.title');
            button.addEventListener('click', () => {
                let content = button.nextElementSibling
                items.forEach((otherItem) => {
                    let otherButton = otherItem.querySelector('.title');
                    const otherContenido = otherButton.nextElementSibling
             
                    if (otherItem !== item) {
                      otherContenido.style.maxHeight = null;
                      otherButton.firstElementChild.nextElementSibling.firstElementChild.style.transform = 'rotate(0)'

                    }
                });
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                    button.firstElementChild.nextElementSibling.firstElementChild.style.transform = 'rotate(0)'

                  } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                    button.firstElementChild.nextElementSibling.firstElementChild.style.transform = 'rotate(180deg)'
                    // console.log(button.firstElementChild.nextElementSibling.firstElementChild)
                  }
            })
           
        });
    }

    // Filtros

    if(filters.length > 0){
      firstFilter(filters, items);
      filters.forEach(filter => {
          filter.addEventListener('click', () => {
            let category = filter.getAttribute('data-category');

            filters.forEach((filter)=>{filter.classList.remove('active')})



            filter.classList.add('active')
            items.forEach((faq) => {
              if(faq.classList.contains(`category-${category}`)){
                faq.classList.remove('d-none')
              }else{
                faq.classList.add('d-none')
              }
            })
          })
      });
    }

})

function normalizeText(text) {
  return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

function firstFilter(filters, items, restore = false){
  if(restore){
    filters.forEach((filter) => {filter.classList.remove('active')})
  }
  filters[0].classList.add('active');
  let cat = filters[0].getAttribute('data-category');
  items.forEach((faq) => {
    if(faq.classList.contains(`category-${cat}`)){
      faq.classList.remove('d-none')
      faq.classList.add('fade-in-bottom')
    }else{
      faq.classList.add('d-none')
    }
  })

}

const removeSearch = (input) => {
  let remove = document.querySelector('.search-remove')
  remove.addEventListener('click', e => {
    
    input.value = "";
    input.dispatchEvent(new Event('input'))
  })
}
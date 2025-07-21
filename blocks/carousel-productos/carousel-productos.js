const container = document.querySelector('.carousel-products');
const filtros = document.querySelectorAll('.filter');
const API = JSON.stringify(ajax_var.site).replace(/['"]+/g, "");
const THEME = JSON.stringify(ajax_var.theme).replace(/['"]+/g, "");
const productContainer = document.getElementById('productContainer');
const errorNotProducts = document.querySelector('.no-products');
const AJAX_URL = ajax_var.url
let splideCarrousel;
const slides = document.querySelectorAll('.splide-products');



window.addEventListener(('DOMContentLoaded'), async () => {
    try {
     
        if(filtros.length > 0){
            filtros.forEach((filtro) => {
                filtro.addEventListener('click', e => {
                    filtros.forEach((filtro) => {filtro.classList.remove('active')})
                    let filterTarget = filtro.getAttribute('data-slide-id');
                    filtro.classList.add('active')
                    if(slides.length > 0){
                        slides.forEach(slide => {
                            let slideTarget = slide.getAttribute('data-slide');
                            if(filterTarget === slideTarget){
                                slides.forEach(slide => {slide.classList.add('d-none')})
                                slide.classList.remove('d-none')
                                slide.classList.add('show')
                            }
                        });
                    }
                })
            })
  

        }

        if(slides.length > 0){
            slides.forEach(slide => {
                if(slide){
                    
                    new Splide( slide, {
                        perPage: 3,
                        arrows: true,
                        pagination: false,
              
                        breakpoints: {
                            878: {
                                perPage: 2,
                                pagination: true,
                                arrows: false,
                            },
                            720: {
                                perPage: 1,
                            }
                      }
                
                    }).mount();
                }
            });
        }

    } catch (error) {
        
    }
})



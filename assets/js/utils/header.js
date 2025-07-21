import { header, hamburgers, body } from './helper.js'
export const navbar = () => {
  const lang_container = document.querySelector('.lang-container')
  // Click Open Right Menu
  if (hamburgers.length > 0) {
    var forEach = function (t, o, r) {
      if ("[object Object]" === Object.prototype.toString.call(t))
        for (var c in t)
          Object.prototype.hasOwnProperty.call(t, c) && o.call(r, t[c], c, t);
      else for (var e = 0, l = t.length; l > e; e++) o.call(r, t[e], e, t);
    };
    forEach(hamburgers, function (hamburger) {
  
    // hamburgers header
    hamburgers[0].addEventListener('click', e => {
        header.classList.add("open-header-right");
        body.classList.add('hidden')
        window.addEventListener("click", (e) => {
            let target = e.target
            if (!header.contains(target)) {
              header.classList.remove('open-header-right');
              body.classList.remove('hidden')
            }
        });
    })
    });
  }
  if (!header.classList.contains("scrolling-default")) {
    window.addEventListener("scroll", (e) => {
      let y = window.scrollY;
      var viewportHeight = window.innerHeight / 4;
      if (y > viewportHeight) {
        if(!header.classList.contains('megamenu')){
          header.classList.add("scrolling", "slide-in-top");
        }
      } else {
        header.classList.remove("scrolling", "slide-in-top");
      }
    });
  } else {
    window.addEventListener("scroll", (e) => {
      let y = window.scrollY;
      if (y > 150) {
        header.classList.add("slide-in-top");
      } else {
        header.classList.remove("slide-in-top");
      }
    });
  }

  function insertarLangContainer() {
    const lang_container = document.querySelector('.lang-container');
    const footer = document.getElementById('menu-pie-de-pagina');
    const li = document.querySelectorAll('.menu-depth-0.dropdown');

    if (lang_container) {
        // Verificar el ancho de la ventana
        if (window.innerWidth <= 634.5) {
            // Insertar en el footer si el viewport es menor o igual a 633px
            footer.append(lang_container);
        } else {
            // Caso contrario, insertar en el último li
            if (li.length > 0) {
                const lastItem = li[li.length - 1]; // Obtener el último elemento
                lastItem.append(lang_container); // Agregar lang_container al último elemento
            }
        }
    }
  }
  insertarLangContainer();
  window.addEventListener('resize', insertarLangContainer);

}
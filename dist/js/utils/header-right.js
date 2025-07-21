import { header, menuDesktop, menuHeaderRight, dropRight, closeBtnRight, body } from './helper.js'
export const headerRight = () => {
    // // Crear un objeto MediaQueryList para la media query
    const mediaQueryList = window.matchMedia('(max-width: 1024.5px)');
    // // Verificar si la media query coincide inicialmente
    if (!mediaQueryList.matches) {
    
        if(menuDesktop){
            const dropdown = header.querySelectorAll(".dropdown-lvl-0");
            let click = 0
            dropdown.forEach((drop) => {
              let menu = drop.lastElementChild;
              drop.addEventListener("mouseover", function () {
                let menuDesk = document.querySelector('.menu-mobile')
                let menuStyle = window.getComputedStyle(menuDesk);
             
                if(menuStyle.display === 'none'){
                  header.classList.remove('open-header-right');
                }
                click++
                if(click <= 1){
                  menu.classList.add('first-child');
                }
                  dropdown.forEach((otherDrop) => {
                    let menuOther = otherDrop.lastElementChild;
                    menuOther.classList.remove('active-submenu')
                    otherDrop.classList.remove('active-color')
                    click = 0;
                  });
                  drop.classList.add('active-color')
                  menu.classList.add('active-submenu');
                  header.classList.add('megamenu');
              });
              // CLICK OUTSIDE DROP
              window.addEventListener("mouseover", (e) => {
          
                let target = e.target
                if (!header.contains(target)) {
                  menu.classList.remove('active-submenu', 'first-child')
                  drop.classList.remove('active-color')
                  let y = window.scrollY;
                  var viewportHeight = window.innerHeight / 4;
                  if (y > viewportHeight) {
                  } else {
                    setTimeout(() => {
                      header.classList.remove("scrolling", "slide-in-top", 'megamenu');
                    }, 200);
        
                  }
                }
              });
            })
       


          }
    }


    // // Close
    if(closeBtnRight){
        closeBtnRight.addEventListener('click', e => {
          header.classList.remove('open-header-right');
          body.classList.remove('hidden')
        })
    }
    
    const dropdownRight = menuHeaderRight.querySelectorAll('.menu-item.niveles-1');
    const allDrops =  Array.from(dropdownRight).concat(Array.from(dropRight));

    // // Mobile drop right
    allDrops.forEach(drop => {
        const i = drop.querySelector('i')
        i.addEventListener('click', e => {
            allDrops.forEach(otherDrop => {
                if (otherDrop !== drop) { // Verificar que no sea el mismo menú
                    const submenuOther = otherDrop.lastElementChild
                    submenuOther.classList.remove('active-submenu');
                }
            });
            const submenu = drop.lastElementChild
            submenu.classList.toggle('active-submenu')
        })
    });
   
};
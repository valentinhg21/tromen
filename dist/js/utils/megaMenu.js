import { menuDesktop } from './helper.js'
export const megaMenu = () => {
    const dropdown = menuDesktop.querySelectorAll('.niveles-1');
    dropdown.forEach(drop => {
        let childSet = new Set();
        let child = drop.querySelectorAll('ul');
        child.forEach(chil => {
            childSet.add(chil.className); // Agregar cada elemento ul al conjunto
        });
        let uniqueChildArray = Array.from(childSet); // Convertir el conjunto de nuevo en un array
        drop.classList.add(`has-lvl-${uniqueChildArray.length}`)
    });
}
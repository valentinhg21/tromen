
import { navbar } from './utils/header.js';
import { select } from './utils/select.js';
import { transition } from './utils/transition.js';
import { headerRight } from './utils/header-right.js';
import { searchHeader } from './utils/search.js';
import { miniCart } from './utils/mini-cart.js'
import { checkout } from './utils/checkout.js'
import { cupon } from './utils/cupon.js'

window.addEventListener('DOMContentLoaded', () => {
    try {
        transition();
        navbar();
        headerRight();
        select();
        searchHeader();
        miniCart();
        checkout();
        cupon();
       
    } catch (error) {
        console.error(error)
    }
});
// VARIABLES GENERALES
const headerMiniCartCount = document.querySelector('.mini-cart-count')
const miniCartButton = document.getElementById('open-cart-header');
const miniCart = document.querySelector('.mini-cart_custom');
const miniCartButtonClose = document.getElementById('close-mini-cart')
const miniCartAddToCart = document.querySelector('.single_add_to_cart_button')
const miniCartContainerItems = document.querySelector('.mini-cart-items');
const LOADING_BLOCK = document.querySelector('.blockUI.blockOverlay')
const LOADING_CUPON = document.querySelector('.blockUI-cupon.blockOverlay') 
const cuponContainer = document.querySelector('.cart-cupon')
const orderTotalNumber = document.querySelector('.order-total-number')
// MINI CART ACTIONS
const openMiniCart = () => {
    if(miniCart){
        miniCart.classList.add('show')
    }
   
  
    removeItemCart();
    quantityItemCart();
}
const closeMiniCart = () => {
    miniCart.classList.remove('show')
}

const updateTotalItemsMiniCart = (total) => {
    headerMiniCartCount.innerHTML = `<span>${total}</span>`;
    removeItemCart();
}

const removeItemCart = () => {

    const miniCartDeleteBtn = document.querySelectorAll('.btn-remove-item');
 
    if(miniCartDeleteBtn.length > 0){
        miniCartDeleteBtn.forEach(btn => {
            btn.addEventListener('click', e => {
                console.log('click')
                const dataProduct = {
                    product_id: btn.dataset.product
                };
        
                ajaxDeleteItemToCart(dataProduct)
                .then(() => {
                    // console.log('ELIMINADO EXITO')
                  })
                  .catch(() => {
                    // console.log('ELIMINADO NO EXITO')
                  });
            })
        });
    }
}

const quantityItemCart = () => {
    const minusQty = document.querySelectorAll('.minus-qty');
    const plusQty = document.querySelectorAll('.plus-qty');
  
 
    if (minusQty.length > 0) {
        minusQty.forEach((minus) => {
            const input = minus.nextElementSibling;
            minus.addEventListener("click", (e) => {
                // Evita el comportamiento predeterminado del botón
                e.preventDefault();
    
                // Obtén el input y su valor actual
         
                let inputValue = parseInt(input.value); // Valor actual del input
                let itemMin = parseInt(input.dataset.minStock); // Stock mínimo permitido
    
                // Decrementa la cantidad si es mayor al mínimo permitido
                if (inputValue > itemMin) {
                    inputValue--; // Decrementar en 1
                    input.value = inputValue; // Actualizar el input con la nueva cantidad
    
                    // Preparar los datos para enviar al backend
                    const dataProduct = {
                        product_id: input.dataset.product, // ID del producto
                        quantity: input.value, // Restar siempre de 1 en 1
                    };
    
                    // Llamar a la función AJAX para actualizar el carrito
                    ajaxUpdateCartQuantity(dataProduct)
                        .then(() => {
                            // console.log("Producto actualizado con éxito");
                        })
                        .catch(() => {
                            // console.log("Error al actualizar el producto");
                        });
                } else {
                    // console.log("No se puede disminuir: Se alcanzó la cantidad mínima");
                }


            });
            input.addEventListener('input', e => {
                const dataProduct = {
                    product_id: input.dataset.product, // ID del producto
                    quantity: input.value, // Restar siempre de 1 en 1
                };
                ajaxUpdateCartQuantity(dataProduct)
                .then(() => {
                    // console.log("Producto actualizado con éxito");
                })
                .catch(() => {
                    // console.log("Error al actualizar el producto");
                });
            })
        });
    }
    if (plusQty.length > 0) {
        plusQty.forEach(plus => {
            // Asegúrate de que no se registre más de una vez
            plus.addEventListener('click', (e) => {
                // Evita el comportamiento predeterminado del botón
                e.preventDefault();
                // console.log('Evento click registrado en:', plus);
            
                // Obtén el input y su valor actual
                const input = plus.previousElementSibling;
                let inputValue = parseInt(input.value); // Valor actual del input
                let itemMax = parseInt(input.dataset.maxStock); // Stock máximo permitido
            
                // Incrementa la cantidad
                if (itemMax === -1 || inputValue < itemMax) {
                    inputValue++; // Incrementar en 1
                    input.value = inputValue; // Actualizar el input con la nueva cantidad
            
                    // Preparar los datos para enviar al backend
                    const dataProduct = {
                        product_id: input.dataset.product, // ID del producto
                        quantity: 1, // Incrementar siempre de 1 en 1
                    };
            

            
                    // Llamar a la función AJAX para actualizar el carrito
                    ajaxAddToCart(dataProduct)
                        .then(() => {
                            // console.log('Producto actualizado con éxito');
                        })
                        .catch(() => {
                            // console.log('Error al actualizar el producto');
                        });
                } else {
                    // console.log('No se puede incrementar: Se alcanzó el stock máximo');
                }
            });
    
        });
    }
}

// Fetch 
const ajaxAddToCart = async (dataProduct) => {
    LOADING_BLOCK.classList.add('active')
    try {
        const params = {
            action: 'ajax_mini_cart_product',
            ...dataProduct
        };

        const url = `${wc_add_to_cart_params.ajax_url}?${new URLSearchParams(params).toString()}`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            }
        });

        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status}`);
        }

        const responseText = await response.text();

        // Intenta analizar el texto como JSON
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (error) {
            console.error('Respuesta no válida como JSON:', responseText);
            throw new Error('La respuesta del servidor no es un JSON válido.');
        }
        LOADING_BLOCK.classList.add('active')
        if (data.success) {
            miniCartContainerItems.innerHTML = data.data.mini_cart;
            updateTotalItemsMiniCart(data.data.cart_total_items);
            openMiniCart();
            setTimeout(() => {
                LOADING_BLOCK.classList.remove('active')
            }, 150);
          
            return true;
        } else {
            console.error('Error en la actualización del carrito:', data);
            LOADING_BLOCK.classList.remove('active')
        }
    } catch (error) {
        console.error('Error en la solicitud AJAX', error);
    }
};

const ajaxUpdateCartQuantity = async (dataProduct) => {
    LOADING_BLOCK.classList.add('active')
    try {
      
        // Combina los datos adicionales con los datos básicos
        const params = {
            action: 'ajax_update_cart_quantity',
            ...dataProduct // Agrega todos los datos del objeto `dataProduct`
        };

        // Construye la URL con los parámetros
        const url = `${wc_add_to_cart_params.ajax_url}?${new URLSearchParams(params).toString()}`;

        // Realiza la solicitud GET sin usar el cuerpo
        const response = await fetch(url, {
            method: 'GET', // Método GET
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            }
        });

        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status}`);
        }
        
        const data = await response.json();
   
        
        if (data.success) {
    
       
            miniCartContainerItems.innerHTML = data.data.mini_cart;
            updateTotalItemsMiniCart(data.data.cart_total_items);
            openMiniCart();
            setTimeout(() => {
                LOADING_BLOCK.classList.remove('active');
                
            }, 150);
            return true;
       
        } else {
            console.error('Error en la actualización del carrito:', data);
           
        }
    } catch (error) {
        console.error('Error en la solicitud AJAX', error);
    }
};

const ajaxDeleteItemToCart = async (dataProduct) => {
    LOADING_BLOCK.classList.add('active')
    try {
        // Combina los datos adicionales con los datos básicos
        const params = {
            action: 'ajax_remove_cart_product',
            ...dataProduct // Agrega todos los datos del objeto `dataProduct`
        };

        // Construye la URL con los parámetros
        const url = `${wc_add_to_cart_params.ajax_url}`;

        // Realiza la solicitud GET sin usar el cuerpo
        const response = await fetch(url, {
            method: 'POST', // Método GET
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams(params).toString() // Enviar los parámetros en el cuerpo

        });

        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            setTimeout(() => {
                miniCartContainerItems.innerHTML = data.data.mini_cart;
                updateTotalItemsMiniCart(data.data.cart_total_items);
                LOADING_BLOCK.classList.remove('active')
            }, 150);


            return true;
        } else {
            console.error('Error en la actualización del carrito:', data);
            LOADING_BLOCK.classList.remove('active')
        }
    } catch (error) {
        console.error('Error en la solicitud AJAX', error);
        LOADING_BLOCK.classList.remove('active')
    }
};


const ajaxVerifyCupon = async (cupon) => {
    LOADING_CUPON.classList.add('active')
    try {
        // Combina los datos adicionales con los datos básicos
        const params = {
            action: 'validar_cupon_ajax',
            ...cupon // Agrega todos los datos del objeto `dataProduct`
        };

        // console.log('params' + cupon)
        // Construye la URL con los parámetros
        const url = `${wc_add_to_cart_params.ajax_url}`;

        // Realiza la solicitud GET sin usar el cuerpo
        const response = await fetch(url, {
            method: 'POST', // Método GET
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams(params).toString() // Enviar los parámetros en el cuerpo

        });

        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status}`);
        }
        let notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top',
              },
        });
        const data = await response.json();

        if (data.success) {
            setTimeout(() => {
                // miniCartContainerItems.innerHTML = data.data.mini_cart;
                // updateTotalItemsMiniCart(data.data.cart_total_items);
                // cuponContainer.innerHTML = data.data.coupons_html
                // orderTotalNumber.innerHTML = data.data.cart_total
                LOADING_CUPON.classList.remove('active')
                notyf.success(data.data.message);
                document.body.dispatchEvent(new Event('update_checkout'));
            }, 150);


            return true;
        } else {
            
            notyf.error(data.data.message);
            console.error('Error en la actualización del carrito:', data);
            LOADING_CUPON.classList.remove('active')
        }
    } catch (error) {
        console.error('Error en la solicitud AJAX', error);
        LOADING_CUPON.classList.remove('active')
    }
};

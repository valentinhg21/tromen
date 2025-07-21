export const miniCart = () => {
    if(miniCartButton){
        miniCartButton.addEventListener('click', e => {
            openMiniCart();
        })
    }
    if(miniCartButtonClose){
        miniCartButtonClose.addEventListener('click', () => {
            closeMiniCart();
        })
    }

    if(miniCartAddToCart){
        miniCartAddToCart.addEventListener('click', () => {
           
            miniCartAddToCart.classList.add('adding');
            miniCartAddToCart.disabled = true;
            miniCartAddToCart.innerHTML = '<span class="loader-btn"></span>';
            const dataProduct = {
                product_id: miniCartAddToCart.dataset.product,
                quantity: miniCartAddToCart.dataset.minValue || 1,
            };
            ajaxAddToCart(dataProduct)
            .then(() => {
                setTimeout(() => {
                    miniCartAddToCart.classList.remove('adding');
                    miniCartAddToCart.disabled = false;
                    miniCartAddToCart.innerHTML = miniCartAddToCart.dataset.text;
                }, 250);

            })
            .catch(() => {
                miniCartAddToCart.innerHTML = 'Producto no agregado';
                setTimeout(() => {
                    miniCartAddToCart.classList.remove('adding');
                    miniCartAddToCart.disabled = false;
                    miniCartAddToCart.innerHTML = miniCartAddToCart.dataset.text;
                }, 250);
            });
            removeItemCart();
        })
    }

    window.addEventListener('click', e => {
        let target = e.target
        if(target.classList.contains('mini-cart')){
            target.classList.remove('show')
        }
    })

}
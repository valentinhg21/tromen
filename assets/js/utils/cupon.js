export const cupon = () => {
   
    const validar = document.getElementById('validar-cupon-btn');

    if(validar){
        const couponCode = document.getElementById('coupon-code-input');
        validar.addEventListener('click', e => {
            const cupon = couponCode.value
            if(cupon.length > 0){
                
                ajaxVerifyCupon({coupon_code: cupon})
                .then(() => {
                    

    
                })
                .catch(() => {
                 

                });

              
            }

        })
    }
}
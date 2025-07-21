export const checkout = () => {
  const payments = document.querySelectorAll(".wc_payment_method");

  payments.forEach((payment) => {
    let label = payment.firstElementChild.nextElementSibling.nextElementSibling

    label.addEventListener("click", (e) => {
      // Verifica si el clic ocurrió dentro del div.content
      const contentDiv = document.querySelector('.content');
      if (contentDiv && contentDiv.contains(e.target)) {
        return; // Si está dentro de content, no hacer nada
      }

      const isActive = payment.classList.contains("active");

      // Reinicia todos los métodos de pago
      payments.forEach((otherPayment) => {
        const box = otherPayment.querySelector(".payment_box");
        const input = otherPayment.querySelector(".input-payment");
        otherPayment.classList.remove("active");
        if (input) input.checked = false;
        if (box) box.style.maxHeight = `0px`;
      });

      // Si el elemento clicado ya estaba activo, lo colapsa
      if (isActive) {
        payment.classList.remove("active");
        const input = payment.querySelector(".input-payment");
        const box = payment.querySelector(".payment_box");
        if (input) input.checked = false;
        if (box) box.style.maxHeight = `0px`;
      } else {
        // Si no estaba activo, lo expande
        payment.classList.add("active");
        const input = payment.querySelector(".input-payment");
        const box = payment.querySelector(".payment_box");
        if (input) input.checked = true;
        if (box) {
          const heightBox = box.firstElementChild.getBoundingClientRect().height;
          box.style.maxHeight = `${heightBox}px`;
        }
      }
    });
  });

  // Validation Select Factura
  let factura = document.getElementById('billing_tipo_de_factura_field');
  if(factura){
    let select = document.getElementById('billing_tipo_de_factura');
    select.addEventListener('change', e => {
      let razon = document.getElementById('billing_razon_social_field')
      if( select.value === 'Responsable Inscripto'){
        razon.classList.add('active')
      }else{
        razon.classList.remove('active')
      }
    })
    
  }
};
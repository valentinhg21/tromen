<?php 
    $cart_total = WC()->cart->get_cart_total();
    $total_items = WC()->cart->get_cart_contents_count();
?>

<sidebar class="mini-cart_custom p-relative">
  <div class="content p-relative">
    <div class="blockUI blockOverlay"><span class="loader"></span></div>
     <div class="mini-cart-header">
        <h2>Carrito</h2>
        <button type="button" class="close-mini-cart" id="close-mini-cart">
            <i class="fa-solid fa-xmark"></i>
        </button>
     </div>
     <div class="mini-cart-items">
        <?php woocommerce_mini_cart(); ?>
     </div>
     <div class="mini-cart-total">
        <div class="subtotal d-none">
            <span clasS="subtotal-text">Subtotal</span>
            <span class="subtotal-price">
                <?php echo $cart_total; ?>
            </span>
         </div>
     </div>

 

  </div>
</sidebar>
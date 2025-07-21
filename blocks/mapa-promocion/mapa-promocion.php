<?php 
    $titulo = get_field( 'titulo' );
    $ancho = get_field( 'ancho_completo' ) ? 'container-fluid full-width' : 'container no-full-width';
    $texto = get_field( 'texto' );
?>
<div class="block-map block-oferta-mapa" id="standalone-map" <?php animation('fade-in', 500);?>>
    <div class="<?php echo $ancho;?>">
        <?php if($titulo || $texto): ?>
            <div class="block-map-title" id="puntos-de-venta">
                <?php insert_acf($titulo, 'h2'); ?>
                <?php echo $texto; ?>
            </div>
        <?php endif; ?>
        <?php get_template_part('template-parts/content', 'map'); ?>
    </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Verifica si la URL contiene el hash '#puntos-de-venta'
   
    if (window.location.href.includes('#puntos-de-venta')) {
        
      // Espera un momento para asegurar que el contenido está cargado
      setTimeout(function() {
        var element = document.getElementById('puntos-de-venta');
        var headerOffset = document.getElementById('main-header').offsetHeight;
        var elementPosition = element.getBoundingClientRect().top - 60;
        var offsetPosition = elementPosition + window.pageYOffset - headerOffset;
    
        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
        });
      }, 500); // Retardo de 100 ms para asegurarse de que el elemento esté disponible
    }
  });
</script>
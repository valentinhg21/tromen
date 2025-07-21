<?php 
    $titulo = get_field( 'titulo' );
    $ancho = get_field( 'ancho_completo' ) ? 'container-fluid full-width' : 'container no-full-width';
    $texto = get_field( 'texto' );
?>
<div class="block-map" id="standalone-map" <?php animation('fade-in', 500);?>>
    <div class="<?php echo $ancho;?>">
        <?php if($titulo || $texto): ?>
            <div class="block-map-title">
                <?php insert_acf($titulo, 'h2'); ?>
                <?php echo $texto; ?>
            </div>
        <?php endif; ?>
        <?php get_template_part('template-parts/content', 'map'); ?>
    </div>
</div>
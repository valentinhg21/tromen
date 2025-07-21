

    <?php 
            $titulo = get_field( 'titulo' );
            $texto = get_field( 'texto' );
            $imagen = get_field( 'imagen' ) ?: '';
            $imagen_mobile = get_field( 'imagen_mobile') ?: $imagen; // Si no hay imagen mobile, usa la de desktop        
            $class_fade = (!empty($titulo)  || !empty($texto)) ? 'fade' : '';

            if(isset($block['className'])){
                $classStyle = $block['className'];
              }else{
                  $classStyle = 'is-style-default';
              }
    ?>
    <div 
    class="block-hero <?php echo $class_fade;?> <?php echo $classStyle ?>" 
    data-image-desktop="<?php echo esc_url( $imagen['url'] ?? '' ); ?>" 
    data-image-mobile="<?php echo esc_url( $imagen_mobile['url'] ?? '' ); ?>" 
    style="--background-image-desktop: url(<?php echo esc_url( $imagen['url'] ?? '' ); ?>); --background-image-mobile: url(<?php echo esc_url( $imagen_mobile['url'] ?? '' ); ?>);">
        <div class="container">
            <div class="content fade-in-bottom">
                <?php insert_acf($titulo, 'h1');?>
                <?php echo $texto;?>
            </div>
        </div>
    </div>




    <?php 
        $bg = get_field( 'seleccionar' );
        $titulo = get_field( 'titulo' );
        $imagen = get_field( 'imagen' );
        $video = get_field( 'video' );

        $imagen_enable = $bg === true ? 'style="background-image:url('.  esc_url( $imagen['url'] ). ')"' : '';
    ?>



    <div class="hero hero-custom fade" <?php echo $imagen_enable; ?>>
        <?php if($bg !== true): ?>
            <div class="video-container">
                <video width="100%" height="100%" autoplay muted loop playsinline>
                    <source src="<?php echo esc_url( $video['url'] ); ?>" type="video/mp4">
                    Tu navegador no soporta el elemento de video.
                </video>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="content" data-transition="fade-in-bottom" data-delay="400" style="opacity: 0;">
                    <?php insert_acf($titulo, 'h1'); ?>
            </div>
        </div>

    </div>


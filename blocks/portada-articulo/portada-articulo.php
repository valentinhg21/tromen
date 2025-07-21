<?php 

$titulo = get_field( 'titulo' );
$texto = get_field( 'texto' );
$imagen = get_field( 'imagen_articulo' );
?>
<div class="block-portada-articulo blog">
    <div class="container">
        <div class="row row-card">
            <div class="col-md-6 col-12 col-content">
                <div class="content">
                    <?php if(is_singular('blog')): ?>
                        <a href="<?php echo home_url()?>/blog/">BLOG</a>
                    <?php endif; ?>
                    <?php insert_acf($titulo, 'h1'); ?>
                    <?php insert_acf($texto, 'h2'); ?>
                  
                </div>
            </div>
            <div class="col-md-6 col-12 col-image">
                <div class="image">
                    <?php insert_image($imagen, 1024); ?>
                </div>
            </div>
        </div>
    </div>
</div>
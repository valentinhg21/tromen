
<?php 
    $titulo = get_field( 'titulo' );



?>

<div class="block-list-products archive-subcategory">
    <div class="container">
        <?php if($titulo): ?>
            <div class="title fade-in-bottom">
                <?php insert_acf($titulo, 'h2'); ?>
            </div>
        <?php endif; ?>
        <div class="list-products carousel-products" id="listProducts">
            <?php
            $posts = get_field( 'seleccion_de_productos' );
            if ( $posts ) : ?>
            <?php foreach( $posts as $post) : ?>
            <?php setup_postdata( $post ); ?>
                    <?php 
                        $product = wc_get_product($post->ID); 
                        $details = product_details($product);
                        $category = $details['category_name'];
                        $tags = $details['tags_html'];
                    ?>
                        <li class="splide__slide d-none">
                            <a href="<?php echo $details['product_permalink']?>" class="product-card fade-in-bottom" aria-label="Ver detalles de <?php echo $details['product_name']?>">
                                <div class="product-image">
                                    <?php echo $details['product_image']?>
                                    <?php echo $tags; ?>
                                </div>
                                <div class="product-body p-relative">
                                    <p><?php echo $details['product_name']?></p>
                                    <p class="product-price-slide"><?php echo $details['product_price']?></p>
                                    <?php echo price_fee_html($product); ?>
                            
                                    <p class="text-red"><?php echo $category; ?></p>
                                    <button class="permalink">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </a>
                        </li>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
            <?php else: ?>
                <p class="no-products active">No se encontraron productos.</p>
            <?php endif; ?>
        </div>
        <div id="pagination" class="fade-in-bottom"></div>
    </div>
</div>
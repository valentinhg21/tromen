<?php 
    $titulo = get_field( 'titulo' );
    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'order' => 'ASC'
    ) ); 
    $main_categories = array();
    foreach ( $categories as $category ) {
        if ( $category->parent == 0 ) {
            $main_categories[] = $category;
        }
    }
    $filtros_cat = get_field( 'filtros_por_categorias' );
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => array( 'publish' )
    );
    $products = new WP_Query($args);

    $related_products = get_field('seleccion_de_productos');
    $active_count = 0;
?>

<div class="carousel-products">
    <div class="container container-sm-8">
        <div class="splide-title" <?php animation('fade-in-bottom', 500);?>>
            <h2><?php echo esc_html($titulo);?></h2>
        </div>
        <?php if ($related_products): ?>
            <div class="filters" <?php animation('fade-in-bottom', 500); ?>>
                <ul class="controls">
                        <?php foreach ($related_products as $product): ?>
                            <?php setup_postdata( $product ); ?>
                            <?php
                                $total = $active_count++;
                                $active = ($total === 0) ? 'active' : '';
                                $slug = strtolower(str_replace(' ', '-', $product['titulo']));
                            ?>
                            <li>
                                <button 
                                    type="button" 
                                    class="filter <?php echo esc_attr($active); ?>" 
                                    data-slide-id="<?php echo esc_attr($slug); ?>">
                                    <?php echo esc_html($product['titulo']); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                        <?php $active_count = 0; ?>
                        <?php wp_reset_postdata(); ?>
                </ul>
            </div>

            <?php foreach ($related_products as $slide): ?>
                <?php $slug = strtolower(str_replace(' ', '-', $slide['titulo'])); ?>
                <?php 
                    $total = $active_count++;
                    $active = ($total === 0) ? 'show' : 'd-none';
                ?>
                
                <div class="splide splide-products <?php echo esc_attr($active); ?>" data-slide="<?php echo esc_attr($slug); ?>" role="group" <?php animation('fade-in-bottom', 500); ?>>
                    <div class="splide__track">
                        <ul class="splide__list list-filter" id="productContainer">
                            <?php foreach ($slide['productos'] as $data): ?>
                                <?php setup_postdata( $data ); ?>
                                <?php 
                                    global $product;
                                    $product_data = product_details($product);
                                    $product_name = $product_data['product_name'];
                                    $product_permalink = $product_data['product_permalink'];
                                    $first_gallery_image = $product_data['first_gallery_image'];
                                    $product_image = $product_data['product_image'];
                                    $category = $product_data['category_name'];
                                    $price = $product_data['product_price'];
                                    $discount_per = $product_data['discount'];
                                    $tags = $product_data['tags_html'];
                                ?>
                                <li class="splide__slide">
                                    <a href="<?php echo $product_permalink; ?>" class="product-card product-carrousel-card" aria-label="Ver detalles de <?php echo esc_attr($product_name); ?>">
                                        <div class="product-image">
                                            <?php if ($product_image): ?>
                                                <?php echo $product_image; ?>
                                            <?php else: ?>
                                                <img fetchpriority="high" decoding="async" src="<?php echo esc_url(IMAGE . '/productos/default.png'); ?>" class="attachment-large size-large" width="100%" height="100%">
                                            <?php endif; ?>
                                            <?php echo $discount_per; ?>
                                            <?php echo $tags; ?>
                                        </div>
                                        <div class="product-body p-relative">
                                            <p><?php echo esc_html($product_name); ?></p>
                                            <p class="product-price-slide"><?php echo wp_kses_post($price); ?></p>
                                            <?php echo price_fee_html($product); ?>
                                            <p class="text-red"><?php echo esc_html($category); ?></p>
                                            <button class="permalink">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </a>
                                </li>
                         
                            <?php endforeach; ?>
                            <?php wp_reset_postdata(); ?>
                        </ul>
                    </div>
                    <div class="splide__arrows splide__arrows--ltr">
                        <button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous slide" aria-controls="splide01-track">
                            <svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.708865 10.4919C0.708865 9.88542 0.945373 9.31386 1.37443 8.88329L9.38089 0.876831L10.4528 1.9487L2.4463 9.95516C2.15976 10.2417 2.15976 10.7405 2.4463 11.027L10.4528 19.0335L9.38088 20.1054L1.37443 12.0989C0.945373 11.6699 0.708865 11.0983 0.708865 10.4903L0.708865 10.4919Z" fill="white"/>
                            </svg>
                        </button>
                        <button class="splide__arrow splide__arrow--next" type="button" aria-label="Next slide" aria-controls="splide01-track">
                            <svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.2913 10.4919C10.2913 9.88542 10.0547 9.31386 9.6257 8.88329L1.61924 0.876831L0.547363 1.9487L8.55382 9.95516C8.84036 10.2417 8.84036 10.7405 8.55382 11.027L0.547364 19.0335L1.61924 20.1054L9.6257 12.0989C10.0547 11.6699 10.2913 11.0983 10.2913 10.4903L10.2913 10.4919Z" fill="white"/>
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>
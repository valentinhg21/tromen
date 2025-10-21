<?php get_header(); ?>
<?php 

    $category = get_queried_object(); 
    $category_id = $category->term_id;
    // Obtener el objeto de la categoría padre
    $parent_category = get_term($category->parent, 'product_cat');
    $name = "";
    if(!is_wp_error($parent_category)){
        $name = $parent_category->name;
    }
    $paged = $_GET['page'] ?? 1;
    $args = [
        'post_type' => 'product',
        'posts_per_page' => 9, // Define la cantidad de productos por página
        'meta_query' => [],
        'paged'          => $paged, // Agrega la paginación
        'tax_query' => [
            [
                'taxonomy' => 'product_cat',
                'field' => 'term_id', // Cambiado a 'term_id' para asegurar que se use el ID de la categoría
                'terms' => $category_id,
            ],
        ],
        'orderby' => 'menu_order', // Ordenar por el orden del menú
        'order' => 'ASC', // De forma ascendente
    ];

    $products_query = new WP_Query($args);
    $title_category = get_field( 'titulo', $category);

    $query_MaxPrice = $_GET['max_price'] ?? getMaxAndMinPrice()['max_price'];
    $query_MinPrice = $_GET['min_price'] ?? getMaxAndMinPrice()['min_price'];
    $title_category = get_field( 'titulo_de_pagina', $category);
?>
<input type="hidden" id="currentPage" value=<?php echo wp_kses_post( $paged )?>>
<div class="archive-subcategory" id="<?php echo $category_id;?>" data-slug="<?php echo $category->slug?>">
    <div class="container">
        <?php woocommerce_breadcrumb('>'); ?>
        <div class="p-relative">
            <div class="content">
                <?php if($title_category): ?>
                    <?php echo insert_acf($title_category, 'h1') ?>
                    <?php else: ?>
                        <?php if($title_category): ?>
                            <?php insert_acf($title_category, 'h1'); ?>
                        <?php else: ?>
                            <h1><?php echo $name; ?> <span><?php single_term_title();?></span></h1>
                        <?php endif; ?>
                <?php endif; ?>
             
                <button type="button" class="filter-order">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_532_1581)">
                            <path d="M0.666667 3.16684H2.49067C2.63376 3.69333 2.94612 4.1581 3.37955 4.48946C3.81299 4.82082 4.34341 5.00035 4.889 5.00035C5.43459 5.00035 5.96501 4.82082 6.39845 4.48946C6.83188 4.1581 7.14424 3.69333 7.28733 3.16684H15.3333C15.5101 3.16684 15.6797 3.0966 15.8047 2.97158C15.9298 2.84655 16 2.67698 16 2.50017C16 2.32336 15.9298 2.15379 15.8047 2.02877C15.6797 1.90374 15.5101 1.83351 15.3333 1.83351H7.28733C7.14424 1.30702 6.83188 0.842243 6.39845 0.510885C5.96501 0.179527 5.43459 0 4.889 0C4.34341 0 3.81299 0.179527 3.37955 0.510885C2.94612 0.842243 2.63376 1.30702 2.49067 1.83351H0.666667C0.489856 1.83351 0.320286 1.90374 0.195262 2.02877C0.0702379 2.15379 0 2.32336 0 2.50017C0 2.67698 0.0702379 2.84655 0.195262 2.97158C0.320286 3.0966 0.489856 3.16684 0.666667 3.16684ZM4.88867 1.33351C5.11941 1.33351 5.34497 1.40193 5.53683 1.53012C5.72869 1.65832 5.87822 1.84053 5.96653 2.05371C6.05483 2.26689 6.07793 2.50147 6.03292 2.72778C5.9879 2.95409 5.87679 3.16197 5.71362 3.32513C5.55046 3.48829 5.34258 3.59941 5.11627 3.64442C4.88996 3.68944 4.65538 3.66633 4.4422 3.57803C4.22902 3.48973 4.04681 3.3402 3.91862 3.14834C3.79042 2.95648 3.722 2.73092 3.722 2.50017C3.72235 2.19086 3.84538 1.89432 4.0641 1.6756C4.28281 1.45689 4.57936 1.33386 4.88867 1.33351Z" fill="currentColor"/>
                            <path d="M15.3333 7.33269H13.5093C13.3665 6.80608 13.0542 6.34114 12.6208 6.00964C12.1874 5.67815 11.657 5.49854 11.1113 5.49854C10.5657 5.49854 10.0352 5.67815 9.60182 6.00964C9.16842 6.34114 8.85619 6.80608 8.71333 7.33269H0.666667C0.489856 7.33269 0.320286 7.40293 0.195262 7.52795C0.0702379 7.65297 0 7.82254 0 7.99935C0 8.17616 0.0702379 8.34573 0.195262 8.47076C0.320286 8.59578 0.489856 8.66602 0.666667 8.66602H8.71333C8.85619 9.19263 9.16842 9.65757 9.60182 9.98906C10.0352 10.3206 10.5657 10.5002 11.1113 10.5002C11.657 10.5002 12.1874 10.3206 12.6208 9.98906C13.0542 9.65757 13.3665 9.19263 13.5093 8.66602H15.3333C15.5101 8.66602 15.6797 8.59578 15.8047 8.47076C15.9298 8.34573 16 8.17616 16 7.99935C16 7.82254 15.9298 7.65297 15.8047 7.52795C15.6797 7.40293 15.5101 7.33269 15.3333 7.33269ZM11.1113 9.16602C10.8806 9.16602 10.655 9.09759 10.4632 8.9694C10.2713 8.8412 10.1218 8.659 10.0335 8.44582C9.94517 8.23264 9.92207 7.99806 9.96708 7.77175C10.0121 7.54544 10.1232 7.33756 10.2864 7.1744C10.4495 7.01124 10.6574 6.90012 10.8837 6.85511C11.11 6.81009 11.3446 6.83319 11.5578 6.9215C11.771 7.0098 11.9532 7.15933 12.0814 7.35119C12.2096 7.54305 12.278 7.76861 12.278 7.99935C12.2776 8.30866 12.1546 8.6052 11.9359 8.82392C11.7172 9.04264 11.4206 9.16566 11.1113 9.16602Z" fill="currentColor"/>
                            <path d="M15.3333 12.8335H7.28733C7.14424 12.307 6.83188 11.8422 6.39845 11.5109C5.96501 11.1795 5.43459 11 4.889 11C4.34341 11 3.81299 11.1795 3.37955 11.5109C2.94612 11.8422 2.63376 12.307 2.49067 12.8335H0.666667C0.489856 12.8335 0.320286 12.9037 0.195262 13.0288C0.0702379 13.1538 0 13.3234 0 13.5002C0 13.677 0.0702379 13.8465 0.195262 13.9716C0.320286 14.0966 0.489856 14.1668 0.666667 14.1668H2.49067C2.63376 14.6933 2.94612 15.1581 3.37955 15.4894C3.81299 15.8208 4.34341 16.0003 4.889 16.0003C5.43459 16.0003 5.96501 15.8208 6.39845 15.4894C6.83188 15.1581 7.14424 14.6933 7.28733 14.1668H15.3333C15.5101 14.1668 15.6797 14.0966 15.8047 13.9716C15.9298 13.8465 16 13.677 16 13.5002C16 13.3234 15.9298 13.1538 15.8047 13.0288C15.6797 12.9037 15.5101 12.8335 15.3333 12.8335ZM4.88867 14.6668C4.65792 14.6668 4.43236 14.5984 4.2405 14.4702C4.04864 14.342 3.89911 14.1598 3.81081 13.9466C3.72251 13.7334 3.6994 13.4989 3.74442 13.2726C3.78943 13.0462 3.90055 12.8384 4.06371 12.6752C4.22687 12.512 4.43475 12.4009 4.66106 12.3559C4.88737 12.3109 5.12195 12.334 5.33513 12.4223C5.54831 12.5106 5.73052 12.6601 5.85871 12.852C5.98691 13.0439 6.05533 13.2694 6.05533 13.5002C6.0548 13.8094 5.93172 14.1059 5.71304 14.3245C5.49436 14.5432 5.19792 14.6663 4.88867 14.6668Z" fill="currentColor"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_532_1581">
                                <rect width="16" height="16" fill="currentColor"/>
                            </clipPath>
                        </defs>
                    </svg>
                    <span class="d-flex-sm d-none">Filtrar y ordenar</span>
                </button>
            </div>
            <div class="description">
                <?php echo term_description(); ?>
            </div>
            <div class="filtros">
                <?php getAllCategoriesById($category_id, true ); ?>
            </div>
        </div>
        <?php $count = 0; ?>
        <div class="list-products carousel-products" id="listProducts">
            <?php if ($products_query->have_posts()): ?>
                <?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
                    <?php 
                        $product = wc_get_product(get_the_ID()); 
                        $details = product_details($product);
                        $category = $details['category_name'];
                        $discount_per = $details['discount'];
                        $tags = $details['tags_html'];
                    ?>

                        <li class="splide__slide">
                            <a href="<?php echo $details['product_permalink']?>" class="product-card fade-in-bottom" aria-label="Ver detalles de <?php echo $details['product_name']?>">
                                <div class="product-image">
                                    <?php echo $details['product_image']?>
                                        <?php echo $discount_per; ?>
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
                                <?php echo $tags; ?>
                            </a>
                        </li>
                <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-products show active">No hay ningún producto.</p>
            <?php endif; ?>

        </div>
        <div id="pagination"></div>

        <div class="return d-flex align-items-center justify-center">
            <?php if(!isCatFather($category_id) && !is_wp_error($parent_category) ): ?>
                <a href="<?php echo get_term_link($parent_category->term_id)?>" class="btn btn-icon btn-red-transparent-icon">
                        <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 13L1 7L7 1" stroke="currentColor"/>
                        </svg>
                        Volver a <?php echo $parent_category->name;?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="modal-backdrop-filter">
        <div class="body">
                <div class="body-title">
                    <h3>Filtrar y ordenar</h3>
                    <button type="button" id="clear-filter">Borrar todo</button>
                    <button type="button" id="closeModal"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="filter filter-apply">
                    <button type="button" class="filter-title">
                        Filtros aplicados

                    </button>
                    <ul id="apply"></ul>
                </div>
                <div class="content-filter">   
                    <div class="dropdown-filter">
                        <button type="button" class="dropdown-title">
                            Ordenar por
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <ul>
                            <li>
                                <button type="button" class="filter-btn-panel filter-order-btn order"  data-group="order" data-name="precio" data-order="asc">Precio (de menor a mayor)</button>
                            </li>
                            <li>
                                <button type="button" class="filter-btn-panel filter-order-btn order"  data-group="order" data-name="destacados" data-order="true">Destacados</button>
                            </li>
                            <li>
                                <button type="button" class="filter-btn-panel filter-order-btn order"  data-group="order" data-name="precio"  data-order="desc" >Precio (de mayor a menor)</button>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown-filter">
                        <button type="button" class="dropdown-title">
                            Tipo de producto
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <?php displayAllProductCategories();?>

                    </div>
                    <div class="dropdown-filter">
                        <button type="button" class="dropdown-title">
                            Precios
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                      
                        <ul>
                            <li>
                                <div class="price-filter-container">
                                    <?php var_dump(getMaxAndMinPrice()); ?>
                                    <input type="text" id="price-range" name="price-range" />
                                    <output class="price-filter-output" id="price-output" data-min='<?php echo $query_MinPrice?>' data-max='<?php echo $query_MaxPrice?>' data-group="range" data-order="range-price" data-value="$<?php echo number_format($query_MinPrice, 2)?> - $<?php echo number_format($query_MaxPrice, 2)?>">
                                        <?php if (isset($query_MinPrice) && $query_MaxPrice): ?>
                                            Precio: de $<?php echo number_format($query_MinPrice, 2)?> - $<?php echo number_format($query_MaxPrice, 2)?>

                                            <?php else: ?>
                                                Precio: de $0 - $<?php echo number_format($query_MaxPrice, 0)?>

                                        <?php endif; ?>
                                    </output>

                         
                                </div>

                            </li>
                        </ul>
               
                    </div>
                </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
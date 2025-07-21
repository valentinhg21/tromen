<?php 

    $args = [
        'post_type' => 'faq',
        'order' => 'asc',
        'posts_per_page' => -1
    ];
    $query = new WP_Query($args);
    $categories_with_faqs = array();
    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            $id = get_the_ID();
            $product_categories = wp_get_post_terms($id, 'product_cat');
            foreach ($product_categories as $product_category) {
                $categories_with_faqs[$product_category->slug] = $product_category->name;
            }
        endwhile;
        wp_reset_postdata();
    endif;
?>

<div class="block-faq">
    <div class="container-sm">
        <!-- BUSCADOR -->
        <div class="search-container" <?php animation('fade-in-bottom', 200 );?>>
            <div class="search">
                <input type="text" placeholder="¿Qué estas buscando?" name="search" id="search-faq">
                <i class="fa-solid fa-magnifying-glass search-icon" id="search-header"></i> 
                <i class="fa-solid fa-x search-remove" id="close-search-header"></i>
            </div>
        </div>
        <!-- FAQS -->
        <div class="resultsList"></div>
        
        <!-- TABS -->
        <div class="tab" <?php animation('fade-in-bottom', 250 );?>>
            <?php if (!empty($categories_with_faqs)): ?>
                <?php foreach ($categories_with_faqs as $slug => $name): ?>
                    <button type="button" class="tab-button" data-category="<?php echo esc_attr($slug); ?>"><?php echo esc_html($name); ?></button>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <div class="faq-list" id="list" <?php animation('fade-in-bottom', 250 );?>>
            <ul>
                <?php if($query->have_posts()): ?>
                    <?php while ($query->have_posts()): ?>
                        <?php 
                            $query->the_post();
                            $id = get_the_ID(); 
                            $categories = wp_get_post_terms($id, 'product_cat');
                            $category_classes = '';
                       
                            if ( ! empty( $categories ) ){
                                foreach ( $categories as $category ){
                                    $category_classes .= ' category-' . esc_html( $category->slug );
                                }
                            }
                        ?>
                        <li class="faq<?php echo $category_classes; ?>">
                            <div class="title">
                                <h2><?php the_title();?></h2>
                                <div class="chevron">
                                    <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.07812 1.16919L7.07812 7.16919L13.0781 1.16919" stroke="#E2252D" stroke-width="2"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="content">
                                    <?php $contenido = get_field( 'contenido', $id ); ?>
                                    <?php echo $contenido; ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</div>
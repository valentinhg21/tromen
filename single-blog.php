
<?php get_header(); ?>
    <div class="single single-header-white single-blog">
        <?php the_content(); ?>

        <!-- Articles relationshed -->
        <?php 
            $post_id = $post->ID; // No es necesario llamar a get_post

            $args = [
                'post_type' => 'blog',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC',
                'post__not_in' => [$post_id], // Asegúrate de que esto es un array
            ];
            $query = new WP_Query($args);
            $post_count = $query->found_posts;
        ?>

        <div class="button__container container-sm">
            <a href="<?php echo home_url()?>/blog/" aria-label="Volver al blog" class="btn btn-only-text-red">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 12L3.64645 11.6464L3.29289 12L3.64645 12.3536L4 12ZM19 12.5C19.2761 12.5 19.5 12.2761 19.5 12C19.5 11.7239 19.2761 11.5 19 11.5V12.5ZM9.64645 5.64645L3.64645 11.6464L4.35355 12.3536L10.3536 6.35355L9.64645 5.64645ZM3.64645 12.3536L9.64645 18.3536L10.3536 17.6464L4.35355 11.6464L3.64645 12.3536ZM4 12.5H19V11.5H4V12.5Z" fill="#E2252D"/>
                </svg> Volver al blog
              
            </a>
        </div>
        <?php if($post_count > 1): ?>
            <div class="block-lista-articulos related">
                <div class="container">
                    <h2 class="lista-articulos-title">Notas relacionadas</h2>
                    <div class="row">
                        <?php if ($query->have_posts()): ?>
                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                <?php 
                                    $id = get_the_ID();
                                    $permalink = get_the_permalink();
                                    $title = get_the_title();
                                    $imagen_articulo = get_field('imagen_articulo');

                                    // Verifica si hay una imagen del artículo, de lo contrario usa una imagen por defecto
                                    $blocks = parse_blocks(get_the_content());
                                    $imagen_articulo = '';
                                    foreach ($blocks as $block) {
                                        if ($block['blockName'] === 'acf/portada-articulo' && !empty($block['attrs']['data']['imagen_articulo'])) {
                                            $imagen_articulo = $block['attrs']['data']['imagen_articulo'];
                                            break;
                                        }
                                    }                    
                                    if ($imagen_articulo) {
                                        $image_data = wp_get_attachment_image_src($imagen_articulo, 'large');
                                        $image_url = $image_data ? $image_data[0] : '';
                                        $image_w = $image_data ? $image_data[1] : '';
                                        $image_h = $image_data ? $image_data[2] : '';
                                        $alt_text = get_post_meta($imagen_articulo, '_wp_attachment_image_alt', true);
                                        $image = '<img src="' . esc_url($image_url) . '" width="' . esc_attr($image_w) . '" height="' . esc_attr($image_h) . '" alt="' . esc_attr($alt_text) . '">';
                                    } else {
                                        $image = insert_default_image(); // Asegúrate de que esta función retorne un HTML de imagen válido
                                    }
                                ?>
                                <div class="col-md-4 col-xs-6 col-12 fade-in-bottom">
                                    <a href="<?php echo esc_url($permalink); ?>" class="article-card" aria-label="Ver detalles del artículo <?php echo esc_attr($title); ?>">
                                        <div class="card">
                                            <div class="card-img"><?php echo $image; ?></div>
                                            <div class="card-body">
                                                <h3><?php echo esc_html($title); ?></h3>
                                              
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>


    </div>
<?php get_footer(); ?>
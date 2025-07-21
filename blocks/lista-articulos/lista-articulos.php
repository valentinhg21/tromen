<?php 

$page = $_GET['paged'] ?? 1;

$args = [
    'post_type' => 'blog',
    'posts_per_page' => 9,
    'paged' => $page,
    'orderby' => 'date',
    'order' => 'DESC',
];

$query = new WP_Query($args);
$total_posts = $query->found_posts;
?>
<div class="block-lista-articulos">
    <input type="hidden" name="" id="total-articles" value="<?php echo $total_posts;?>">
    <input type="hidden" name="" id="currentPageBlog" value="<?php echo $page ?? 1?>">
    <div class="container-md">
        <div class="row  scroll-margin" id="lista-articulos">
            <?php if ($query->have_posts()): ?>
                <?php while ($query->have_posts()): $query->the_post(); ?>
                    <?php 
                        $blocks = parse_blocks(get_the_content());
                        $imagen_articulo = '';
                        $permalink = get_the_permalink();
                        $title = get_the_title();
                        $image = '';

                        // Buscar la imagen en los bloques ACF
                        foreach ($blocks as $block) {
                            if ($block['blockName'] === 'acf/portada-articulo' && !empty($block['attrs']['data']['imagen_articulo'])) {
                                $imagen_articulo = $block['attrs']['data']['imagen_articulo'];
                                break; // Salimos del bucle al encontrar la imagen
                            }
                        }

                        // Si se encontró la imagen, obtenerla con la función nativa de WP
                        if ($imagen_articulo) {
                            $image = wp_get_attachment_image($imagen_articulo, 'large', false, [
                                'class' => 'mi-clase-imagen',
                                'alt' => get_post_meta($imagen_articulo, '_wp_attachment_image_alt', true)
                            ]);
                        }
                    ?>

                    <div class="col-md-4 col-xs-6 col-12 fade-in-bottom">
                        <a href="<?php echo esc_url($permalink); ?>" class="article-card" aria-label="Ver detalles del artículo <?php echo esc_attr($title); ?>">
                            <div class="card">
                                <div class="card-img"><?php echo $image; ?></div>
                                <div class="card-body">
                                    <h2><?php echo esc_html($title); ?></h2>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

        </div>
        <div id="pagination" ></div>
    </div>
</div>
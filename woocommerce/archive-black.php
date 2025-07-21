<?php
global $header_type;
$header_type = 'default';

$category = get_queried_object(); 
$cat = cat_details();
$cat_id = $cat['id'];
$title = $cat['title'];
$description = $cat['description'];
$image_url = $cat['image_url'];
$parent_cat = $cat['parent_cat'];


// ACF
$seleccionar_video = get_field( 'seleccionar_video', $category );
$video = get_field( 'video' ,$category  );
$youtube = get_field( 'youtube' ,$category  );
$image_url_cat = $image_url;
$premier = get_field( 'destacar', $category );
if($seleccionar_video){
	if($video){
		$image_url_cat = "";
	}else{
        $image_url_cat = "style='background-image:url($image_url);'";
    }
}else{
	if($youtube == "" || $youtube){
     
		$image_url_cat = "style='background-image:url($image_url);'";
	}else{
		$image_url_cat = "";
	}
}

// BLACK
$logo_black = get_field( 'logo_black', $category );

?>

<?php get_header(); ?>
<div class="archive-product archive-black <?php echo $premier;?>" id="<?php echo $cat_id; ?>">
    <div class="hero-custom" <?php echo $image_url_cat;?>>
        <?php if($seleccionar_video): ?>
				<?php if($video): ?>		
					<div class="video-container">
						<video width="100%" height="100%" autoplay muted loop playsinline>
							<source src="<?php echo esc_url( $video['url'] );?>"  type="video/mp4"/>
						</video>
					</div>		
				<?php endif; ?>
				<?php else: ?>
					<?php if($youtube): ?>
						<div class="video-container">                    
								<iframe
								src="<?php echo idYoutube($youtube);?>?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&mute=1&"
								frameborder="0" allow="autoplay; encrypted-media" 
								allowfullscreen loading="lazy"
								></iframe>
						</div>
					<?php endif; ?>
		<?php endif; ?>
     
            <div class="container">
                <div class="content">
                    <?php if($logo_black): ?>
                         <?php insert_image($logo_black); ?>
                         <h1 class="d-none"<?php animation('tracking-in-contract', 200); ?>><?php echo $title; ?></h1>
                    <?php else: ?>
                         <h1 <?php animation('tracking-in-contract', 200); ?>><?php echo $title; ?></h1>
                    <?php endif; ?>
                   
                    <div class="desc fade-in-bottom">
                    <?php if ( have_rows( 'texto_black', $category ) ) : ?>
                        <?php while ( have_rows( 'texto_black', $category ) ) :
                        the_row(); ?>
                        <?php 
                            $texto = get_sub_field( 'texto', $category );
                            $texto_2 = get_sub_field( 'texto_2', $category );
                    
                         ?>
                            <p>
                                <?php insert_acf($texto, 'span'); ?>
                                <?php echo $texto_2; ?>
                            </p>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <?php echo $description; ?>
                    <?php endif; ?>
                        
                    </div>
                
                </div>
            </div>
      
    </div>
    <div class="archive-products-container">
        <div class="container container-sm-8">
            <div class="products-container list-products carousel-products p-relative">
                <div class="row">
                    <?php
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC',
                        'post_status'    => 'publish', // Solo productos publicados
                        'meta_query'     => array(
                            array(
                                'key'     => '_stock_status',
                                'value'   => 'instock',
                                'compare' => '='
                            )
                        ),
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'term_id',
                                'terms'    => $cat_id,
                            ),
                        ),
                    );
                    $products = new WP_Query($args);
                    ?>
                    <?php if ($products->have_posts()) : ?>
                        <?php $i = 1; ?>
                        <?php while ($products->have_posts()) : $products->the_post(); ?>
                            <?php
                            global $product;
                            // Ensure visibility.
                            if (empty($product) || !$product->is_visible()) {
                                return;
                            }
                                $product_data = product_details($product);
                                $product_name = $product_data['product_name'];
                                $product_permalink = $product_data['product_permalink'];
                                $first_gallery_image = $product_data['first_gallery_image'];
                                $product_image = $product_data['product_image'];
                                $sub_category_name = $product_data['category_name'];
                                $gallery = $product_data['gallery'];
                                $tags = $product_data['tags_html'];
                                $image_black = $product_data['image_black'];
                            ?>
                            <div class="product-item col-xs-6 col-12">
                                <a <?php animation('slide-in-bottom', $i++ * 60); ?> href="<?php echo $product_permalink; ?>" class="product-card" aria-label="Ver detalles de <?php echo $product_name; ?>">
                                    <div class="product-image">
                                        <?php if($image_black): ?>
                                            <img fetchpriority="high" decoding="async" src="<?php echo $image_black['url'];?>" class="attachment-large size-large" width="<?php echo $image_black['width'];?>" height="<?php echo $image_black['height'];?>">
                                            <?php else: ?>
                                                <?php if ($gallery) : ?>
                                                    <img fetchpriority="high" decoding="async" src="<?php echo $gallery[0]['url'];?>" class="attachment-large size-large" width="<?php echo $gallery[0]['width'];?>" height="<?php echo $gallery[0]['height'];?>">
                                                <?php else : ?>
                                                    <img fetchpriority="high" decoding="async" src="<?php echo IMAGE ?>/productos/default.png" class="attachment-large size-large" width="100%" height="100%">
                                                <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php echo $tags; ?>
                                    <div class="product-body p-relative">
                                        <p><?php echo $product_name; ?></p>
                                        <button class="permalink">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                            
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>


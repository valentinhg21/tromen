<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;
get_header(); ?>

<?php if(!is_front_page() && !is_home()): ?>
	<?php 
		$category = get_queried_object(); 
		$category_id = $category->term_id;
		// Get image
		$category_image_url = get_term_meta($category_id, 'thumbnail_id', true);
		if (!empty($category_image_url)) {
			$image = wp_get_attachment_image_src($category_image_url, 'full');
			$category_image_url = $image[0]; // Obtiene la URL de la imagen completa
		}
		$image_url = $category_image_url;
		// Get subcategory
		$subcategories = get_terms( array(
			'taxonomy' => 'product_cat', // Taxonomía de WooCommerce para categorías de productos
			'parent'   => $category_id, // ID de la categoría padre
			'hide_empty' => false,
		));
		// ACF
		$seleccionar_video = get_field( 'seleccionar_video', $category );
		$video = get_field( 'video' ,$category  );
		$youtube = get_field( 'youtube' ,$category  );
		if($seleccionar_video){
			if($video){
				$image_url = "";
			}else{
				$image_url = "style='background-image:url($category_image_url);'";
			}
		}else{
			if($youtube == ""){
				$image_url = "style='background-image:url($category_image_url);'";
			}else{
				$image_url = "";
			}
		}
		$title_category = get_field( 'titulo', $category);
	?>
	<div class="archive-product" id="<?php echo $category_id;?>">
		<div class="hero-custom fade" <?php echo $image_url;?>>
			<?php if($seleccionar_video): ?>
				<?php if($video): ?>
					<div class="video-container">
						<video id="video-product" autoplay muted loop playsinline>
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
					<div <?php animation('slide-in-bottom', 100 );?> >
						<?php if($title_category): ?>
							<?php insert_acf($title_category, 'h1'); ?>
							<?php else: ?>
								<h1><?php single_term_title();?></span></h1>
						<?php endif; ?>
					</div>
					<div class="d-flex-md flex-column d-none">
						<div  <?php animation('slide-in-bottom', 120 );?>>
							<?php echo term_description(); ?>
						</div>
		
						<?php if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ): ?>
								<ul class="subcategory-list">
									<?php $i = 1; ?>
									
									<?php foreach ( $subcategories as $subcategory ): ?>
									
										<?php 
											$delay = $i++ * 100;
											$id = $subcategory->term_id;
											$title = get_field('titulo', $subcategory);
							
										?>
									
										<li <?php animation('slide-in-right', $delay );?>>
											<a class="btn btn-transparent btn-w-lg" href="<?php echo get_term_link( $subcategory );?>">
												<?php if($title): ?>
														<?php echo $title; ?>
													<?php else: ?>
														<?php echo single_term_title() . " " . $subcategory->name;?>
												<?php endif; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="hero-description d-none-md">
			<div class="container">
					<div class="content">
						<div <?php animation('slide-in-bottom', 120 );?>>
							<?php echo term_description(); ?>
						</div>
						
						<?php if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ): ?>
								<ul class="subcategory-list">
									<?php $i = 1; ?>
									<?php foreach ( $subcategories as $subcategory ): ?>
										<?php 
											$delay2 = $i++ * 100; 
											$id = $subcategory->term_id;
											$title = get_field('titulo', $subcategory);
										?>
										<li <?php animation('slide-in-bottom', $delay2 );?>>
											<a class="btn btn-red-transparent btn-w-lg" href="<?php echo get_term_link( $subcategory );?>">
												<?php if($title): ?>
														<?php echo $title; ?>
													<?php else: ?>
														<?php echo single_term_title() . " " . $subcategory->name;?>
												<?php endif; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
						<?php endif; ?>
					</div>
			</div>
		</div>
		<div class="archive-products-container">
			<?php if (!empty($subcategories) && !is_wp_error($subcategories)) : ?>
					<?php foreach ($subcategories as $subcategory) : ?>
						<?php
						// Obtiene los productos no destacados de la subcategoría actual
						$args = array(
							'post_type'      => 'product',
							'posts_per_page' => -1, // Cantidad de productos a mostrar,
							'order' => 'ASC',
							'tax_query'      => array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $subcategory->term_id,
								),
							),
							'meta_query'     => array(
								'relation' => 'OR',
								array(
									'key'     => '_featured',
									'value'   => 'no',
									'compare' => '=',
								),
								array(
									'key'     => '_featured',
									'compare' => 'NOT EXISTS', // Excluye productos que no tienen la clave _featured
								),
							),
						);
						$products = new WP_Query($args);
						?>
						<div class="carousel-products d-none">
							<div class="container container-sm-8">
								<?php $subcategory_title_lower = strtolower($subcategory->name); ?>
								<div class="splide-title" <?php animation('fade-in-bottom', 500);?>>
									<h2 id="carousel-heading-<?php echo $subcategory->slug;?>">Productos destacados <?php echo $subcategory_title_lower; ?></h2>
									<?php if ($products->have_posts()) : ?>
									<div class="see-more">
										<a class="btn btn-red-transparent btn-sm" href="<?php echo get_term_link($subcategory);?>" target="_self" arial-label="Ver todos los productos de <?php echo single_term_title() . ' ' .$subcategory_title_lower;?>">  
											Ver todos
										</a>
									</div>
									<?php endif; ?>
								</div>

								<?php if ($products->have_posts()) : ?>
										<div class="splide splide-archive" aria-labelledby="carousel-heading-<?php echo $subcategory->slug;?>" role="group" <?php animation('fade-in-bottom', 500);?>>
											<div class="splide__track">
												<ul class="splide__list" >
													<?php while ($products->have_posts()) : $products->the_post(); ?>
														<?php 
															global $product;

															// Ensure visibility.
															if ( empty( $product ) || ! $product->is_visible() ) {
																return;
															}
															$product_data = product_details($product);
															$product_name = $product_data['product_name'];
															$product_permalink = $product_data['product_permalink'];
															$first_gallery_image = $product_data['first_gallery_image'];
															$product_image = $product_data['product_image'];
															$product_price = $product_data['product_price'];
															$category = $product_data['category_name'];
															$discount_per = $product_data['discount'];
															$tags = $product_data['tags_html'];
														?>
														<li class="splide__slide">
															<a href="<?php echo $product_permalink;?>" class="product-card" aria-label="Ver detalles de <?php echo $product_name;?>">
																<div class="product-image">
																	<?php if($product_image):?>
																		<?php  echo $product_image;?>
																		<?php else: ?>
																			<img fetchpriority="high" decoding="async" src="<?php echo IMAGE ?>/productos/default.png" class="attachment-large size-large" width="100%" height="100%">
																	<?php endif; ?>
																	<?php echo $discount_per; ?>
																	<?php echo $tags; ?>
            													
																</div>
																<div class="product-body p-relative">
																	<p><?php echo $product_name; ?></p>
																	<p><?php echo $product_price;?></p>
																	<?php price_fee_html($product); ?>
																	<p class="text-red"><?php echo $category; ?></p>
																	<button class="permalink">
																		<i class="fa-solid fa-plus"></i>
																	</button>
																</div>
															</a>
														</li>							
													<?php endwhile; ?>
												</ul>
											</div>
											<?php wp_reset_postdata(); ?>
										</div>
								<?php else : ?>
									<p class="no-products" <?php animation('fade-in-bottom', 500);?>>No hay productos disponibles en esta subcategoría.</p>
								<?php endif; ?>
								<div class="see-more d-none-xs" <?php animation('scale-in-center', 500);?>>
									<a class="btn btn-red-transparent btn-sm" href="<?php echo get_term_link($subcategory);?>" target="_self" arial-label="Ver todos los productos de <?php echo single_term_title() . ' ' .$subcategory->name;?>">  
										Ver todos
									</a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
			<?php else : ?>
					<p class="no-products d-none">No hay subcategorías disponibles en esta categoría.</p>
			<?php endif; ?>		
			<?php 
			$flexibleContentPath = dirname(__FILE__) . '/flexible-content/';
			if ( have_rows( 'productos_destacados', 'product_cat_' . get_queried_object()->term_id  ) ) :
			 while ( have_rows( 'productos_destacados', 'product_cat_' . get_queried_object()->term_id  ) )  :
			  the_row();
			  $layout = get_row_layout();
			  $file = ( $flexibleContentPath . str_replace( '_', '-', $layout) . '.php' );
			  if ( file_exists( $file ) ) {
			   include( $file );
			  }
			 endwhile;
			endif; ?>	
		</div>
	</div>
	<?php else: ?>
		<?php 
			$shop_page_id = wc_get_page_id('shop');
			$shop_page = get_post($shop_page_id);
			$shop_page_content = apply_filters('the_content', $shop_page->post_content);
			?>
		<div class="single">
			<?php echo $shop_page_content; ?>
		</div>
<?php endif; ?>
<?php get_footer(); ?>

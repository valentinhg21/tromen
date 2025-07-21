<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
// Datasheet
$imagen_destacada = get_field( 'imagen_destacada' );
$potencia = get_field( 'potencia' );
$caracteristicas = get_field( 'caracteristicas' );
$seleccionar_video = get_field( 'seleccionar_video' );
$video = get_field( 'video' );
$youtube = get_field( 'youtube' );
$seleccionar_video_2 = get_field( 'seleccionar_video_2' );
$video_2 = get_field( 'video_2' );
$youtube_2 = get_field( 'youtube_2' );
$product_data = product_details($product);
$discount_per = $product_data['discount'];
$tags = $product_data['tags_html'];

// CATEGORIA
$terms = get_the_terms( $product->get_id(), 'product_cat' );
$is_black = '';

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    foreach ( $terms as $term ) {
        $categoria_id = $term->term_id;
        // Obtener campo ACF de la categoría (taxonomía)
        $is_black = get_field( 'destacar', 'product_cat_' . $categoria_id );
		$is_black = sanitize_title( $is_black );
    }
}

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $is_black , $product ); ?>>
    <div class="single-product-header">
		<div class="container">	
			<div class="row">
				<div class="col-sm-7 col-12">
					<?php 	
					$attachment_ids = $product->get_gallery_image_ids(); 
					// Get the featured image ID
					$featured_image_id = get_post_thumbnail_id( $product->get_id() );
					if ( $featured_image_id ) {
						array_unshift( $attachment_ids, $featured_image_id );
					}
					?>
					<?php 
						$attachment_idsClass = count( $attachment_ids ) === 1  ? 'one-image' : ''; 
						$attachment_idsClass2 = count( $attachment_ids ) === 0  ? 'one-image' : ''; 
					?>
					<div class="product-gallery <?php echo $attachment_idsClass ?> <?php echo $attachment_idsClass2 ?>" id="lightgallery">
							<?php
							/**
							 * Hook: woocommerce_before_single_product_summary.
							 *
							 * @hooked woocommerce_show_product_sale_flash - 10
							 * @hooked woocommerce_show_product_images - 20
							 */
							?>
								<div class="slide-product__main splide">
									<div class="splide__track">
										<ul class="splide__list"  id="tromen-gallery">
											<?php if(count($attachment_ids) > 0): ?>				
												<?php foreach ( $attachment_ids as $attachment_id ): ?>
													<?php 
														$full_size_image_url = wp_get_attachment_image_url( $attachment_id, 'full' );
														$image_src = wp_get_attachment_image_src($attachment_id, 'full');
														
														if ($image_src) {
															$image_url = $image_src[0]; // URL de la imagen
															$image_width = $image_src[1]; // Ancho de la imagen
															$image_height = $image_src[2]; // Altura de la imagen
														}
													?>
													<a href="<?php echo $image_url; ?>" class="splide__slide"
														data-pswp-width="<?php echo $image_width; ?>" 
														data-pswp-height="<?php echo $image_height; ?>" 
													>
														<?php echo wp_get_attachment_image( $attachment_id, 'full', false, array('loading' => 'lazy') ); ?>
													</a>
												<?php endforeach; ?>
											<?php else: ?>
												<?php wc_get_template('feature-image-default.php'); ?>
											<?php endif; ?>
											
										</ul>
									</div>
									<div class="splide__arrows splide__arrows--ltr">
										<button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous slide"
											aria-controls="splide01-track">
											<svg width="11" height="21" viewBox="0 0 11 21" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M0.708865 10.4919C0.708865 9.88542 0.945373 9.31386 1.37443 8.88329L9.38089 0.876831L10.4528 1.9487L2.4463 9.95516C2.15976 10.2417 2.15976 10.7405 2.4463 11.027L10.4528 19.0335L9.38088 20.1054L1.37443 12.0989C0.945373 11.6699 0.708865 11.0983 0.708865 10.4903L0.708865 10.4919Z"
													fill="white" />
											</svg>
										</button>
										<button class="splide__arrow splide__arrow--next" type="button" aria-label="Next slide"
											aria-controls="splide01-track">
											<svg width="11" height="21" viewBox="0 0 11 21" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M10.2913 10.4919C10.2913 9.88542 10.0547 9.31386 9.6257 8.88329L1.61924 0.876831L0.547363 1.9487L8.55382 9.95516C8.84036 10.2417 8.84036 10.7405 8.55382 11.027L0.547364 19.0335L1.61924 20.1054L9.6257 12.0989C10.0547 11.6699 10.2913 11.0983 10.2913 10.4903L10.2913 10.4919Z"
													fill="white" />
											</svg>

										</button>
									</div>
									<?php echo $discount_per; ?>
									<?php echo $tags; ?>
								</div>
					</div>
				</div>
				<div class="col-sm-5 col-12">
					<div class="single-product-header__info">					
						<?php breadcrumb(); ?>
						<?php
							/**
							* Hook: woocommerce_single_product_summary.
							*
							* @hooked woocommerce_template_single_title - 5
							* @hooked woocommerce_template_single_rating - 10
							* @hooked woocommerce_template_single_price - 10
							* @hooked woocommerce_template_single_excerpt - 20
							* @hooked woocommerce_template_single_add_to_cart - 30
							* @hooked woocommerce_template_single_meta - 40
							* @hooked woocommerce_template_single_sharing - 50
							* @hooked WC_Structured_Data::generate_product_data() - 60
							*/
							do_action( 'woocommerce_single_product_summary' );
						?>
					
						<?php if(is_ecommerce_active($product->get_id())): ?>
							<?php do_action( 'woocommerce_simple_add_to_cart' ); ?>
							<?php else: ?>
								<div class="where-buy">
											<button type="button" id="openModal" class="btn btn-red-black btn-md" >
												Dónde comprar
											</button>
											<?php if($caracteristicas || $potencia || $caracteristicas  ): ?>
												<a href="#especificaciones" class="btn btn-red-transparent-black btn-md" >
													Ver especificaciones
												</a>
											<?php endif; ?>
											<?php if($video || $youtube || $video_2 || $youtube_2 ): ?>
												<a href="#video" class="btn btn-red-transparent-black btn-md" >
													Ver video
												</a>
											<?php endif; ?>
								</div>
						<?php endif; ?>


					</div>
				</div>
			</div>
		</div>
    </div>
	<?php if($imagen_destacada): ?>
		<div class="feature-image-container">
			<div class="container">
				<div class="feature-image splide">
					<div class="splide__track">
						<ul class="splide__list" id="tromen-feature">
							<?php foreach( $imagen_destacada as $image ) : ?>
								<?php 
									$width = $image['sizes']['large-width'];
									$height = $image['sizes']['large-height'];						
								?>
							
									<a 
									 href="<?php echo esc_url( $image['url'] ); ?>" 
									 data-pswp-width="<?php echo $width;?>" 
									 data-pswp-height="<?php echo $height;?>" 
									 class="splide__slide image"
									 >
										<img  loading="lazy" width=<?php echo $width;?> height=<?php echo $height;?> src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] );?>"/>
									</a>
							
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="splide__arrows splide__arrows--ltr">
										<button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous slide"
											aria-controls="splide01-track">
											<svg width="11" height="21" viewBox="0 0 11 21" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M0.708865 10.4919C0.708865 9.88542 0.945373 9.31386 1.37443 8.88329L9.38089 0.876831L10.4528 1.9487L2.4463 9.95516C2.15976 10.2417 2.15976 10.7405 2.4463 11.027L10.4528 19.0335L9.38088 20.1054L1.37443 12.0989C0.945373 11.6699 0.708865 11.0983 0.708865 10.4903L0.708865 10.4919Z"
													fill="white" />
											</svg>
										</button>
										<button class="splide__arrow splide__arrow--next" type="button" aria-label="Next slide"
											aria-controls="splide01-track">
											<svg width="11" height="21" viewBox="0 0 11 21" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M10.2913 10.4919C10.2913 9.88542 10.0547 9.31386 9.6257 8.88329L1.61924 0.876831L0.547363 1.9487L8.55382 9.95516C8.84036 10.2417 8.84036 10.7405 8.55382 11.027L0.547364 19.0335L1.61924 20.1054L9.6257 12.0989C10.0547 11.6699 10.2913 11.0983 10.2913 10.4903L10.2913 10.4919Z"
													fill="white" />
											</svg>

										</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if($caracteristicas || $potencia || have_rows( 'dimensiones' ) ): ?>
		<div class="datasheet-product-block" id="especificaciones">
			<div class="container">
				<div class="datasheet-product">
					<h2>Especificaciones</h2>
					<?php if($potencia): ?>
						<div class="row">
							<div class="col-md-4 col-12 column-title">
								<p class="text-red">Potencia</p>
							</div>
							<div class="col-md-8 col-12 column-info">
								<p>
									<?php echo $potencia;?>
								</p>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( have_rows( 'dimensiones' ) ) : ?>
						<?php while ( have_rows( 'dimensiones' ) ) : the_row(); ?>
						<?php  
							$ancho = get_sub_field( 'ancho' );
							$alto = get_sub_field( 'alto' );
							$profundidad = get_sub_field( 'profundidad' );
							$peso = get_sub_field( 'peso' );
							$diametro_total = get_sub_field( 'diametro_total' );
							$diametro_coccion = get_sub_field( 'diametro_coccion' );
						?>
							<?php if($ancho || $alto || $profundidad || $peso || $diametro_total || $diametro_coccion): ?>
								<div class="row">
				
										<div class="col-md-4 col-12 column-title">
											<p class="text-red">Dimensiones</p>
										</div>
									

									<div class="col-md-8 col-12 column-info">
										<ul class="grid-info">
											<?php if($ancho): ?>
												<li>
													<p class="column-info-title">ANCHO</p>
														<?php echo $ancho; ?> cm
												</li>
												<?php else: ?>
											<?php endif; ?>
											<?php if($alto): ?>
												<li>
												<p class="column-info-title">ALTO</p>
													<?php echo $alto; ?> cm
												</li>
												<?php else: ?>
											<?php endif; ?>
											<?php if($profundidad): ?>
												<li>
												<p class="column-info-title profundidad">PROFUNDIDAD</p>
													<?php echo $profundidad; ?> cm
												</li>
												<?php else: ?>
											<?php endif; ?>
											<?php if($diametro_total): ?>
												<li>
												<p class="column-info-title">DIÁMETRO TOTAL</p>
													<?php echo $diametro_total; ?> cm
												</li>
											<?php endif; ?>
											<?php if($diametro_coccion): ?>
												<li>
												<p class="column-info-title">DIÁMETRO COCCIÓN</p>
													<?php echo $diametro_coccion; ?> cm
												</li>

											<?php endif; ?>
											<?php if($peso): ?>
												<li>
													<p class="column-info-title">PESO</p>
													<?php echo $peso; ?> kg
												</li>

											<?php endif; ?>
											
								
										</ul>
									</div>
								
								</div>
							<?php endif; ?>
						<?php endwhile; ?>
					<?php endif; ?>
					<?php if($caracteristicas): ?>
						<div class="row">
							<div class="col-md-4 col-12 column-title">
								<p class="text-red">Características</p>
							</div>
							<div class="col-md-8 col-12 column-info">
								<ul class="caracteristicas">
									<?php insert_textarea($caracteristicas, 'li') ?>
								</ul>

							</div>
						</div>
					<?php endif; ?>
					<?php if ( have_rows( 'descargas' ) ) : ?>
						<?php while ( have_rows( 'descargas' ) ) : the_row(); ?>
							<?php 
								$manual = get_sub_field( 'manual' ); 
								$ficha = get_sub_field( 'ficha_tecnica' );
								$cad = get_sub_field( 'cad_del_producto' );
							?>
							<?php if($manual || $ficha || $cad): ?>
								<div class="row">
								
										<div class="col-md-4 col-12 column-title">
											<p class="text-red">Descargas</p>
										</div>
									
										<div class="col-md-8 col-12 column-info">
											<div class="downloads">

												<?php insert_download_button($manual, 'Manual'); ?>
												<?php insert_download_button($ficha, 'Ficha Técnica'); ?>
												<?php insert_download_button($cad, 'cad del producto'); ?>

											</div>
										</div>
								
								</div>
							<?php endif; ?>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if($video || $youtube || $video_2 || $youtube_2 ): ?>	
		<div class="slide-product__videos splide" id="video">
			<div class="container p-relative">
				<div class="splide__track">
					<ul class="splide__list">		
							<?php if($seleccionar_video): ?>						
								<?php if($video): ?>
									<li class="splide__slide videos">
										<div class="video-block">
												<video id="video-product" playsinline controls>
													<source src="<?php echo esc_url( $video['url'] );?>"  type="video/mp4"/>
												</video>
										</div>
									</li>
								<?php endif; ?>
							<?php else: ?>
								<?php if($youtube): ?>
									<li class="splide__slide videos">
										<div class="video-frame">                    
											<div class="plyr__video-embed">
												<iframe
													src="<?php echo idYoutube($youtube);?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&aplaysinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
													allowfullscreen
													allowtransparency
													allow="autoplay"
												></iframe>
											</div>
										</div>
									</li>
								<?php endif; ?>
							<?php endif; ?>
							<?php if($seleccionar_video_2): ?>
								<?php if($video_2): ?>
									<li class="splide__slide videos">
										<div class="video-block">
												<video id="video-product" playsinline controls>
													<source src="<?php echo esc_url( $video_2['url'] );?>"  type="video/mp4"/>
												</video>
										</div>
									</li>
								<?php endif; ?>
							<?php else: ?>
								<?php if($youtube_2): ?>
									<li class="splide__slide videos">
										<div class="video-frame">                    
											<div class="plyr__video-embed">
												<iframe
													src="<?php echo idYoutube($youtube_2);?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&aplaysinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
													allowfullscreen
													allowtransparency
													allow="autoplay"
												></iframe>
											</div>
										</div>
									</li>
								<?php endif; ?>
							<?php endif; ?>
					</ul>
				</div>
				<div class="splide__arrows splide__arrows--ltr">
										<button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous slide"
											aria-controls="splide01-track">
											<svg width="11" height="21" viewBox="0 0 11 21" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M0.708865 10.4919C0.708865 9.88542 0.945373 9.31386 1.37443 8.88329L9.38089 0.876831L10.4528 1.9487L2.4463 9.95516C2.15976 10.2417 2.15976 10.7405 2.4463 11.027L10.4528 19.0335L9.38088 20.1054L1.37443 12.0989C0.945373 11.6699 0.708865 11.0983 0.708865 10.4903L0.708865 10.4919Z"
													fill="currentColor" />
											</svg>
										</button>
										<button class="splide__arrow splide__arrow--next" type="button" aria-label="Next slide"
											aria-controls="splide01-track">
											<svg width="11" height="21" viewBox="0 0 11 21" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path
													d="M10.2913 10.4919C10.2913 9.88542 10.0547 9.31386 9.6257 8.88329L1.61924 0.876831L0.547363 1.9487L8.55382 9.95516C8.84036 10.2417 8.84036 10.7405 8.55382 11.027L0.547364 19.0335L1.61924 20.1054L9.6257 12.0989C10.0547 11.6699 10.2913 11.0983 10.2913 10.4903L10.2913 10.4919Z"
													fill="currentColor" />
											</svg>

										</button>
				</div>
			</div>
		</div>
	<?php endif; ?>
    <?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
<?php 
$posts = get_field( 'productos' );

?>
<div class="block-dos-columnas-productos">
    <div class="hidden">
        <div class="container container-sm-8">
            <div class="row">
                <?php
                if ( $posts ) : ?>
                <?php  $i = 0; ?>
                <?php foreach( $posts as $post) : ?>
                    <?php setup_postdata( $post ); ?>
                        <?php 
                            $title = $post->post_title;
                            $id = $post->ID;
                            $short_desc = wc_get_product($id)->get_short_description();
                            $permalink = get_the_permalink($post->ID);
                            $imagen_destacada = get_field( 'imagen_destacada', $id );
                            $product_categories = get_the_terms($id, 'product_cat');
                            $cat = $product_categories[0]->name;
               
                            // $sub_category_name = $product_data['sub_category_name'];
                        ?>
                        <div class="col-md-6 col-12">
                            <a class="product-card" href="<?php echo $permalink; ?>" <?php animation('slide-in-right', $i++ * 120);?>>
                                <?php if($imagen_destacada): ?>
                                    <div class="image">
                                        <img src="<?php echo esc_url( $imagen_destacada[0]['sizes']['1536x1536'] ); ?>" alt="<?php echo esc_attr( $imagen_destacada[0]['alt'] ); ?>"/>
                                     
                                    </div>
                                <?php endif; ?>
                                <div class="body">
                                    <span <?php animation('slide-in-bottom', $i++ * 120);?> ><?php echo  $cat;?></span>
                                    <h2 <?php animation('slide-in-bottom', $i++ * 130);?>><?php echo $title?></h2>
                                    <p <?php animation('slide-in-bottom', $i++ * 140);?>>
                                        <?php echo $short_desc?>
                                    </p>
                                </div>
                                <button class="permalink">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>
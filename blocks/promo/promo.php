


<div class="block-promo">
    <div class="splide splide-promo container container-sm-8">
        <div class="splide__track">
            <ul class="splide__list">
                <?php if ( have_rows( 'slides' ) ) : ?>
                    <?php while ( have_rows( 'slides' ) ) :
                    the_row(); ?>
                    <?php  
                        $imagen = get_sub_field('imagen')['url'];
                        $imagen_mobile = get_sub_field('imagen_mobile')['url'] ?: $imagen;
                        $bg = "--background-image-desktop: url(" . esc_url($imagen) . "); --background-image-mobile: url(" . esc_url($imagen_mobile) . ");";
                        $link_data = get_sub_field('link');
                        $link = 'javascript:void(0);';
                        if($link_data){
                            $link = $link_data['url'];
                        }else{
                            $link = 'javascript:void(0);';
                        }
                   

                    ?>
                        <li class="splide__slide">
                            <div <?php animation('fade-in', 500);?> >
                                <a href="<?php echo  $link?>" class="banner p-relative hidden" style="<?php echo $bg;?>" data-image-desktop="<?php echo esc_url( $imagen ); ?>" data-image-mobile="<?php echo esc_url( $imagen_mobile ); ?>"></a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="splide__arrows splide__arrows--ltr">
                <button
                    class="splide__arrow splide__arrow--prev"
                    type="button"
                    aria-label="Previous slide"
                    aria-controls="splide01-track"
                >
                <svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.708865 10.4919C0.708865 9.88542 0.945373 9.31386 1.37443 8.88329L9.38089 0.876831L10.4528 1.9487L2.4463 9.95516C2.15976 10.2417 2.15976 10.7405 2.4463 11.027L10.4528 19.0335L9.38088 20.1054L1.37443 12.0989C0.945373 11.6699 0.708865 11.0983 0.708865 10.4903L0.708865 10.4919Z" fill="white"/>
                </svg>
                </button>
                <button
                    class="splide__arrow splide__arrow--next"
                    type="button"
                    aria-label="Next slide"
                    aria-controls="splide01-track"
                >
                    <svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.2913 10.4919C10.2913 9.88542 10.0547 9.31386 9.6257 8.88329L1.61924 0.876831L0.547363 1.9487L8.55382 9.95516C8.84036 10.2417 8.84036 10.7405 8.55382 11.027L0.547364 19.0335L1.61924 20.1054L9.6257 12.0989C10.0547 11.6699 10.2913 11.0983 10.2913 10.4903L10.2913 10.4919Z" fill="white"/>
                    </svg>
                </button>
        </div>
    </div>
</div>

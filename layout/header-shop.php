<?php 
$icon_mini_cart = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1 1H2.62563C3.193 1 3.47669 1 3.70214 1.12433C3.79511 1.17561 3.87933 1.24136 3.95162 1.31912C4.12692 1.50769 4.19573 1.7829 4.33333 2.33333L4.51493 3.05972C4.616 3.46402 4.66654 3.66617 4.74455 3.83576C5.01534 4.42449 5.5546 4.84553 6.19144 4.96546C6.37488 5 6.58326 5 7 5V5" stroke="#222222" stroke-width="2" stroke-linecap="round"/>
<path d="M15 14H4.55091C4.40471 14 4.33162 14 4.27616 13.9938C3.68857 13.928 3.28605 13.3695 3.40945 12.7913C3.42109 12.7367 3.44421 12.6674 3.49044 12.5287V12.5287C3.54177 12.3747 3.56743 12.2977 3.59579 12.2298C3.88607 11.5342 4.54277 11.0608 5.29448 11.0054C5.3679 11 5.44906 11 5.61137 11H11" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.7639 11H6.69425C5.71658 11 4.8822 10.2932 4.72147 9.3288L4.2911 6.7466C4.13872 5.8323 4.84378 5 5.77069 5H15.382C16.1253 5 16.6088 5.78231 16.2764 6.44721L14.5528 9.89443C14.214 10.572 13.5215 11 12.7639 11Z" fill="#222222" stroke="#222222" stroke-width="2" stroke-linecap="round"/>
<circle cx="14" cy="17" r="1" fill="#222222"/>
<circle cx="6" cy="17" r="1" fill="#222222"/>
</svg>
';
$items_count = WC()->cart->get_cart_contents_count();
?>

<header id="main-header" class="scrolling-default">
    <nav class="container navbar-header">
        <div class="logo">
            <?php display_custom_logo(); ?>
        </div>
        <div class="menu-links-container"> 
            <div class="backdrop">
                <div class="logo-mobile d-none-md">
                    <?php display_custom_logo(); ?>
                </div>
                <?php 
                    if (has_nav_menu('main-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu',
                            'menu' => '',
                            'menu_class' => 'menu',
                            'menu_id' => 'menu-menu',
                            'container_class' => 'menu__container',
                            'walker' => new Walker_Zetenta_Menu()
                     
                        ));
                    }
                ?>
            </div>
        </div>
        <div class="header-right">
            <div class="cart-btn">
                <button type="button" id="open-cart-header">
                    <div class="mini-cart-count"><span><?php echo esc_html($items_count); ?></span></div>
                    <?php echo $icon_mini_cart;?>
                </button>
            </div>
            <div class="search">
                <div class="icon" id="toggle-search">
                    <i class="fa-solid fa-magnifying-glass " id="search-header"></i>
                    <i class="fa-solid fa-x " id="close-search-header"></i>
                </div>
                <div class="search-results-block">
                      <div class="search-results">
                        <div class="container"> 
                            <p class="name-search">Encontrá tu Tromen ideal.</p>           
                            <div class="search-results-input p-relative">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" placeholder="Buscar..." id="search-input" >
                            </div>
                            <div class="splide splide-search carousel-products">
                                <div class="splide__track">
                                    <ul class="splide__list" id="search-list-container">
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
                      </div>                      
                </div>
            </div>
            <div class="actions-mobile">
                <div class="hamburger hamburger--slider">
                    <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu-header-right">
            <div class="menu-header-right-container">
                <button type="button" class="button-menu-right" id="btn-right">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <?php 
                    if (has_nav_menu('main-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu',
                            'menu' => '',
                            'menu_class' => 'menu-mobile d-none-md',
                            'menu_id' => 'menu-mobile',
                            'container_class' => 'menu__container-mobile',
                            'walker' => new Walker_Zetenta_Menu()
                     
                        ));
                    }
                ?>
                <?php 
                    if (has_nav_menu('main-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu-right',
                            'menu' => '',
                            'menu_class' => 'menu',
                            'menu_id' => 'menu-menu',
                            'container_class' => 'menu__container-right',
                            'walker' => new Walker_Zetenta_Menu_Right()
                        ));
                    }
                ?>
                <div class="lang-mobile">
                    <?php echo do_shortcode('[weglot_switcher]'); ?>  
                </div>
          
                <div class="social d-none-md">
                    <?php if ( have_rows( 'item', 'options' ) ) : ?>
                        <?php while ( have_rows( 'item', 'options' ) ) :
                        the_row(); ?>                
                                <?php if ( $link = get_sub_field( 'link', 'options' ) ) : ?>
                                    <a href="<?php echo esc_url( $link ); ?>" target="_blank" >
                                        <?php if ( $icon = get_sub_field( 'icon', 'options' ) ) : ?>
                                            <?php echo $icon; ?>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>     
  
            </div>              
        </div>

    </nav>
</header>
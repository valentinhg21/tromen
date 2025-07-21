


<footer>
    <div class="hidden">
        <div class="container">
            <nav class="row">
                <div class="col-12">
                    <div class="logo" <?php animation('fade-in-right', 100);?>>
                        <?php display_custom_logo(); ?>
                    </div>
                    <div class="social">
                        <?php $i = 1; ?>
                        <ul>
                            <?php if ( have_rows( 'item', 'options' ) ) : ?>
                                <?php while ( have_rows( 'item', 'options' ) ) :
                                the_row(); ?>
                                <?php 
                                    $link = get_sub_field( 'link', 'options' ); 
                                    $icon = get_sub_field( 'icon', 'options' );
                                ?>
                                    <li <?php animation('fade-in-right', $i++ * 80);?>>
                                        <a class="link-social" href="<?php echo esc_url( $link ); ?>" target="_blank">
                                            <?php echo $icon; ?>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-12 p-relative" <?php animation('fade-in-bottom', 500);?>>
                    <?php 
                        if (has_nav_menu('main-footer')) {
                            wp_nav_menu(array(
                                'theme_location' => 'main-footer',
                                'menu' => '',
                                'menu_class' => 'menu-left',
                                'menu_id' => '',
                                'container_class' => '',
                                'walker' => new Walker_Zetenta_Menu_Footer()
                            ));
                        }   
                    ?>
                    <?php if (function_exists('do_shortcode')) : ?>
                        <div class="lang-container">
                            <div class="lang-mobile">
                                <p>Lenguaje</p>
                            
                                    <?php echo do_shortcode('[weglot_switcher]'); ?>
                            
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12" <?php animation('scale-in-center', 600);?>>
                    <div class="copy">
                        <?php $year = date("Y");?>
                        <p>
                            <a href="<?php echo get_home_url();?>" aria-label="Tromen Argentina. Todos los derechos reservados">©<?php echo $year;?>. Tromen Argentina. Todos los derechos reservados</a>
                        </p>
                        <p>
                            <a href="https://www.zetenta.com/web/es/" target="_blank" aria-label="Sitio Creado Diseñado y Desarrollador por Zetenta" class="creditos">Diseño y desarrollo web: Zetenta</a>
                        </p>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</footer>
<?php get_template_part('template-parts/content', 'mini-cart'); ?>
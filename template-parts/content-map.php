<?php 
    $args = array(
        'post_type'      => 'puntos_de_ventas',
        'posts_per_page' => -1,
        'post_status'    => array( 'publish' ),
        'order' => 'asc'
    );
    $puntos = new WP_Query($args);

?>
<div class="modal-backdrop">
        <div class="modal-content container">
            <button type="button" class="modal-close">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 1L1 13" stroke="#222222" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M1 1L13 13" stroke="#222222" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="row">
                <div class="col-lg-4 col-md-5 col-12">
                    <div class="column-info">
                        <div class="points-header">
                            <h2>Ver puntos de venta <span>*Consultar stock</span></h2>
                        </div>
                        <div class="search__container">
                            <div class="search p-relative">
                                <input type="text" placeholder="Ingresá una ubicación..." id="buscadorMap">
                                <div class="search-suggest p-absolute">
                                    <ul id="search-suggest-container"></ul>
                                </div>
                            </div>
                            <div class="geolocation">
                                <div class="geolocation__buttons" >
                                    <button type="button" class="btn btn-icon btn-red-transparent-icon-black btn-sm" id="mapFlyLocation" style="min-width: auto;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19.5 12C19.5 17.018 14.0117 20.4027 12.4249 21.2764C12.1568 21.424 11.8432 21.424 11.5751 21.2764C9.98831 20.4027 4.5 17.018 4.5 12C4.5 7.5 8.13401 4.5 12 4.5C16 4.5 19.5 7.5 19.5 12Z" stroke="currentColor"/>
                                            <circle cx="12" cy="12" r="3.5" stroke="currentColor"/>
                                        </svg>
                                        mi ubicación
                                    <button type="button" class="btn btn-red-black btn-sm " id="openModalVentas">
                                        venta online
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="list__container">
                           <p class="msg-map d-none">Se encontraron puntos de ventas cercanos.</p>
                           <p class="error-msg-map d-none">No hay punto de ventas cercanos.</p>
                            <ul class="list" id="listContainer"></ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="map-container">
                        <div id="map-single" style="height: 100%; z-index: 22;"></div>
                    </div>
                    <div class="ventas-backdrop">
                        <div class="ventas-online">
                            <div class="header" id="closeModalVentas">
                                <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 7L0.646447 6.64645L0.292893 7L0.646447 7.35355L1 7ZM16 7.5C16.2761 7.5 16.5 7.27614 16.5 7C16.5 6.72386 16.2761 6.5 16 6.5V7.5ZM6.64645 0.646447L0.646447 6.64645L1.35355 7.35355L7.35355 1.35355L6.64645 0.646447ZM0.646447 7.35355L6.64645 13.3536L7.35355 12.6464L1.35355 6.64645L0.646447 7.35355ZM1 7.5H16V6.5H1V7.5Z" fill="#222222"/>
                                </svg>
                                <h2>Puntos de venta online</h2>
                            </div>
                            <div class="row">
                                <?php if ($puntos->have_posts()) : ?>
                                    <?php while ($puntos->have_posts()) : $puntos->the_post(); ?>
                                    <?php 
                                        $ID = get_the_id();
                                        $imagen = get_field( 'imagen', $ID ); 
                                        $link = get_field( 'link', $ID );
                                    ?>
                                        <div class="col-xs-3 col-6">
                                            <a class="card" href="<?php echo esc_url( $link );?>" target="_blank" aria-label="Distribuidor <?php echo the_title();?>">
                                                <?php insert_image($imagen, 1024) ?>
                                            </a>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btnMap__container">   
                    <button type="button" class="btn btn-icon btn-red-icon" id="btnMap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 32 22" fill="none">
                            <rect x="4" y="4.29999" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
                            <rect x="4" y="14.3" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
                            <rect x="14" y="4.29999" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
                            <rect x="14" y="14.3" width="6" height="6" rx="1" stroke="currentColor" stroke-linecap="round"/>
                        </svg>
                        <span>Lista</span>
                    </button>
                </div>
            </div>
        </div>
</div>

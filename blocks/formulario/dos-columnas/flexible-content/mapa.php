<?php 
    $direccion = get_sub_field( 'direccion' );
    $ciudad = get_sub_field( 'ciudad' );
    $zoom = get_sub_field( 'zoom' );

?>

<div class="col-sm-6 col-12 p-relative">
    <a href='http://mapswebsite.net/es' style="opacity:0; position:absolute;z-index:-1;">mapswebsite.net/es</a>
    <div class="map" <?php animation('fade-in-bottom', 500);?>>

        <iframe width="100%" height="100%" loading="lazy" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
            id="gmap_canvas"
            src="https://maps.google.com/maps?width=520&amp;height=400&amp;hl=en&amp;q=<?php echo $direccion;?>%20<?php echo $ciudad;?>+()&amp;t=&amp;z=<?php echo $zoom;?>&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>

        <script type='text/javascript'
            src='https://embedmaps.com/google-maps-authorization/script.js?id=a6263daf3570b3b8be511a25b28db265648b612c'>
        </script>
    </div>
</div>



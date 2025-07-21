<?php $bordes = get_sub_field( 'bordes' ); ?>
<div class="col-sm-6 col-12 d-flex justify-center align-items-center p-relative col-image" <?php animation('fade-in-bottom', 500);?>> 
    <div class="image <?php echo strtolower($bordes);?>">
        <?php insert_image(get_sub_field( 'imagen' ), 1024); ?>
    </div>
</div>


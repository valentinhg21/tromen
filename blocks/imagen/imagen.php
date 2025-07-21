

    <?php 
        $bg = get_field('imagen');
        $texto = get_field('texto');
    ?>


<div class="block-imagen blog">
    <div class="container-sm">
        <figure class="image">
            <?php echo insert_image($bg, 1024); ?>
            <?php if($texto): ?>
                <figcaption>
                    <?php echo $texto;  ?>
                </figcaption>
            <?php endif; ?>
        </figure>
    </div>
</div>



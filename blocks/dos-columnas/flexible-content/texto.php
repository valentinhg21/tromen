<div class="col-sm-6 col-12 d-flex justify-center align-items-center p-relative col-texto" <?php animation('fade-in-bottom', 700);?>>
        <div class="content">
            <?php if ( $titulo = get_sub_field( 'titulo' ) ) : ?>
                <h2 class="title"><?php echo $titulo; ?></h2>
            <?php endif; ?>
            <div class="content-text">
                <?php if ( $texto = get_sub_field( 'texto' ) ) : ?>
                    <?php echo $texto; ?>
                <?php endif; ?>
            </div>

        </div>
   
</div>


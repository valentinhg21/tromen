
<?php 
$requerid = get_sub_field( 'obligatorio' ) ;
$nombre = get_sub_field( 'nombre' );
?>

<div class="field-container">
        <?php if ($nombre) : ?>
            <?php $slug = sanitizeString($nombre); ?>
            <label data-label="<?php echo $slug;?>">
                <?php echo esc_html( $nombre ); ?><?php echo $requerid ? '*' : '';?>
                <?php echo $requerid ? '<span class="required-error"></span>' : '';?>
            </label>
        <?php endif; ?>
        <div class="field-check"> 
            <?php if ( have_rows( 'check' ) ) : ?>
                <?php while ( have_rows( 'check' ) ) :
                    the_row(); ?>
                    <?php if ( $nombre_campo = get_sub_field( 'nombre_campo' ) ) : ?>
                        <label class="checkbox-container">
                            <?php $nombre_slug = sanitizeString($nombre_campo) ?>
                            <?php echo esc_html( $nombre_campo ); ?>
                            <input type="checkbox" data-type="checkbox" data-nombre="<?php echo $nombre_campo; ?>">
                            <span class="checkmark"></span>
                        </label>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
</div>
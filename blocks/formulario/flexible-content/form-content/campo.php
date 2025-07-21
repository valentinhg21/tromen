<?php 
    $requerid = get_sub_field( 'obligatorio' ) ;
    $id = get_sub_field( 'id' );

    $envialoSimpleHTML = $id ? " data-envialosimple-custom-id=$id" : "";
?>

<div class="field-container">
    <?php if ( get_sub_field( 'tipo' ) == "Texto" ) : ?>
        <?php $nombre = get_sub_field( 'nombre' ); ?>
        <?php $placeholder = get_sub_field( 'placeholder' ); ?>
        <?php $slug = sanitizeString($nombre); ?>
        <?php if ($nombre) : ?>
            <label for="<?php echo $slug;?>">
                <?php echo esc_html( $nombre ); ?><?php echo $requerid ? '*' : '';?>
                <?php echo $requerid ? '<span class="required-error"></span>' : '';?>
            </label>
        <?php endif; ?>
        <input data-requerid = "<?php echo $requerid;?>" type="text" placeholder="<?php echo $placeholder;?>" data-type="text" data-label="<?php echo $slug;?>" id="<?php echo $slug;?>"<?php echo $envialoSimpleHTML;?>>
    <?php endif; ?>
    <?php if ( get_sub_field( 'tipo' ) == "Fecha" ) : ?>
        <?php $nombre = get_sub_field( 'nombre' ); ?>
        <?php $placeholder = get_sub_field( 'placeholder' ); ?>
        <?php $slug = sanitizeString($nombre); ?>
        <?php if ($nombre) : ?>
            <label for="<?php echo $slug;?>">
                <?php echo esc_html( $nombre ); ?><?php echo $requerid ? '*' : '';?>
                <?php echo $requerid ? '<span class="required-error"></span>' : '';?>
            </label>
        <?php endif; ?>
        <input data-requerid = "<?php echo $requerid;?>" type="date" placeholder="<?php echo $placeholder;?>" data-type="date" data-label="<?php echo $slug;?>" value="" id="<?php echo $slug;?>"<?php echo $envialoSimpleHTML;?>
        >
    <?php endif; ?>
    <?php if ( get_sub_field( 'tipo' ) == "Email" ) : ?>
        <?php $nombre = get_sub_field( 'nombre' ); ?>
        <?php $placeholder = get_sub_field( 'placeholder' ); ?>
        <?php $slug = sanitizeString($nombre); ?>
        <?php if ($nombre) : ?>
            <label for="<?php echo $slug;?>">
                <?php echo esc_html( $nombre ); ?><?php echo $requerid ? '*' : '';?>
                <?php echo '<span class="required-error"></span>';?>
            </label>
        <?php endif; ?>
        <input data-requerid = "<?php echo $requerid;?>" type="email" placeholder="<?php echo $placeholder;?>" data-type="email" data-label="<?php echo $slug;?>" value="" id="<?php echo $slug;?>"<?php echo $envialoSimpleHTML;?>
        >
    <?php endif; ?>
    <?php if ( get_sub_field( 'tipo' ) == "Numero" ) : ?>
        <?php $nombre = get_sub_field( 'nombre' ); ?>
        <?php $placeholder = get_sub_field( 'placeholder' ); ?>
        <?php $slug = sanitizeString($nombre); ?>
        <?php if ($nombre) : ?>
            <label for="<?php echo $slug;?>">
                <?php echo esc_html( $nombre ); ?><?php echo $requerid ? '*' : '';?>
                <?php echo $requerid ? '<span class="required-error"></span>' : '';?>
            </label>
        <?php endif; ?>
        <input type="number" placeholder="<?php echo $placeholder;?>" data-type="number" data-label="<?php echo $slug;?>" id="<?php echo $slug;?>"<?php echo $envialoSimpleHTML;?>
        >
    <?php endif; ?>
    <?php if ( get_sub_field( 'tipo' ) == "Mensaje" ) : ?>
        <?php $nombre = get_sub_field( 'nombre' ); ?>
        <?php $caracteres = get_sub_field( 'caracteres' ); ?>
        <?php $placeholder = get_sub_field( 'placeholder' ); ?>
        <?php $slug = sanitizeString($nombre); ?>
        <div class="field-textarea">
            <?php if ($nombre) : ?>
                <label for="<?php echo $slug;?>">
                    <?php echo esc_html( $nombre ); ?><?php echo $requerid ? '*' : '';?>
                    <?php echo $requerid ? '<span class="required-error"></span>' : '';?>
                </label>
            <?php endif; ?>
            <?php if($caracteres != 0): ?>
                <textarea data-requerid = "<?php echo $requerid;?>" name="" maxlength="200" data-count="yes" placeholder="<?php echo $placeholder;?>" data-type="message" data-label="<?php echo $slug;?>" id="<?php echo $slug;?>" cols="30" rows="8"></textarea>
                <p class="count-max">*Caracteres restantes <span class="max"><?php echo $caracteres; ?></span></p>
                <?php else: ?>
                    <textarea data-requerid = "<?php echo $requerid;?>" name="" data-count="no" placeholder="<?php echo $placeholder;?>" data-type="message" data-label="<?php echo $slug;?>" id="<?php echo $slug;?>" cols="30" rows="8"<?php echo $envialoSimpleHTML;?>></textarea>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
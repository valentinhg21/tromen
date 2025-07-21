<?php 
$requerid = get_sub_field( 'obligatorio' ) ;
$nombre = get_sub_field( 'nombre' );
$placeholder = get_sub_field( 'placeholder' );
$slug = sanitizeString($nombre);
$lista_de_modelos = get_sub_field( 'lista_de_modelos' );
$id = get_sub_field( 'id' );
$envialoSimpleHTML = $id ? " data-envialosimple-custom-id=$id" : "";
?>

<div class="field-container">
    <?php if ($nombre) : ?>
        <?php $slug = sanitizeString($nombre); ?>
        <label for="<?php echo $slug;?>">
            <?php echo esc_html( $nombre ); ?><?php echo $requerid ? '*' : '';?>
            <?php echo $requerid ? '<span class="required-error"></span>' : '';?>
        </label>
    <?php endif; ?>
    <div class="field-container-input field-container-input-search">
        <div class="field-container-input__icon">
            <i class="fa-solid fa-magnifying-glass"></i>
            <i class="fa-solid fa-x"></i>
        </div>
        <input data-requerid = "<?php echo $requerid;?>" type="text" placeholder="<?php echo $placeholder;?>" data-type="select-products" data-label="<?php echo $slug;?>" id="<?php echo $slug;?>"<?php echo $envialoSimpleHTML;?>>
        <div class="list-select">
            <ul>    
                <?php if ($lista_de_modelos): ?>
                    <?php $opciones = explode(',', $lista_de_modelos); ?>
                    <?php foreach ($opciones as $opcion): ?>
                        <li class="options-list-select"><p><?php echo trim($opcion); ?></p></li>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <li class="options-list-select"><p>No se encontró el producto.</p></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
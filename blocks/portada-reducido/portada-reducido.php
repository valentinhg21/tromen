<?php
    $texto = get_field( 'Texto' );
    $texto_de_fondo = get_field( 'texto_de_fondo' );
    $titulo = get_field( 'titulo' );
    if(isset($block['className'])){
        $classStyle = $block['className'] !== 'is-style-default' ? 'bg-gray' : '';
    }else{
        $classStyle = '';
    }
        
?>
<div class="block-portada-reducido <?php echo $classStyle?>">
    <div class="text-bg" <?php animation('fade-in-bottom', 250 );?>>
        <?php insert_acf($texto_de_fondo, 'h1'); ?>
    </div>
    <div class="container">
        <div class="content" <?php animation('fade-in-bottom', 250 );?>>
            <?php insert_acf($titulo, 'h1'); ?>
            <?php insert_acf($texto, 'p'); ?>
        </div>
    </div>
</div>

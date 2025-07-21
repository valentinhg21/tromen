<?php 
    $titulo = get_field('titulo');
    $cta = get_field('cta')

?>

<div class="block-banner bg-gray">
    <div class="container">
        <div class="content" <?php animation('fade-in-bottom', 250 );?>>
            <?php insert_acf($titulo, 'p') ?>
            <?php insert_button($cta, "3", "btn-black-to-red") ?>
        </div>
    </div>
</div>
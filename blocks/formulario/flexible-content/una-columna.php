<?php 
$texto = get_field( 'texto' );
$current_page_slug = get_post_field( 'post_name', get_queried_object_id() );
$id = get_field( 'id' );
$asunto = get_field('asunto');
$id_de_la_lista = get_field( 'id_de_la_lista' );
$activar_spread = get_field( 'activar' );
$spreadsheet_id = get_field( 'spreadsheet_id' );
?>

<div class="row">
    <div class="col-12" <?php animation('fade-in-bottom', 800); ?>>
        <form 
        class="form form-dark <?php echo $current_page_slug;?>" 
        data-destinatario="<?php echo get_field( 'destinatario' );?>" 
        data-asunto="<?php echo $asunto?>"
        data-envialosimple="<?php echo $id_de_la_lista;?>"
        data-activegs = "<?php echo $activar_spread ? 'on' : 'off';?>"
        data-gsid = <?php echo $spreadsheet_id;?>
        >
            <?php
            $flexibleContentPath = dirname(__FILE__) . '/form-content/';
            if ( have_rows( 'formulario_content' ) ) :
                while ( have_rows( 'formulario_content' ) ) :
                the_row();
                $layout = get_row_layout();
                $file = ( $flexibleContentPath . str_replace( '_', '-', $layout) . '.php' );
                if ( file_exists( $file ) ) {
                    include( $file );
                }
                endwhile;
            endif; ?>
        </form>
    </div>
</div>

<div class="popup">
    <div class="popup-card">
        <div class="popup-card-close">
            <i class="fa-solid fa-x popup-close"></i>
        </div>
        <div class="popup-content d-flex justify-center align-items-center flex-column">
        </div>
        <div class="button__container d-flex justify-center align-items-center">
            <button type="button" class="btn btn-black-to-red popup-close">
                Cerrar
            </button>
        </div>
    </div>
</div>


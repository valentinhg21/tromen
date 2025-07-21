<?php 
    $title = get_option('newsletter_title');
    $dest = get_option('newsletter_destinatario');
    $email = get_option('newsletter_email');
    $idEnvialo = get_option('newsletter_id');
    $lang = function_exists('weglot_get_current_language') ? weglot_get_current_language() : 'es';

?>

<div class="block-news bg-red" data-lang="<?php echo $lang;?>">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-12">
                <div class="content" <?php animation('fade-in-bottom', 650);?>>
                    <h2><?php echo $title; ?></h2>
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <div class="form-news" <?php animation('scale-in-center', 650);?>>
                    <form class="form-newsletter form-dark">
                        <div class="field-container">
                            <input data-destinatario="<?php echo $dest; ?>" placeholder="<?php echo $email; ?> " type="email" id="email-newsletter" data-envialosimple="<?php echo wp_kses_post($idEnvialo)?>">
                        </div>
                        <div class="button__container  d-flex justify-center align-items-center">
                            <button type="submit" class="btn btn-md btn-black" id="submit-news">
                                SUSCRIBIRME
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
</div>
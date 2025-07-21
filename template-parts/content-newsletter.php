<?php 
    $title = get_option('newsletter_title');
    $dest = get_option('newsletter_destinatario');
    $email = get_option('newsletter_email');

?>

<div class="block-news bg-red">
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
                            <input placeholder="<?php echo $email; ?> " type="email" id="email">
                       
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
</div>
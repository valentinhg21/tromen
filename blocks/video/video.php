
<?php 

$video = get_field( 'video' );
$video_url = get_field( 'video_url' );

$seleccionar = get_field( 'seleccionar_tipo' );
$titulo = get_field( 'titulo' );
$controles = get_field( 'controles' );
$muted = $controles ? '' : 'muted';
?>

<div class="block-video" >
    <div class="hidden">
        <div class="container p-relative container-sm-8">
            <div class="video" <?php animation('fade-in', 200);?>>
                <?php if($seleccionar): ?>
                    <video class="video-plyr" <?php echo $muted;?> data-controls= <?php echo $controles ? 'true' : 'false' ?> width="100%" height="100%" playsinline>
                        <source src="<?php echo esc_url( $video['url'] ); ?>" type="video/mp4" >
                            Tu navegador no soporta el elemento de video.
                    </video>
                    <?php else: ?>
                        <div class="video-frame">                    
                            <div class="plyr__video-embed">
                                <iframe
                                    src="<?php echo idYoutube($video_url);?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                                    allowfullscreen
                                    allowtransparency
                                    allow="autoplay"
                                ></iframe>
                            </div>
                        </div>
                <?php endif; ?>
            </div>
            <?php if ( $titulo) : ?>
                <div class="content" <?php animation('fade-in-left', 400);?>>
                        <?php insert_acf($titulo, 'h2'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
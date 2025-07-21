
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="google-site-verification" content="9MNDb5ioK748C9XGvVU-455QhFGEtXe-Xkx-RdDIb4k" />
  <title>
    <?php
      if (is_front_page() || is_home()) {
        
        echo get_bloginfo('name') . ' – ' . get_bloginfo('description');
      } else {
        wp_title('', true, 'right');
      }
    ?>
  </title>
  <?php wp_head(); ?>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-MCX3Z7WN');</script>
  <!-- End Google Tag Manager -->

</head>



<?php 
    global $header_type;
    if (!isset($header_type)) {
        $header_type = ''; // Valor por defecto
    } 
?>

<body <?php body_class('theme');?>">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MCX3Z7WN"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <?php if (function_exists('weglot_get_current_language')) : ?>
    <input type="hidden" name="current-lang" value="<?php echo weglot_get_current_language() ?: 'es'; ?>" id="current-lang-tromen">
<?php else : ?>
    <input type="hidden" name="current-lang" value="es" id="current-lang-tromen">
<?php endif; ?>
  <?php if(is_front_page()): ?>
      <input type="hidden" name="title-page" value="<?php echo get_the_title(); ?>" id="title-page">
      <?php require_once('layout/header.php');?>
    <?php else: ?>
      <?php if($header_type === 'default'): ?>
          <?php require_once('layout/header.php');?>
        <?php else: ?>
          <?php require_once('layout/header-shop.php');?>

      <?php endif; ?>
  <?php endif; ?>
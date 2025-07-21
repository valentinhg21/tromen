
<?php 
  $flexibleContentPath = dirname(__FILE__) . '/flexible-content/';
  $classNamesLayouts = []; 

  if (have_rows('dos_columnas')):
    while (have_rows('dos_columnas')):
      the_row();
      $layout = get_row_layout();
      $classNamesLayouts[] = $layout;

    endwhile;
  endif;

  if(isset($block['className'])){
    $classStyle = $block['className'];
  }else{
      $classStyle = 'is-style-default';
  }


?>


<div class="block-dos-columnas <?php echo $classStyle === 'is-style-gris' ? 'bg-gray' : 'bg-white'; ?> <?php echo implode('-', $classNamesLayouts); ?>" id="">
  <div class="container">
    <div class="row">
      <?php
        if (have_rows('dos_columnas')):
          while (have_rows('dos_columnas')):
            the_row();
            $layout = get_row_layout();
              $file = ($flexibleContentPath . str_replace('_', '-', $layout) . '.php');
              if (file_exists($file)) {
                include($file);
              }
          endwhile;
        endif; ?>
    </div>
  </div>
</div>
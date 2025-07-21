
<?php 
    $texto = get_field('texto');
    if(isset($block['className'])){
        $classStyle = $block['className'];
      }else{
          $classStyle = 'is-style-default';
    }
?>
<div class="block-texto blog <?php echo $classStyle === 'is-style-default' ? 'bg-gray' : 'bg-white'; ?>">
    <div class="container-sm">
        <?php echo $texto; ?>
    </div>
</div>

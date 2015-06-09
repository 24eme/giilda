<?php
$liClass = '';
if($actif == $num_etape+1) $liClass = 'active';
  else
  {
      if(($vrac->etape > $num_etape) && (($num_etape+1)!=$actif)) $liClass = 'passe';
  }

 $href ='';
  if($num_etape == 0 && $vrac->etape == 0 && isset($urlsoussigne) && $urlsoussigne) $href = 'href="'.$urlsoussigne.'"';
  else if($vrac->etape >= $num_etape) $href = 'href="'.url_for($url_etape,$vrac).'"';
?>

<li class="<?php echo $liClass; ?>">
    <?php if($liClass != 'active'): ?>
    <a <?php echo $href; ?>>
        <?php echo $num_etape+1;?>.
        <?php echo $label; ?> 
    </a>    
    <?php else: ?>
      <strong class="lead text-info"><?php echo $num_etape+1;?>.
        <?php echo $label; ?></strong>
    <?php endif; ?>
</li>


   

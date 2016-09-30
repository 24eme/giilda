<?php
$liClass = '';
if($actif == $num_etape+1) $liClass = 'active';
  else
  {
      if(($vrac->etape > $num_etape)) $liClass = 'passe';
  }

 $href ='';
  if($num_etape == 0 && $vrac->etape == 0 && isset($urlsoussigne) && $urlsoussigne) $href = $urlsoussigne;
  else if($vrac->etape >= $num_etape) $href = url_for($url_etape,$vrac);
?>

<li class="<?php if($liClass == 'active'): ?>active<?php elseif ($liClass == 'passe'): ?>visited<?php endif; ?> <?php if ($liClass != 'active' && $liClass != 'passe'): ?>disabled<?php endif; ?>">
    <a href="<?php echo $href ?>">
        <?php echo $label; ?> 
    </a>
</li>

<?php
$liClass = '';
if($actif == $num_etape) $liClass = 'actif';
  else
  {
      if(($revendication_etape > $num_etape) && (($num_etape+1)!=$actif)) $liClass = 'passe';
  }

 $href ='';
  if($num_etape == 0 && $revendication_etape == 0) $href = 'href="'.url_for('revendication_upload').'"';
  else if($revendication_etape >= $num_etape) $href = 'href="'.url_for($url_etape,$revendication).'"';
?>

<li class="<?php echo $liClass; ?>">
    <a <?php echo $href; ?>>
        <?php if($actif == $num_etape+1) echo '<strong>'; ?>
        <span <?php echo ($revendication_etape < $num_etape)? 'style="cursor: default;"' : '' ?> ><?php echo $num_etape+1;?> </span>
        <?php echo $label; ?> 
        <?php if($actif == $num_etape+1) echo '</strong>'; ?>
    </a>    
</li>


   

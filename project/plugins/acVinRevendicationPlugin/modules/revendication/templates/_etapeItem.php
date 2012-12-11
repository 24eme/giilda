<?php
$liClass = '';
if($actif == $num_etape) {
  $liClass = 'actif';
} elseif(($revendication_etape > $num_etape) && (($num_etape+1)!=$actif)) {
  $liClass = 'passe';
}

?>

<li class="<?php echo $liClass; ?>">
    <a href="<?php echo url_for($url_etape, $revendication) ?>">
        <?php if($actif == $num_etape+1) echo '<strong>'; ?>
        <span <?php echo ($revendication_etape < $num_etape)? 'style="cursor: default;"' : '' ?> ><?php echo $num_etape+1;?> </span>
        <?php echo $label; ?> 
        <?php if($actif == $num_etape+1) echo '</strong>'; ?>
    </a>    
</li>


   

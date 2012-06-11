<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section id="conditions_recapitulatif_typeContrat">
   <span>Type de contrat&nbsp;:</span>
   <span><?php echo $vrac->type_contrat; ?></span>
<section id="conditions_recapitulatif_isvariable">
   <span>prix variable ?</span>
   <span><?php echo ($vrac->prix_variable) ? 'Oui' : 'Non';
echo ($vrac->prix_variable)? ' ('.$vrac->part_variable.'%)' : '';
?>
</span>
</section>
   <?php 
   if($vrac->prix_variable)
       {
   ?>
<section id="conditions_recapitulatif_variable">    
  <span>Taux variable&nbsp;:</span>
  <span><?php echo $vrac->taux_variation;?></span>
</section>
   <?php   
   }
   ?>
<section id="conditions_recapitulatif_cvo">
  <span>CVO&nbsp;: </span>
  <span><?php 
  echo  $vrac->cvo_nature.' ('. $vrac->cvo_repartition.')';
?></span>
</section>
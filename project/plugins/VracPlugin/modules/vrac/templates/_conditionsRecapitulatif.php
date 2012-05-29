<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section id="conditions_recapitulatif_typeContrat">
   <span>Type de contrat&nbsp;:</span>
   <span><?php echo $form['type_contrat']; ?></span>
<section id="conditions_recapitulatif_isvariable">
   <span>prix variable ?</span>
   <span><?php echo ($form['prix_variable']) ? 'Oui' : 'Non';
echo ($form['prix_variable'])? ' ('.$form['part_variable'].'%)' : '';
?>
</span>
</section>
<section id="conditions_recapitulatif_variable">    
  <span>Taux variable&nbsp;:</span>
  <span><?php 
            echo $form['taux_variation'];
?></span>
</section>
<section id="conditions_recapitulatif_cvo">
  <span>CVO&nbsp;: </span>
  <span><?php 
  echo $form['cvo_nature'].' ('.$form['cvo_repartition'].')';
?></span>
</section>
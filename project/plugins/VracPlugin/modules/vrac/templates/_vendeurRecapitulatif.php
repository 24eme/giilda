<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section id="vendeur_recapitulatif_vendeur">
   <span>Vendeur&nbsp;:</span>
   <span><?php echo $form->getVendeurObject()->getNom(); ?></span>
</section>
<section id="vendeur_recapitulatif_acheteur">
   <span>Acheteur&nbsp;:</span>
   <span><?php echo $form->getAcheteurObject()->getNom(); ?></span>
</section>
<section id="vendeur_recapitulatif_mandataire">
   <span>Mandataire&nbsp;:</span>
   <span><?php echo $form->getMandataireObject()->getNom();?></span>
</section>
        
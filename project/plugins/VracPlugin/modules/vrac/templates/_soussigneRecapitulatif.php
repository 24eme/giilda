<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section id="soussigne_recapitulatif_vendeur">
   <span>Vendeur&nbsp;:</span>
   <span><?php echo $vrac->getVendeurObject()->getNom(); ?></span>
</section>
<section id="soussigne_recapitulatif_acheteur">
   <span>Acheteur&nbsp;:</span>
   <span><?php echo $vrac->getAcheteurObject()->getNom(); ?></span>
</section>
<section id="soussigne_recapitulatif_mandataire">
    <?php
    if($vrac->mandataire_exist)
    {
    ?>
   <span>Mandataire&nbsp;:</span>
   <span><?php echo $vrac->getMandataireObject()->getNom();?></span>
    <?php
    }
    else
    {
    ?>
   <span>Ce contrat ne possède pas de mandataire</span>
    <?php
    }
    ?>

</section>
        
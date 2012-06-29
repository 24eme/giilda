<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="bloc_form">
    <div id="soussigne_recapitulatif_vendeur" class="ligne_form">
        <label>Vendeur :</label>
        <span><?php echo $vrac->getVendeurObject()->getNom(); ?></span>
    </div>
    <div id="soussigne_recapitulatif_acheteur" class="ligne_form ligne_form_alt">
        <label>Acheteur :</label>
        <span><?php echo $vrac->getAcheteurObject()->getNom(); ?></span>
    </div>
    <div id="soussigne_recapitulatif_mandataire" class="ligne_form">
        <?php if($vrac->mandataire_identifiant!=null && $vrac->mandataire_exist){ ?>
            <label>Mandataire&nbsp;:</label>
            <span><?php echo $vrac->getMandataireObject()->getNom();?></span>
        <?php }else{ ?>
            Ce contrat ne possède pas de mandataire
        <?php } ?>
    </div>
</div>
        
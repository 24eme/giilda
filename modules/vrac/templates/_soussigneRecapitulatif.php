<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="bloc_form">
    <div id="soussigne_recapitulatif_vendeur" class="ligne_form">
        <label>Vendeur :</label>
        <span><a href="<?php echo url_for('vrac/recherche?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $vrac->vendeur_identifiant)) ?>"><?php echo $vrac->getVendeurObject()->getNom(); ?></a></span>
    </div>
    <div id="soussigne_recapitulatif_acheteur" class="ligne_form ligne_form_alt">
        <label>Acheteur :</label>
        <span><a href="<?php echo url_for('vrac/recherche?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $vrac->acheteur_identifiant)) ?>"><?php echo $vrac->getAcheteurObject()->getNom(); ?></a></span>
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
        
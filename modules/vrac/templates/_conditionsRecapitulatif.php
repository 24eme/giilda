<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use_helper('Vrac');
use_helper('Float');
use_helper('Date');
?>
<div class="bloc_form bloc_form_condensed">
    <div id="conditions_recapitulatif_typeContrat" class="ligne_form">
        <label>Type de contrat&nbsp;:</label>
        <span><?php echo $vrac->type_contrat; ?></span>
    </div>
    <div id="conditions_recapitulatif_isvariable" class="ligne_form ligne_form_alt">
        <label>Prix variable :</label>
        <span><?php echo ($vrac->prix_variable) ? 'Oui' : 'Non';
        echo ($vrac->prix_variable)? ' ('.$vrac->part_variable.'%)' : '';
        ?>
        </span>
    </div>
    
    <div id="conditions_cvo_nature" class="ligne_form">
        <label>Nature de la transaction&nbsp;: </label>
        <span><?php echo $vrac->cvo_nature ?></span>
    </div>

    <div id="conditions_recapitulatif_cvo" class="ligne_form ligne_form_alt">
        <label>Repartition de la CVO&nbsp;: </label>
        <span><?php echo VracClient::$cvo_repartition[$vrac->cvo_repartition] ?></span>
    </div>
    
    <?php if($vrac->date_signature) : ?>
    <div id="conditions_recapitulatif_date_signature" class="ligne_form">
        <label>Date de signature&nbsp;: </label>
        <span><?php echo $vrac->date_signature; ?></span>
    </div>
    <?php endif; ?> 
    
    <?php if($vrac->date_campagne) : ?>
    <div id="conditions_recapitulatif_date_saisie" class="ligne_form ligne_form_alt">
        <label>Date de campagne (statistique)&nbsp;: </label>
        <span><?php echo $vrac->date_campagne; ?></span>
    </div>
    <?php endif; ?> 
    
    <?php if($vrac->valide->date_saisie) : ?>
    <div id="conditions_recapitulatif_date_saisie" class="ligne_form">
        <label>Date de saisie&nbsp;: </label>
        <span><?php echo format_date($vrac->valide->date_saisie, 'dd/MM/yyyy'); ?></span>
    </div>
    <?php endif; ?> 
    
    
    <div id="conditions_recapitulatif_commentaire" class="ligne_form <?php echo ($vrac->valide->date_saisie)? 'ligne_form_alt' : '' ;?>">
        <label>Commentaires&nbsp;: </label>
        <pre class="commentaire"><?php echo $vrac->commentaire;?></pre>
    </div>
    
</div>

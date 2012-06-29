<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="bloc_form">
    <div id="conditions_recapitulatif_typeContrat" class="ligne_form">
        <label>Type de contrat&nbsp;:</label>
        <span><?php echo $vrac->type_contrat; ?></span>
    </div>
    <div id="conditions_recapitulatif_isvariable" class="ligne_form ligne_form_alt">
        <label>prix variable ?</label>
        <span><?php echo ($vrac->prix_variable) ? 'Oui' : 'Non';
        echo ($vrac->prix_variable)? ' ('.$vrac->part_variable.'%)' : '';
        ?>
        </span>
    </div>

    <div id="conditions_recapitulatif_cvo" class="ligne_form">
        <label>CVO&nbsp;: </label>
        <span><?php 
        echo  $vrac->cvo_nature.' ('. $vrac->cvo_repartition.')';
        ?></span>
    </div>
</div>
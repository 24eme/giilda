<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use_helper('Vrac');
use_helper('Float');
?>
<div class="bloc_form">
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
    
    <div id="conditions_recapitulatif_commentaire" class="ligne_form">
        <label>Commentaires&nbsp;: </label>
             <span style="width: 100%; height: 100%;"><?php 
            echo $vrac->commentaire;
            ?></span>
        </textarea>
    </div>
    
</div>

<?php
use_helper('Display');
?>
<?php $asterisk = ($isTeledeclarationMode)? '' : '*'; ?>
<div class="mandataire_infos bloc_form bloc_form_condensed">
    <div class="ligne_form">
        <span>
            <label>Nom du courtier :</label>
            <?php display_field($mandataire,'nom'); ?>
        </span>
    </div>
    <div class="ligne_form ligne_form_alt">    
        <span>
            <label>N° carte professionnelle</label>
            <?php display_field($mandataire,'carte_pro'); ?>
        </span>
    </div>
    <div class="ligne_form">       
        <span>
            <label>Adresse</label>
            <?php  display_field($mandataire,'siege/adresse');  ?>
        </span>
    </div>
    <div class="ligne_form ligne_form_alt"> 
        <span>
            <label>CP</label>
            <?php  display_field($mandataire,'siege/code_postal');  ?>
        </span>
    </div>
    <div class="ligne_form ">      
        <span>
            <label>Ville</label>
            <?php  display_field($mandataire,'siege/commune');  ?>
        </span>
    </div>
</div>
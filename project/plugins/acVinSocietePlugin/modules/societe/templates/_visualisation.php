<?php
use_helper('Display');
?>
<div class="section_label_maj">
    <div class="section_label_maj">
        <label>
            Identifiant : 
        </label>
        <div class="bloc_form">
            <?php display_field($societe, 'identifiant');  ?>
        </div>
    </div>
    <div class="section_label_maj">
        <label>
            Raison sociale :
        </label>
        <div class="bloc_form">
            <?php display_field($societe, 'raison_sociale'); ?>
        </div>
    </div>
    <div class="section_label_maj">
        <label>
            Siret :
        </label>
        <div class="bloc_form">
            <?php display_field($societe, 'siret'); ?>
        </div>
    </div>
    <div class="section_label_maj">
        <label>
            Téléphone : 
        </label>
        <div class="bloc_form">
            <?php display_field($societe, 'telephone'); ?>
        </div>
    </div>
</div>

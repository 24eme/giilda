<div id="etablissement_societe_modification" class="section_label_maj">
    <h2>Détail de l'établissement</h2>
    <?php
    echo $etablissementSocieteForm->renderHiddenFields();
    echo $etablissementSocieteForm->renderGlobalErrors();
    ?>
    <div class="section_label_maj" id="famille">
        <?php echo $etablissementSocieteForm['famille']->renderError(); ?>
        <?php echo $etablissementSocieteForm['famille']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['famille']->render(); ?>
    </div>
    <div class="section_label_maj" id="nom">
        <?php echo $etablissementSocieteForm['nom']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['nom']->render(); ?>
        <?php echo $etablissementSocieteForm['nom']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="statut">
        <?php echo $etablissementSocieteForm['statut']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['statut']->render(); ?>
        <?php echo $etablissementSocieteForm['statut']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="cvi">
        <?php echo $etablissementSocieteForm['cvi']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['cvi']->render(); ?>
        <?php echo $etablissementSocieteForm['cvi']->renderError(); ?>
    </div>  
    <div class="section_label_maj" id="raisins_mouts">
        <?php echo $etablissementSocieteForm['raisins_mouts']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['raisins_mouts']->render(); ?>
        <?php echo $etablissementSocieteForm['raisins_mouts']->renderError(); ?>
    </div> 

    <div class="section_label_maj" id="exclusion_drm">
        <?php echo $etablissementSocieteForm['exclusion_drm']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['exclusion_drm']->render(); ?>
        <?php echo $etablissementSocieteForm['exclusion_drm']->renderError(); ?>
    </div>                 
    <div class="section_label_maj" id="relance_ds">
        <?php echo $etablissementSocieteForm['relance_ds']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['relance_ds']->render(); ?>
        <?php echo $etablissementSocieteForm['relance_ds']->renderError(); ?>
    </div>                
    <div class="section_label_maj" id="recette_locale">
        <?php echo $etablissementSocieteForm['recette_locale']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['recette_locale']->render(); ?>
        <?php echo $etablissementSocieteForm['recette_locale']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="region">
        <?php echo $etablissementSocieteForm['region']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['region']->render(); ?>
        <?php echo $etablissementSocieteForm['region']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="type_dr">
        <?php echo $etablissementSocieteForm['type_dr']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['type_dr']->render(); ?>
        <?php echo $etablissementSocieteForm['type_dr']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="type_liaison">
        <?php echo $etablissementSocieteForm['type_liaison']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['type_liaison']->render(); ?>
        <?php echo $etablissementSocieteForm['type_liaison']->renderError(); ?>
    </div>
    <?php foreach ($etablissementSocieteForm->getObject()->liaisons_operateurs as $key => $liaison_societe): ?>
        <div class="section_label_maj" id="liaisons_operateurs[<?php echo $key; ?>]">
            <?php echo $etablissementSocieteForm['liaisons_operateurs[' . $key . ']']->renderLabel(); ?>
            <?php echo $etablissementSocieteForm['liaisons_operateurs[' . $key . ']']->render(); ?>
            <?php echo $etablissementSocieteForm['liaisons_operateurs[' . $key . ']']->renderError(); ?>
        </div>
    <?php endforeach; ?>

    <div class="section_label_maj" id="site_fiche">
        <?php echo $etablissementSocieteForm['site_fiche']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['site_fiche']->render(); ?>
        <?php echo $etablissementSocieteForm['site_fiche']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="carte_pro">
        <?php echo $etablissementSocieteForm['carte_pro']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['carte_pro']->render(); ?>
        <?php echo $etablissementSocieteForm['carte_pro']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="no_accises">
        <?php echo $etablissementSocieteForm['no_accises']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['no_accises']->render(); ?>
        <?php echo $etablissementSocieteForm['no_accises']->renderError(); ?>
    </div>
    <div class="section_label_maj" id="commentaire">
        <?php echo $etablissementSocieteForm['commentaire']->renderLabel(); ?>
        <?php echo $etablissementSocieteForm['commentaire']->render(); ?>
        <?php echo $etablissementSocieteForm['commentaire']->renderError(); ?>
    </div>

</div>

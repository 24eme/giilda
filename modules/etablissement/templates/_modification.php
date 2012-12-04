<div id="detail_etablissement" class="form_section">
    <h2>Détail de l'établissement</h2>

    <div class="form_contenu">
        <?php
        echo $etablissementForm->renderHiddenFields();
        echo $etablissementForm->renderGlobalErrors();
        ?>
        <div class="form_ligne">
            <?php echo $etablissementForm['famille']->renderError(); ?>
            <label for="famille">
                <?php echo $etablissementForm['famille']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['famille']->render(); ?>
        </div>
        <div class="form_ligne">
            <label for="nom">
            <?php echo $etablissementForm['nom']->renderLabel(); ?>
            <?php echo $etablissementForm['nom']->render(); ?>
            <?php echo $etablissementForm['nom']->renderError(); ?>
        </div>
        <div class="form_ligne">
             <label for="statut">
            <?php echo $etablissementForm['statut']->renderLabel(); ?>
             </label>
            <?php echo $etablissementForm['statut']->render(); ?>
            <?php echo $etablissementForm['statut']->renderError(); ?>
        </div>
        <div class="form_ligne">
            <label for="famille">
                <?php echo $etablissementForm['raisins_mouts']->renderLabel(); ?>                
            </label>
            <?php echo $etablissementForm['raisins_mouts']->render(); ?>
            <?php echo $etablissementForm['raisins_mouts']->renderError(); ?>
        </div> 

        <div class="form_ligne">
            <label for="exclusion_drm">
            <?php echo $etablissementForm['exclusion_drm']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['exclusion_drm']->render(); ?>
            <?php echo $etablissementForm['exclusion_drm']->renderError(); ?>
        </div>                 
        <div class="form_ligne">
            <label for="relance_ds">
            <?php echo $etablissementForm['relance_ds']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['relance_ds']->render(); ?>
            <?php echo $etablissementForm['relance_ds']->renderError(); ?>
        </div>                
<!--        <div class="form_ligne">
            <label for="recette_locale">
            <?php //echo $etablissementForm['recette_locale']->renderLabel(); ?>
            </label>
            <?php //echo $etablissementForm['recette_locale']->render(); ?>
            <?php //echo $etablissementForm['recette_locale']->renderError(); ?>
        </div>-->
        <div class="form_ligne">
            <label for="region">
            <?php echo $etablissementForm['region']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['region']->render(); ?>
            <?php echo $etablissementForm['region']->renderError(); ?>
        </div>
        <div class="form_ligne">
            <label for="type_dr">
            <?php echo $etablissementForm['type_dr']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['type_dr']->render(); ?>
            <?php echo $etablissementForm['type_dr']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="type_liaison">
            <label for="type_liaison">
            <?php echo $etablissementForm['type_liaison']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['type_liaison']->render(); ?>
            <?php echo $etablissementForm['type_liaison']->renderError(); ?>
        </div>
        <?php foreach ($etablissementForm->getObject()->liaisons_operateurs as $key => $liaison_societe): ?>
            <div class="form_ligne">
                <label for='liaisons_operateurs[<?php echo $key; ?>]'>
                <?php echo $etablissementForm['liaisons_operateurs[' . $key . ']']->renderLabel(); ?>
                </label>               
 <?php echo $etablissementForm['liaisons_operateurs[' . $key . ']']->render(); ?>
                <?php echo $etablissementForm['liaisons_operateurs[' . $key . ']']->renderError(); ?>
            </div>
        <?php endforeach; ?>

         <div class="form_ligne">
            <label for="cvi">
            <?php echo $etablissementForm['cvi']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['cvi']->render(); ?>
            <?php echo $etablissementForm['cvi']->renderError(); ?>
        </div>
        <div class="form_ligne">
            <label for="site_fiche">
            <?php echo $etablissementForm['site_fiche']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['site_fiche']->render(); ?>
            <?php echo $etablissementForm['site_fiche']->renderError(); ?>
        </div>
        <div class="form_ligne">
            <label for="carte_pro">
            <?php echo $etablissementForm['carte_pro']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['carte_pro']->render(); ?>
            <?php echo $etablissementForm['carte_pro']->renderError(); ?>
        </div>
        <div class="form_ligne">
            <label for="no_accises">
            <?php echo $etablissementForm['no_accises']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['no_accises']->render(); ?>
            <?php echo $etablissementForm['no_accises']->renderError(); ?>
        </div>
        <div class="form_ligne">
            <label for="commentaire">
            <?php echo $etablissementForm['commentaire']->renderLabel(); ?>
            </label>
            <?php echo $etablissementForm['commentaire']->render(); ?>
            <?php echo $etablissementForm['commentaire']->renderError(); ?>
        </div>

    </div>
</div>

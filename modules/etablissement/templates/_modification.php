<div class="form_contenu">
    <?php
    echo $etablissementForm->renderHiddenFields();
    echo $etablissementForm->renderGlobalErrors();
    ?>
    <div class="form_ligne">
        <?php echo $etablissementForm['nom']->renderLabel(); ?>
        <?php echo $etablissementForm['nom']->render(array('class' => 'champ_long')); ?>
        <?php echo $etablissementForm['nom']->renderError(); ?>
    </div>
    <div class="form_ligne">
        <?php echo $etablissementForm['statut']->renderLabel(); ?>
        <?php echo $etablissementForm['statut']->render(); ?>
        <?php echo $etablissementForm['statut']->renderError(); ?>
    </div>
    <?php if (!$etablissement->isNegociant() && !$etablissement->isCourtier()) : ?>
        <div class="form_ligne">
            <div class="form_colonne">
                <?php echo $etablissementForm['raisins_mouts']->renderLabel(); ?>                
                <?php echo $etablissementForm['raisins_mouts']->render(); ?>
                <?php echo $etablissementForm['raisins_mouts']->renderError(); ?>
            </div>
            <div class="form_colonne">
                <?php echo $etablissementForm['exclusion_drm']->renderLabel(); ?>
                <?php echo $etablissementForm['exclusion_drm']->render(); ?>
                <?php echo $etablissementForm['exclusion_drm']->renderError(); ?>
            </div>
        </div>
    <?php
    endif;
    if (!$etablissement->isCourtier()) :
        ?>
        <div class="form_ligne">
            <div class="form_colonne">
                <?php echo $etablissementForm['relance_ds']->renderLabel(); ?>
                <?php echo $etablissementForm['relance_ds']->render(); ?>
    <?php echo $etablissementForm['relance_ds']->renderError(); ?>
            </div>
            <div class="form_colonne">
                <!--    
                <label for="recette_locale">
                <?php // echo $etablissementForm['recette_locale']->renderLabel();   ?>
                </label>
                <?php // echo $etablissementForm['recette_locale']->render(); ?>
    <?php // echo $etablissementForm['recette_locale']->renderError();   ?>
                -->

            </div>
        </div>    
<?php endif; ?>
    <div class="form_ligne">
        <div class="form_colonne">
            <?php echo $etablissementForm['region']->renderLabel(); ?>
            <?php echo $etablissementForm['region']->render(); ?>
        <?php echo $etablissementForm['region']->renderError(); ?>
        </div>
            <?php if (!$etablissement->isNegociant() && !$etablissement->isCourtier()) : ?>
            <div class="form_colonne">
                <?php echo $etablissementForm['type_dr']->renderLabel(); ?>
                <?php echo $etablissementForm['type_dr']->render(); ?>
            <?php echo $etablissementForm['type_dr']->renderError(); ?>
            </div>
<?php endif; ?>
    </div>
    <div class="section_label_maj" id="type_liaison">
        <?php echo $etablissementForm['type_liaison']->renderLabel(); ?>
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
    <?php
    endforeach;
    if (!$etablissement->isCourtier()):
        ?>
        <div class="form_ligne">
            <?php echo $etablissementForm['cvi']->renderLabel(); ?>
        <?php echo $etablissementForm['cvi']->render(); ?>
        <?php echo $etablissementForm['cvi']->renderError(); ?>
        </div> 
        <?php endif; ?>
    <div class="form_ligne">
        <?php echo $etablissementForm['site_fiche']->renderLabel(); ?>
<?php echo $etablissementForm['site_fiche']->render(); ?>
        <?php echo $etablissementForm['site_fiche']->renderError(); ?>
    </div>
     <?php if ($etablissement->isCourtier()): ?>
    <div class="form_ligne">
        <?php echo $etablissementForm['carte_pro']->renderLabel(); ?>
<?php echo $etablissementForm['carte_pro']->render(); ?>
        <?php echo $etablissementForm['carte_pro']->renderError(); ?>
    </div>
    <?php endif; ?>
    <div class="form_ligne">
        <?php echo $etablissementForm['no_accises']->renderLabel(); ?>
<?php echo $etablissementForm['no_accises']->render(); ?>
        <?php echo $etablissementForm['no_accises']->renderError(); ?>
    </div>
    <div class="form_ligne">
        <?php echo $etablissementForm['commentaire']->renderLabel(); ?>
<?php echo $etablissementForm['commentaire']->render(); ?>
<?php echo $etablissementForm['commentaire']->renderError(); ?>
    </div>

</div>

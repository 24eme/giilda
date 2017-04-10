<section id="principal" class="drm">
    <div id="application_drm">
        <?php if (!$isTeledeclarationMode): ?>
            <?php include_partial('drm/header', array('drm' => $drm)); ?>
            <ul id="recap_infos_header">
                <li>
                    <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?><label style="float: right;">Période : <?php echo $drm->periode ?></label>
                </li>
            </ul>
        <?php endif; ?>

        <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_VALIDATION)); ?>

        <h2>Modification des informations de votre chai</h2>

        <form action="<?php echo url_for('drm_validation_update_etablissement', $drm); ?>" method="POST" class="drm_validation_etablissement_form">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <div class="ligne_form">
                <label>Raison Sociale :</label>
                <?php echo $drm->declarant->nom; ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['cvi']->renderError(); ?>
                <?php echo $form['cvi']->renderLabel(); ?>
                <?php echo $form['cvi']->render(); ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['no_accises']->renderError(); ?>
                <?php echo $form['no_accises']->renderLabel(); ?>
                <?php echo $form['no_accises']->render(); ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['adresse']->renderError(); ?>
                <?php echo $form['adresse']->renderLabel(); ?>
                <?php echo $form['adresse']->render(array('class' => 'champ_long')); ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['code_postal']->renderError(); ?>
                <?php echo $form['code_postal']->renderLabel(); ?>
                <?php echo $form['code_postal']->render(); ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['commune']->renderError(); ?>
                <?php echo $form['commune']->renderLabel(); ?>
                <?php echo $form['commune']->render(); ?>
            </div>
            <?php if ($drm->declarant->exist('adresse_compta')): ?>
                <div class="ligne_form">
                    <?php echo $form['adresse_compta']->renderError(); ?>
                    <?php echo $form['adresse_compta']->renderLabel(); ?>
                    <?php echo $form['adresse_compta']->render(array('class' => 'champ_long')); ?>
                </div>
            <?php endif; ?>
            <?php if ($drm->declarant->exist('caution')): ?>
                <div class="ligne_form alignes update_form_radio_list">
                    <span>
                        <?php echo $form['caution']->renderError(); ?>
                        <?php echo $form['caution']->renderLabel(); ?>
                        <?php echo $form['caution']->render(); ?>
                    </span>
                </div>
            <?php endif; ?>
            <?php $hasSocialeCautionneur = (($drm->getEtablissement()->exist('caution') && ($drm->getEtablissement()->caution))
            || (($drm->declarant->exist('caution')) && ($drm->declarant->caution == EtablissementClient::CAUTION_CAUTION)));
            ?>
                <?php if ($drm->declarant->exist('raison_sociale_cautionneur')): ?>
                <div class="ligne_form raison_sociale_cautionneur" style="<?php echo (!$hasSocialeCautionneur) ? 'display:none;' : ''; ?>" >
                    <?php echo $form['raison_sociale_cautionneur']->renderError(); ?>
                    <?php echo $form['raison_sociale_cautionneur']->renderLabel(); ?>
                <?php echo $form['raison_sociale_cautionneur']->render(array('class' => 'champ_long')); ?>
                </div>
<?php endif; ?>

            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_validation', $drm) ?>" class="btn_majeur btn_annuler" style="float: left;" id="drm_validation_etablissement_annuler_btn"><span>annuler</span></a>
                <button type="submit" class="btn_majeur btn_valider" id="drm_validation_etablissement_valider_btn" style="float: right;"><span>Valider</span></button>
            </div>
        </form>
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>

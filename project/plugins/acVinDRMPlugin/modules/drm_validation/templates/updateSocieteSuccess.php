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

        <h2>Modification des informations de votre société</h2>

        <form action="<?php echo url_for('drm_validation_update_societe', $drm); ?>" method="POST" class="drm_validation_societe_form">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <div class="ligne_form">
                <label>Raison Sociale :</label>
                <?php echo $drm->societe->raison_sociale; ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['siret']->renderError(); ?>
                <?php echo $form['siret']->renderLabel(); ?>
                <?php echo $form['siret']->render(); ?>
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
            <div class="ligne_form">
                <?php echo $form['email']->renderError(); ?>
                <?php echo $form['email']->renderLabel(); ?>
                <?php echo $form['email']->render(array('class' => 'champ_long')); ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['telephone']->renderError(); ?>
                <?php echo $form['telephone']->renderLabel(); ?>
                <?php echo $form['telephone']->render(); ?>
            </div>
            <div class="ligne_form">
                <?php echo $form['fax']->renderError(); ?>
                <?php echo $form['fax']->renderLabel(); ?>
                <?php echo $form['fax']->render(); ?>
            </div>
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_validation', $drm) ?>" class="btn_majeur btn_annuler" style="float: left;" id="drm_validation_societe_annuler_btn"><span>annuler</span></a>
                <button type="submit" class="btn_majeur btn_valider" id="drm_validation_societe_valider_btn" style="float: right;"><span>Valider</span></button>
            </div>
        </form>
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>


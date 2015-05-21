<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">

    <?php if (!$isTeledeclarationMode): ?>
        <?php include_partial('drm/header', array('drm' => $drm)); ?>
        <h2>Déclaration Récapitulative Mensuelle</h2>
    <?php else: ?>
        <h2><?php echo getDrmTitle($drm); ?></h2>
    <?php endif; ?>
    <?php if (!$isTeledeclarationMode): ?>  
        <ul id="recap_infos_header">
            <li>
                <label>Nom de l'opérateur : </label> 
                <?php echo $drm->getEtablissement()->nom ?>
            </li>
            <li><label>Période : </label><?php echo $drm->periode ?></li>
        </ul>
    <?php endif; ?>

    <?php include_partial('drm_edition/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_VALIDATION)); ?>


    <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>

    <section id="contenu_etape">
        <?php if (!$isTeledeclarationMode): ?>
            <?php include_component('drm', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
        <?php endif; ?>
        <h2>STOCKS/MOUVEMENTS</h2>
        <fieldset id="validation_drm_mvts_stocks"> 
            <nav>
                <ul>
                    <li class="actif onglet" id="drm_visualisation_stock_onglet"><span>Stock</span></li>
                    <li class="onglet" id="drm_visualisation_mouvements_onglet"><a>Mouvements</a></li>
                </ul>
            </nav>
            <div id="drm_visualisation_stock" class="section_label_maj">
                <?php include_partial('drm_visualisation/stock', array('drm' => $drm, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>
            </div>
            <div id="drm_visualisation_mouvements" class="section_label_maj" style="display: none;">
                <?php include_partial('drm_visualisation/mouvements', array('mouvements' => $mouvements, 'no_link' => $no_link, 'isTeledeclarationMode' => $isTeledeclarationMode)) ?>
            </div>
        </fieldset>

        <br />
        <?php if (!$isTeledeclarationMode): ?>
            <?php echo $form['commentaire']->renderLabel(); ?>
            <?php echo $form['commentaire']->renderError(); ?>
            <?php echo $form['commentaire']->render(); ?>

            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_edition', $drm) ?>" class="btn_etape_prec" id="facture"><span>Précédent</span></a>
                <button type="submit" name="brouillon" value="brouillon" class="btn_brouillon btn_majeur"><span>Enregistrer en brouillon</span></button>
                <button type="submit" <?php if (!$validation->isValide()): ?>disabled="disabled"<?php endif; ?> class="btn_etape_suiv" id="facture"><span>Valider</span></button> 
            </div>
        <?php else: ?>
            <form action="" method="post">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
                <div id="btn_etape_dr">
                    <a href="<?php echo url_for('drm_crd', $drm) ?>" class="btn_etape_prec" id="drm_crd"><span>Précédent</span></a>
                    <button type="submit" <?php if (!$validation->isValide()): ?>disabled="disabled"<?php endif; ?> class="btn_etape_suiv" id="facture"><span>Valider</span></button> 
                </div>
            </form>
        <?php endif; ?>
    </section>
    <?php
    if ($isTeledeclarationMode):
        include_partial('drm_edition/colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
    endif;
    ?>
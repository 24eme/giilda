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
    <?php include_partial('drm_edition/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode,'etape_courante' => DRMClient::ETAPE_SAISIE)); ?>
    <?php include_partial('drm/controlMessage'); ?>

    <div id="application_dr">
        <h2>Saisie des mouvements</h2>

        <div id="contenu_onglet">
            <?php
            include_partial('drm_edition/list', array('drm_noeud' => $drm->declaration,
                'config' => $config,
                'detail' => $detail,
                'produits' => $details,
                'form' => $form,
                'detail' => $detail));
            ?>
        </div>
    </div>
    <?php if (!$isTeledeclarationMode): ?>  
        <div id="btn_etape_dr">
            <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()); ?>" class="btn_brouillon btn_majeur">Enregistrer en brouillon</a>

            <a href="<?php echo url_for('drm_validation', $drm); ?>" class="btn_etape_suiv" id="button_drm_validation"><span>Suivant</span></a> 
        </div>
    <?php else: ?>
        <form action="<?php echo url_for('drm_edition', $formValidation->getObject()) ?>" method="post">
            <?php echo $formValidation->renderHiddenFields(); ?>
            <div id="btn_etape_dr">
                <a class="btn_etape_prec" href="<?php echo url_for('drm_choix_produit', $drm); ?>">
                    <span>Précédent</span>
                </a>
                <button class="btn_etape_suiv" id="button_drm_validation"><span>Suivant</span></button> 
            </div>
        </form>
    <?php endif; ?>

</section>
<?php
if ($isTeledeclarationMode):
    include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
    include_partial('colonne_droite_fil_edition', array('produits' => $details, 'drm' => $drm, 'config' => $config));
endif;
?>
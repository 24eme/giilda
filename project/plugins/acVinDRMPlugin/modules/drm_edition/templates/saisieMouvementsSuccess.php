<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <?php if (!$isTeledeclarationMode): ?>
        <?php include_partial('drm/header', array('drm' => $drm)); ?> 
        <ul id="recap_infos_header">
            <li>
                <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?><label style="float: right;">Période : <?php echo $drm->periode ?></label>
            </li>
        </ul>
    <?php endif; ?>

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_SAISIE)); ?>
    <?php include_partial('drm/controlMessage'); ?>

    <div id="application_dr">
        <div id="contenu_onglet">
            <?php
            include_partial('drm_edition/list', array('drm_noeud' => $drm->declaration,
                'config' => $config,
                'detail' => $detail,
                'produits' => $details,
                'formFavoris' => $formFavoris,
                'form' => $form,
                'detail' => $detail,
                'favoris' => $favoris,
                'isTeledeclarationMode' => $isTeledeclarationMode));
            ?>
        </div>
    </div>
    <form action="<?php echo url_for('drm_edition', $formValidation->getObject()) ?>" method="post">
        <div id="btn_etape_dr">
            <a class="btn_etape_prec" href="<?php echo ($isTeledeclarationMode) ? url_for('drm_choix_produit', $drm) : url_for('drm_choix_produit', $drm); ?>">
                <span>Précédent</span>
            </a>
            <?php if (!$isTeledeclarationMode): ?>  
                <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()); ?>" class="btn_brouillon btn_majeur">Enregistrer en brouillon</a>
            <?php endif; ?>

            <?php echo $formValidation->renderHiddenFields(); ?>
            <button class="btn_etape_suiv" id="button_drm_validation"><span>Suivant</span></button> 

        </div>
    </form>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
include_partial('drm_edition/colonne_droite_fil_edition', array('produits' => $details, 'drm' => $drm, 'config' => $config));
?>
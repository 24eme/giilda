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
    <?php
    $etape_courante = ($isTeledeclarationMode && $saisieSuspendu)?  DRMClient::ETAPE_SAISIE : DRMClient::ETAPE_SAISIE_ACQUITTE;
    include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => $etape_courante));  include_partial('drm/controlMessage');

     ?>

    <div id="application_drm">
        <?php if ($isTeledeclarationMode): ?>
            <p class="choix_produit_explication"><?php echo getHelpMsgText('drm_mouvements_texte1'); ?></p>
        <?php endif; ?>
        <div id="contenu_onglet">
            <?php
            include_partial('drm_edition/list', array('drm_noeud' => $drm->declaration,
                'config' => $config,
                'detail' => $detail,
                'detailsKey' => $detailsKey,
                'produits' => $details,
                'drm' => $drm,
                'formFavoris' => $formFavoris,
                'form' => $form,
                'favoris' => $favoris,
                'isTeledeclarationMode' => $isTeledeclarationMode,
                'detailsNodes' => $detailsNodes,
                'saisieSuspendu' => $saisieSuspendu));
            ?>
        </div>
        <div id="contenu_etape">

            <form action="<?php echo url_for('drm_edition_details', array('sf_subject' => $formValidation->getObject(), 'details' => $detailsKey)) ?>" method="post" class="hasBrouillon">
                <div class="btn_etape">
                    <a class="btn_etape_prec" href="<?php echo ($isTeledeclarationMode) ? url_for('drm_choix_produit', $drm) : url_for('drm_etablissement', $drm); ?>">
                        <span>Précédent</span>
                    </a>


                    <a class="btn_majeur btn_annuaire save_brouillon" href="#">
                        <span>Enregistrer le brouillon</span>
                    </a>
                         <a class="drm_delete_lien" href="#drm_delete_popup"></a>
                    <?php echo $formValidation->renderHiddenFields(); ?>
                    <button class="btn_etape_suiv" id="button_drm_validation"><span>Suivant</span></button>

                </div>
            </form>
        </div>

    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
include_partial('drm_edition/colonne_droite_fil_edition', array('produits' => $details, 'drm' => $drm, 'config' => $config, 'detailsKey' => $detailsKey));
include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>

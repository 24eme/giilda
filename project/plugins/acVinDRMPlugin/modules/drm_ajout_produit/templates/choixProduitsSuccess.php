<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_CHOIX_PRODUITS)); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <div id="application_drm">
        <div id="contenu_etape">
            <p class="choix_produit_explication"><?php echo getHelpMsgText('drm_produits_texte1'); ?></p>
            <form id="form_choix_produits" action="<?php echo url_for('drm_choix_produit', $form->getObject()) ?>" method="post" class="hasBrouillon">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
                <div id="contenu_onglet">
                    <?php
                    include_partial('drm_ajout_produit/choixProduitsList', array('certificationsProduits' => $certificationsProduits,
                        'form' => $form, 'drm' => $drm, 'hasRegimeCrd' => $hasRegimeCrd));
                    ?>
                </div>
                <div class="btn_etape">
                    <a href="<?php echo url_for('drm_societe', array('identifiant' => $drm->getEtablissement()->identifiant)); ?>" class="btn_etape_prec"><span>Précédent</span></a>
                   <a class="btn_majeur btn_annuaire save_brouillon" href="#">
                        <span>Enregistrer le brouillon</span>
                    </a>
                         <a class="drm_delete_lien" href="#drm_delete_popup"></a>
                    <button type="submit" class="btn_etape_suiv" id="choixProduitsSubmit"><span>Suivant</span></button>
                </div>
            </form>
        </div>
    </div>
    <?php if (isset($formAddProduitsByCertification)): ?>
            <a style="display:none" class="btn_majeur ajout_produit_popup" href="#add_produit_popup">Ajouter des Produits</a>
            <?php include_partial('drm_ajout_produit/ajout_produit_popup_certification', array('drm' => $drm, 'form' => $formAddProduitsByCertification)); ?>
    <?php endif; ?>
    <?php if(isset($crdRegimeForm)): ?>
        <?php include_partial('drm_crds/crd_regime_choice_popup', array('drm' => $drm, 'crdRegimeForm' => $crdRegimeForm, 'etablissementPrincipal' => $etablissementPrincipal)); ?>
    <?php endif; ?>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));

include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>

<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_CHOIX_PRODUITS)); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <div id="application_drm">
        <div id="contenu_etape">
            <p class="choix_produit_explication">Afin de préparer le détail de la DRM, vous pouvez préciser ici vos stocks épuisés ou l'absence de mouvements pour tout ou partie des produits.</p>
            <form action="<?php echo url_for('drm_choix_produit', $form->getObject()) ?>" method="post">
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
                    <a class="lien_drm_supprimer" href="<?php echo url_for('drm_delete', $drm); ?>">
                        <span>Supprimer la DRM</span>
                    </a>
                    <button type="submit" class="btn_etape_suiv" id="choixProduitsSubmit"><span>Etape Suivante</span></button>
                </div>
            </form>
        </div>
    </div>
    <?php if (isset($formAddProduitsByCertification)): ?>
            <a style="display:none" class="btn_majeur ajout_produit_popup" href="#add_produit_<?php echo $formAddProduitsByCertification->getCertificationKey(); ?>">Ajouter des Produits</a>
            <?php include_partial('drm_ajout_produit/ajout_produit_popup_certification', array('drm' => $drm, 'form' => $formAddProduitsByCertification)); ?>
    <?php endif; ?>
    <?php if(isset($crdRegimeForm)): ?>
        <?php include_partial('drm_crds/crd_regime_choice_popup', array('drm' => $drm, 'crdRegimeForm' => $crdRegimeForm, 'etablissementPrincipal' => $etablissementPrincipal)); ?>
    <?php endif; ?>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>

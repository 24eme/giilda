<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>

<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_CHOIX_PRODUITS)); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <div id="application_drm">
        <div id="contenu_etape">
            <p class="choix_produit_explication">Afin de préparer le détail de la DRM, vous pouvez préciser ici vos stocks épuisés ou l'absence de mouvements pour tout ou partie des produits.</p>
            <form id="form_choix_produits" action="<?php echo url_for('drm_choix_produit', $form->getObject()) ?>" method="post">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
                <div id="contenu_onglet">
                    <?php
                    include_partial('drm_ajout_produit/choixProduitsList', array('certificationsProduits' => $certificationsProduits,
                        'form' => $form, 'drm' => $drm, 'hasRegimeCrd' => $hasRegimeCrd));
                    ?>
                </div>
                <div class="row">
                    <div class="col-xs-4 text-left">
                        <a tabindex="-1" href="<?php echo url_for('drm_societe', array('identifiant' => $drm->getEtablissement()->identifiant)); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Etape précédente</a>
                    </div>
                    <div class="col-xs-4 text-center">
                        <a class="btn btn-default" href="#drm_delete_popup">
                            <span>Supprimer la DRM</span>
                        </a> 
                    </div>
                    <div class="col-xs-4 text-right">
                        <button type="submit" class="btn btn-success">Étape suivante <span class="glyphicon glyphicon-chevron-right"></span></button>
                    </div>
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

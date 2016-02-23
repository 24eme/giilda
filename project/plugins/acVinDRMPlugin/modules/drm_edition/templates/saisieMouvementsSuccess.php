<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->

<?php include_partial('drm/breadcrumb', array('drm' => $drm)); ?>

<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_SAISIE)); ?>
    <?php include_partial('drm/controlMessage'); ?>

    <div id="application_drm">
        <div class="row">
            <div class="col-sm-9" id="contenu_onglet">
                <?php
                include_partial('drm_edition/list', array('drm_noeud' => $drm->declaration,
                    'config' => $config,
                    'detail' => $detail,
                    'produits' => $details,
                    'drm' => $drm,
                    'formFavoris' => $formFavoris,
                    'form' => $form,
                    'favoris' => $favoris,
                    'isTeledeclarationMode' => $isTeledeclarationMode,
                    'detailsNodes' => $detailsNodes));
                ?>
            </div>

            <div class="col-sm-3">
                <div class="panel panel-default stickyHeader">
                    <div class="panel-heading">Édition des Produits</div>
                    <div class="panel-body">
                        <?php include_component('drm_edition', 'produitForm', array('drm' => $drm, 'config' => $config)) ?>
                    </div>
                    <ul id="list-produits" class="list-group drm_fil_edition_produit">
                        <?php foreach ($details as $produit) : ?>
                            <?php
                            if (!$produit->hasMovements()): continue;
                            endif;
                            ?> 
                            <a style="position: relative;  overflow:hidden;  
  display: block; height: 30px; padding: 5px 15px;
  margin-bottom: -1px;" data-hash="<?php echo $produit->getHash() ?>" <?php echo (!$produit->hasMovements()) ? 'style="display:none;"' : '' ?> class="list-group-item <?php echo ($produit->isEdited()) ? 'edited list-group-item-success' : '' ?>">

                                <small><?php echo $produit->getLibelle("%format_libelle%"); ?></small>
                                <span style="position:absolute; right: 1px; top:5px;" class="btn btn-xs btn-link glyphicon glyphicon-eye-open "></span>
                            </a>
<?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="navigation_etapes" class="row col-xs-10">
            <div class="col-xs-4 text-left">
                <a tabindex="-1" href="<?php echo ($isTeledeclarationMode) ? url_for('drm_choix_produit', $drm) : url_for('drm_etablissement', $drm); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Etape précédente</a>
            </div>
            <div class="col-xs-4 text-center">
                <?php if (!$isTeledeclarationMode): ?>
                    <a href="<?php echo url_for('drm_etablissement', $drm->getEtablissement()); ?>" class="btn btn-default">Enregistrer en brouillon</a>
<?php endif; ?>
                <a class="btn btn-default" data-toggle="modal" data-target="#drm_delete_popup" >Supprimer la DRM</a> 
            </div>
            <div class="col-xs-3 text-right">
                <form action="<?php echo url_for('drm_edition', $formValidation->getObject()) ?>" method="post">
<?php echo $formValidation->renderHiddenFields(); ?>
                    <button type="submit" class="btn btn-success">Étape suivante <span class="glyphicon glyphicon-chevron-right"></span></button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));

include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>

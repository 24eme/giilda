<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<?php $allCrdsByRegimeAndByGenre = $drm->getAllCrdsByRegimeAndByGenre(); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => true, 'etape_courante' => DRMClient::ETAPE_CRD)); ?>
    <div class="row" id="application_drm">
        <div class="col-xs-12">
            <form id="form_crds" action="<?php echo url_for('drm_crd', $crdsForms->getObject()); ?>" method="post">
                <?php echo $crdsForms->renderGlobalErrors(); ?>
                <?php echo $crdsForms->renderHiddenFields(); ?>
                <?php foreach ($allCrdsByRegimeAndByGenre as $regime => $crdAllGenre): ?>
                    <?php if (count($allCrdsByRegimeAndByGenre) > 1): ?>
                        <p>Régime de CRD : <?php echo EtablissementClient::$regimes_crds_libelles_longs[$regime]; ?></p>
                    <?php endif; ?>
                    <?php foreach ($crdAllGenre as $genre => $crds): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">

                                <h3 class="panel-title text-center">Stocks CRD de vins <?php echo getLibelleForGenre($genre); ?></h3>
                            </div>
                            <table id="table_drm_crds" class="table table-bordered table-condensed table-striped">
                                <thead >
                                    <tr>
                                        <th class="col-xs-2 text-center" style="vertical-align: middle;" rowspan="2">CRD <a href="<?php echo url_for('drm_crd', array('sf_subject' => $crdsForms->getObject(), 'add_crd' => $regime, 'genre' => $genre)); ?>" class="btn btn-xs btn-link"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter un type de CRD</a></th>
                                        <th class="col-xs-1 text-center" style="vertical-align: middle;" rowspan="2">Stock</th>
                                        <th class="text-center" colspan="3">Entrées</th>
                                        <th class="text-center" colspan="3">Sorties</th>
                                        <th class="col-xs-1 text-center" style="vertical-align: middle;" rowspan="2" >Stock <?php echo getLastDayForDrmPeriode($drm); ?></th>
                                    </tr>
                                    <tr>

                                        <th class="col-xs-1 text-center">Achat</th>
                                        <th class="col-xs-1 text-center">Retour</th>
                                        <th class="col-xs-1 text-center">Excéd.</th>

                                        <th class="col-xs-1 text-center">Utilisé</th>
                                        <th class="col-xs-1 text-center">Destr.</th>
                                        <th class="col-xs-1 text-center">Manq.</th>

                                    </tr>
                                </thead>
                                <tbody class="drm_crds_list">
                                    <?php foreach ($crds as $crdKey => $crd): ?>
                                        <tr class="crd_row" id="<?php echo $crdKey; ?>">
                                            <td class="type_crd_col" style="vertical-align: middle"><?php echo $crd->getShortLibelle(); ?></td>
                                            <td class="crds_debut_de_mois"><?php if ($crd->stock_debut) { echo $crd->stock_debut; }  echo $crdsForms['stock_debut_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int form-control text-right')); ?></td>
                                            <td class="crds_entreesAchats"><?php echo $crdsForms['entrees_achats_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int form-control text-right')); ?></td>
                                            <td class="crds_entreesRetours"><?php echo $crdsForms['entrees_retours_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int form-control text-right')); ?></td>
                                            <td class="crds_entreesExcedents"><?php echo $crdsForms['entrees_excedents_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int form-control text-right')); ?></td>
                                            <td class="crds_sortiesUtilisations"><?php echo $crdsForms['sorties_utilisations_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int form-control text-right')); ?></td>
                                            <td class="crds_sortiesDestructions"><?php echo $crdsForms['sorties_destructions_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int form-control text-right')); ?></td>
                                            <td class="crds_sortiesManquants"><?php echo $crdsForms['sorties_manquants_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int form-control text-right')); ?></td>
                                            <td class="crds_fin_de_mois text-right" style="vertical-align: middle"><?php echo (is_null($crd->stock_fin)) ? "0" : $crd->stock_fin; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-xs-4 text-left">
                        <a tabindex="-1" href="<?php echo url_for('drm_edition', $drm); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Etape précédente</a>
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
            <?php if(isset($addCrdForm) && isset($addCrdRegime)): ?>
                <?php include_partial('ajout_crds_popups', array('form' => $addCrdForm, 'regime' => $addCrdRegime)); ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>
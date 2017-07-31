<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<?php $allCrdsByRegimeAndByGenre = $drm->getAllCrdsByRegimeAndByGenre(); ?>
<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => true, 'etape_courante' => DRMClient::ETAPE_CRD)); ?>
    <div id="application_drm">
        <div id="contenu_etape">
            <div id="contenu_onglet">
                <p class="choix_produit_explication"><?php echo getHelpMsgText('drm_crds_texte1'); ?></p>
                <form id="form_crds" action="<?php echo url_for('drm_crd', $crdsForms->getObject()); ?>" method="post"  class="hasBrouillon" >
                    <?php echo $crdsForms->renderGlobalErrors(); ?>
                    <?php echo $crdsForms->renderHiddenFields(); ?>
                    <?php foreach ($allCrdsByRegimeAndByGenre as $regime => $crdAllGenre): ?>
                        <?php foreach ($crdAllGenre as $genre => $crds): ?>
                            <h2>Stocks CRD de vins <?php echo EtablissementClient::$regimes_crds_libelles[$regime]; ?> <?php echo getLibelleForGenre($genre); ?></h2>
                            <table id="table_drm_crds" class="table_recap">
                                <thead >
                                    <tr>
                                        <th rowspan="2">CRD&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_crds_aide1'); ?>"></a></th>
                                        <th rowspan="2">Stock&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_crds_aide2'); ?>"></a></th>
                                        <th class="mainth" colspan="3">Entrées&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_crds_aide3'); ?>"></a></th>
                                        <th class="mainth" colspan="3">Sorties&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_crds_aide4'); ?>"></a></th>
                                        <th rowspan="2" >Stock <?php echo getLastDayForDrmPeriode($drm); ?>&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_crds_aide5'); ?>"></a></th>
                                    </tr>
                                    <tr>

                                        <th>Achat</th>
                                        <th>Retour</th>
                                        <th>Excéd.</th>

                                        <th>Utilisé</th>
                                        <th>Destr.</th>
                                        <th>Manq.</th>

                                    </tr>
                                </thead>
                                <tbody class="drm_crds_list">
                                    <?php foreach ($crds as $crdKey => $crd): ?>
                                        <tr class="crd_row" id="<?php echo $regime.'-'.str_replace(".", "", $crdKey); ?>">
                                            <td class="type_crd_col"><?php echo $crd->getShortLibelle(); ?></td>
                                            <td class="crds_debut_de_mois"><?php
                                                if ($crd->stock_debut && !$isUsurpationMode) {
                                                    echo $crd->stock_debut;
                                                } echo $crdsForms['stock_debut_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int'));
                                                ?></td>
                                            <td class="crds_entreesAchats"><?php echo $crdsForms['entrees_achats_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int')); ?></td>
                                            <td class="crds_entreesRetours"><?php echo $crdsForms['entrees_retours_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int')); ?></td>
                                            <td class="crds_entreesExcedents"><?php echo $crdsForms['entrees_excedents_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int')); ?></td>
                                            <td class="crds_sortiesUtilisations"><?php echo $crdsForms['sorties_utilisations_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int')); ?></td>
                                            <td class="crds_sortiesDestructions"><?php echo $crdsForms['sorties_destructions_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int')); ?></td>
                                            <td class="crds_sortiesManquants"><?php echo $crdsForms['sorties_manquants_' . $regime . '_' . $crdKey]->render(array('class' => 'num_int')); ?></td>
                                            <td class="crds_fin_de_mois"><?php echo (is_null($crd->stock_fin)) ? "0" : $crd->stock_fin; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <br/>
                            <div class="drm_add_crd_categorie">
                                <a href="<?php echo url_for('drm_crd', array('sf_subject' => $crdsForms->getObject(), 'add_crd' => $regime, 'genre' => $genre)); ?>" class="btn_majeur submit_button">Ajouter des types de CRD</a>
                            </div>
                            <br/>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <div class="btn_etape">
                        <a class="btn_etape_prec" href="<?php echo url_for('drm_edition', $drm); ?>">
                            <span>Précédent</span>
                        </a>
                        <a class="btn_majeur btn_annuaire save_brouillon" href="#">
                            <span>Enregistrer le brouillon</span>
                        </a>
                        <a class="drm_delete_lien" href="#drm_delete_popup"></a>
                        <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button>
                    </div>
                </form>
                <?php if (isset($addCrdForm) && isset($addCrdRegime)): ?>
                    <a class="btn_majeur ajout_crds_popup " style="display: none;" href="#add_crds_<?php echo $addCrdRegime ?>">Ajouter CRD</a>
                    <?php include_partial('ajout_crds_popups', array('form' => $addCrdForm, 'regime' => $addCrdRegime)); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php if (isset($crdRegimeForm)): ?>
    <?php include_partial('drm_crds/crd_regime_choice_popup', array('drm' => $drm, 'crdRegimeForm' => $crdRegimeForm, 'etablissementPrincipal' => $etablissementPrincipal, 'retour' => 'crds')); ?>
<?php endif; ?>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => true));
include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>

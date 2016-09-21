<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<?php
$paiement_douane_frequence = ($societe->exist('paiement_douane_frequence')) ? $societe->paiement_douane_frequence : null;
?>
<?php include_partial('drm/breadcrumb', array('drm' => $drm)); ?>

<!-- #principal -->
<section id="principal" class="drm">

    <?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_ADMINISTRATION)); ?>
    <?php include_partial('drm/controlMessage'); ?>
    <div id="application_drm">
        <form action="<?php echo url_for('drm_annexes', $annexesForm->getObject()); ?>" class="form-horizontal" method="post">
          <?php echo $annexesForm->renderGlobalErrors(); ?>
          <?php echo $annexesForm->renderHiddenFields(); ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default" >
                        <div class="panel-heading " style="cursor:pointer;" id="drm_annexes_documents" >
                          <div class="row">
                            <div class="col-xs-11">
                              <h3 class="panel-title text-center"><strong>Déclaration des documents d'accompagnement</strong></h3>
                            </div>
                            <div class="col-xs-1 text-right">
                              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_documents" aria-expanded="true" aria-controls="collapse_documents">
                                &nbsp;<span class="glyphicon  <?php echo ($drm->hasAnnexes())? 'glyphicon-chevron-down' : 'glyphicon-chevron-right' ?> " style="padding-top: 4px;" ></span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div id="collapse_documents" class="panel-collapse collapse <?php echo ($drm->hasAnnexes())? 'in' : '' ?>" role="tabpanel" aria-labelledby="drm_annexes_documents">
                          <div class="panel-body">
                            <table id="table_drm_adminitration" class="table table-bordered table-striped">
                                <thead >
                                    <tr>
                                        <th class="col-xs-4" >Type de document</th>
                                        <th class="col-xs-4">Numéro de début</th>
                                        <th class="col-xs-4">Numéro de fin</th>
                                    </tr>
                                </thead>
                                <tbody class="drm_adminitration">
                                    <?php foreach ($annexesForm->getDocTypes() as $typeDoc): ?>
                                        <tr>
                                            <td style="vertical-align: middle;" class="drm_annexes_type"><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>
                                            <td class="drm_annexes_doc_debut"><?php echo $annexesForm[$typeDoc . '_debut']->render(); ?></td>
                                            <td class="drm_annexes_doc_fin"><?php echo $annexesForm[$typeDoc . '_fin']->render(); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" id="drm_annexes_apurement" >
                          <div class="row">
                            <div class="col-xs-11">
                              <h3 class="panel-title text-center"><strong>Relevé de non apurement</strong></h3>
                            </div>
                            <div class="col-xs-1 text-right">
                              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_apurement" aria-expanded="true" aria-controls="collapse_apurement">
                                &nbsp;<span class="glyphicon  <?php echo (count($annexesForm['releve_non_apurement']))? 'glyphicon-chevron-down' : 'glyphicon-chevron-right' ?> " style="padding-top: 4px;" ></span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div id="collapse_apurement" class="panel-collapse collapse <?php echo (count($annexesForm['releve_non_apurement']))? 'in' : '' ?>" role="tabpanel" aria-labelledby="drm_annexes_apurement">
                        <div class="panel-body">
                          <table id="table_drm_non_apurement" class="table table-bordered table-striped">
                              <thead >
                                  <tr>
                                      <th class="col-xs-4">Numéro de document</th>
                                      <th class="drm_non_apurement_date_emission col-xs-4">Date d'expédition</th>
                                      <th class="col-xs-4">Numéro d'accise</th>
                                      <th class="col-xs-1"></th>
                                  </tr>
                              </thead>
                              <tbody class="drm_non_apurement" id="nonapurement_list">
                                  <?php
                                  foreach ($annexesForm['releve_non_apurement'] as $nonApurementForm) :
                                      include_partial('itemNonApurement', array('form' => $nonApurementForm));
                                  endforeach;
                                  ?>
                                  <?php include_partial('templateNonApurementItem', array('form' => $annexesForm->getFormTemplate())); ?>
                              </tbody>
                              <thead>
                                  <tr>
                                      <td colspan="4"><a class="btn_ajouter_ligne_template btn btn-sm btn-link pull-right" data-container="#nonapurement_list" data-template="#template_nonapurement" href="#"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter une ligne non apurement</a></td>
                                  </tr>
                              </thead>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-12">
                      <div class="panel panel-default">
                        <div class="panel-heading" id="drm_annexes_stats_europeenes" >
                            <div class="row">
                              <div class="col-xs-11">
                                <h3 class="panel-title text-center"><strong>Statistiques européennes</strong></h3>
                              </div>
                              <div class="col-xs-1 text-right">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_stats_europeenes" aria-expanded="true" aria-controls="collapse_stats_europeenes">
                                  &nbsp;<span class="glyphicon  glyphicon-chevron-down" style="padding-top: 4px;" ></span>
                                </a>
                              </div>
                            </div>
                        </div>
                        <div id="collapse_stats_europeenes" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="drm_annexes_stats_europeenes">
                        <div class="panel-body">
                          <table class="table table-bordered table-striped">
                              <thead >
                                <tr>
                                  <th class="col-xs-6" ></th>
                                  <th class="col-xs-5" >Volume</th>
                                  <th class="col-xs-1" ></th>
                              </tr>
                            </thead>
                            <tbody class="drm_non_apurement" id="nonapurement_list">
                              <tr>
                                <td class="col-xs-6"><?php echo $annexesForm['statistiques_jus']->renderLabel() ?></td>
                                <td class="col-xs-5"><?php echo $annexesForm['statistiques_jus']->render() ?><?php echo $annexesForm['statistiques_jus']->renderError() ?></td>
                                <td class="col-xs-1" ><span class="unite">hl</span></td>
                              </tr>
                              <tr>
                                <td class="col-xs-6"><?php echo $annexesForm['statistiques_mcr']->renderLabel() ?></td>
                                <td class="col-xs-5"><?php echo $annexesForm['statistiques_mcr']->render() ?><?php echo $annexesForm['statistiques_mcr']->renderError() ?></td>
                                <td class="col-xs-1" ><span class="unite">hl</span></td>
                              </tr>
                              <tr style="border:none;" >
                                <td class="col-xs-6"><?php echo $annexesForm['statistiques_vinaigre']->renderLabel() ?></td>
                                <td class="col-xs-5"><?php echo $annexesForm['statistiques_vinaigre']->render() ?><?php echo $annexesForm['statistiques_vinaigre']->renderError() ?></td>
                                <td class="col-xs-1" ><span class="unite">hl</span></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php if($drm->hasObservations()): ?>
                <div class="row">
                  <div class="col-xs-12">
                      <div class="panel panel-default">
                        <div class="panel-heading" id="drm_annexes_observations" >
                            <div class="row">
                              <div class="col-xs-11">
                                <h3 class="panel-title text-center"><strong>Observations</strong></h3>
                              </div>
                              <div class="col-xs-1 text-right">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_observations" aria-expanded="true" aria-controls="collapse_observations">
                                  &nbsp;<span class="glyphicon  glyphicon-chevron-down" style="padding-top: 4px;" ></span>
                                </a>
                              </div>
                            </div>
                        </div>
                      <div id="collapse_observations" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="drm_annexes_observations">
                        <div class="panel-body">
                        <table class="table table-bordered table-striped">
                            <thead >
                              <tr>
                                  <th class="col-xs-7" >Produits</th>
                                  <th class="col-xs-5" >Observations</th>
                              </tr>
                            </thead>
                            <tbody class="drm_non_apurement" id="nonapurement_list">
                                <?php foreach ($annexesForm['observationsProduits'] as $formObservations): ?>
                                  <?php if(isset($formObservations['observations'])): ?>
                                  <tr>
                                    <td class="col-xs-7" ><?php echo $formObservations['observations']->renderLabel() ?></td>
                                    <td class="col-xs-5" >
                                          <?php echo $formObservations['observations']->renderError() ?>
                                          <?php echo $formObservations['observations']->render(array("maxlength" => "250", "style" => "width: 95%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
                                        </td>
                                  </tr>
                                  <?php endif; ?>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                                  250 caractères max.
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php endif; ?>
              <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                      <div class="panel-heading"  id="drm_annexes_douanes" >
                        <div class="row">
                          <div class="col-xs-11">
                            <h3 class="panel-title text-center"><strong>Sucre / Paiement Douane</strong></h3>
                          </div>
                          <div class="col-xs-1 text-right">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_douanes" aria-expanded="true" aria-controls="collapse_douanes">
                              &nbsp;<span class="glyphicon <?php echo (!$drm->hasPaiementDouane())? 'glyphicon-chevron-down' : 'glyphicon-chevron-right' ?> " style="padding-top: 4px;" ></span>
                            </a>
                          </div>
                        </div>
                      </div>
                    <div id="collapse_douanes" class="panel-collapse collapse <?php echo (!$drm->hasPaiementDouane())? 'in' : '' ?>" role="tabpanel" aria-labelledby="drm_annexes_douanes">
                      <div class="panel-body">
                      <table class="table table-bordered table-striped">
                        <thead >
                          <tr>
                            <th class="col-xs-12" colspan="3" >Information sur le sucre</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="col-xs-5">
                              <?php echo $annexesForm['quantite_sucre']->renderLabel(); ?>
                            </td>
                            <td class="col-xs-5">
                              <?php echo $annexesForm['quantite_sucre']->render(); ?><strong style="float: right; padding-top: 5px;"></strong>
                            </td>
                            <td class="col-xs-2">
                              (en quintal)
                            </td>
                          </tr>
                      </tbody>
                    </table>
                    <br/>
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th colspan="3">Condition de paiement des douanes</th>
                        </tr>
                        </thead>
                        <tbody>
                          <tr>
                              <td class="col-xs-3">
                                <?php echo $annexesForm['paiement_douane_frequence']->renderLabel(); ?>
                              </td>
                              <td class="col-xs-9" colspan="2" >
                                <?php echo $annexesForm['paiement_douane_frequence']->renderError(); ?>
                                <?php echo $annexesForm['paiement_douane_frequence']->render(); ?>
                              </td>
                          </tr>
                          <tr style="vertical-align: middle;" class="drm_paiement_douane_cumul" <?php echo ($paiement_douane_frequence && ($paiement_douane_frequence == DRMPaiement::FREQUENCE_ANNUELLE)) ? '' : 'style="display:none;"'; ?>  >
                              <td class="col-xs-4">
                                  <strong>Cumul des droits douaniers (en €)</strong>
                              </td>
                              <?php foreach ($drm->getAllGenres() as $genre): ?>
                            <td class="col-xs-4" >

                                  <!-- <div class=""> -->
                                    <?php echo $annexesForm['cumul_' . $genre]->renderLabel(); ?>
                                    <?php echo $annexesForm['cumul_' . $genre]->renderError(); ?>
                                  <!-- </div>
                                   <div class="col-xs-3"> -->
                                    <?php echo $annexesForm['cumul_' . $genre]->render(); ?>
                                  <!-- </div> -->

                              </td>
                            <?php endforeach; ?>
                          </tr>
                        </tbody>
                      </table>
                   </div>
                 </div>
               </div>
             </div>
            </div>
            <div class="row">
                <div class="col-xs-4 text-left">
                    <a tabindex="-1" href="<?php echo url_for('drm_crd', $drm); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Etape précédente</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a class="btn btn-default" data-toggle="modal" data-target="#drm_delete_popup" >Supprimer la DRM</a>
                </div>
                <div class="col-xs-4 text-right">
                    <button type="submit" class="btn btn-success">Étape suivante <span class="glyphicon glyphicon-chevron-right"></span></button>
                </div>
            </div>
        </form>
    </div>
</section>
<?php
include_partial('drm/deleteDrmPopup', array('drm' => $drm, 'deleteForm' => $deleteForm));
?>

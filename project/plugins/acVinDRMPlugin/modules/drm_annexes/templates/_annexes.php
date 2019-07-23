<div id="contenu_etape">
    <div id="contenu_onglet">
        <p class="choix_produit_explication"><?php echo getHelpMsgText('drm_annexes_texte1'); ?></p>
        <form action="<?php echo url_for('drm_annexes', $annexesForm->getObject()); ?>" method="post" class="hasBrouillon">
          <?php echo $annexesForm->renderGlobalErrors(); ?>
          <?php echo $annexesForm->renderHiddenFields(); ?>
          <div class="table-condensable">
        <div class="drm_annexes_toggle" style="cursor:pointer;">
          <p class="extendable <?php echo ($drm->hasAnnexes())? 'ouvert' : '' ?>"></p>
          <h2>Déclaration des documents d'accompagnement (facultatif)</h2>
        </div>
        <div <?php echo ($drm->hasAnnexes())? 'style="padding: 0px 10px 10px 10px;"' : 'style="display:none; padding: 0px 10px 10px 10px;"' ?> class="drm_annexes_content_togglable" >
        <div><?php echo getHelpMsgText('drm_annexes_texte2'); ?></div><br/>
            <table id="table_drm_adminitration" class="table_recap table_drm_annexes" >
                <thead >
                    <tr>
                        <th style="width: 200px;">Type de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide1'); ?>" style="float: right; padding: 0 10px 0 0;"></a></th>
                        <th>Numéro de début</th>
                        <th>Numéro de fin</th>
                        <th>Nombre de document(s)</th>
                    </tr>
                </thead>
                <tbody class="drm_adminitration">
                    <?php foreach ($annexesForm->getDocTypes() as $typeDoc): ?>
                        <tr>
                            <td class="drm_annexes_type"><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>
                            <td class="drm_annexes_doc_debut"><?php echo $annexesForm[$typeDoc . '_debut']->render(); ?></td>
                            <td class="drm_annexes_doc_fin"><?php echo $annexesForm[$typeDoc . '_fin']->render(); ?></td>
                            <td class="drm_annexes_doc_nb"><?php echo $annexesForm[$typeDoc . '_nb']->render(array('size'=> 5)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
            <br/>
            <div class="table-condensable ">
            <div class="drm_apurement_toggle" style="cursor:pointer;">
              <p class="extendable <?php echo ($drm->hasReleveNonApurement())? 'ouvert' : '' ?>"></p>
              <h2>Relevé de non apurement</h2>
            </div>
            <div <?php echo ($drm->hasReleveNonApurement())? 'style="padding: 0px 10px 10px 10px;"' : 'style="display:none; padding: 0px 10px 10px 10px;"' ?> class="drm_apurement_content_togglable" >

            <div><?php echo getHelpMsgText('drm_annexes_texte3'); ?></div><br/>
            <table id="table_drm_non_apurement" class="table_recap table_drm_annexes">
                <thead >
                    <tr>
                        <th>&nbsp;Numéro de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide2'); ?>"  style="float: right; padding: 0 10px 0 0;"></a></th>
                        <th class="drm_non_apurement_date_emission">Date d'expédition&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide3'); ?>"  style="float: right; padding: 0 10px 0 0;"></a></th>
                        <th>Numéro d'accise&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide4'); ?>"  style="float: right; padding: 0 10px 0 0;"></a></th>
                        <th></th>
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

            </table>

            <br /><br />
            <div class="form_ligne ajouter_non_apurement">
                <a class="btn_ajouter_ligne_template btn_majeur" data-container="#nonapurement_list" data-template="#template_nonapurement" href="#">Ajouter un non apurement</a>
            </div>
  </div>
          </div>
          <br/>
          <div class="table-condensable ">
          <div class="drm_statistiques_toggle" style="cursor:pointer;">
              <p class="extendable  <?php echo ($drm->hasStatistiquesEuropeennes())? 'ouvert' : '' ?>"></p>
            <h2>Statistiques européennes</h2>
          </div>
<div style="<?php echo ($drm->hasStatistiquesEuropeennes())? '' : 'display:none; ' ?>padding: 0px 10px 10px 10px;" class="drm_statistiques_content_togglable" >
    <table id="table_drm_non_apurement" class="table_recap table_drm_annexes">
        <thead>
                    <tr>
                      <th style=" width: auto;"></th>
                      <th>Volume</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?php echo $annexesForm['statistiques_jus']->renderLabel() ?></td>
                      <td><?php echo $annexesForm['statistiques_jus']->render() ?>&nbsp;<span class="unite">hl</span><br /><?php echo $annexesForm['statistiques_jus']->renderError() ?></td>
                    </tr>
                    <tr>
                      <td><?php echo $annexesForm['statistiques_mcr']->renderLabel() ?></td>
                      <td><?php echo $annexesForm['statistiques_mcr']->render() ?>&nbsp;<span class="unite">hl</span><br /><?php echo $annexesForm['statistiques_mcr']->renderError() ?></td>
                    </tr>
                    <tr>
                      <td><?php echo $annexesForm['statistiques_vinaigre']->renderLabel() ?></td>
                      <td><?php echo $annexesForm['statistiques_vinaigre']->render() ?>&nbsp;<span class="unite">hl</span><br /><?php echo $annexesForm['statistiques_vinaigre']->renderError() ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
</div>
            <br/>
<?php if($drm->hasObservations()): ?>
            <div class="table-condensable ">
            <div class="drm_observations_toggle" style="cursor:pointer;">
                <p class="extendable ouvert"></p>
                <h2>Observations<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide5'); ?>"  style="padding: 0 0 0 10px;"></a></h2>
            </div>
<div style="padding: 0px 10px 10px 10px;" class="drm_observations_content_togglable" >
                <table id="table_drm_observations" class="table_recap table_drm_observations">
                  <thead>
                              <tr>
                                <th style=" width: auto;">Produits</th>
                                <th>Observations</th>
                                <th>Date de la sortie donnant lieue à réintégration</th>
                              </tr>
                            </thead>
                <?php foreach ($annexesForm['observationsProduits'] as $formObservations): ?>
                  <?php if(isset($formObservations['observations'])): ?>
                  <tr>
                    <td><?php echo $formObservations['observations']->renderLabel() ?></td>
                    <td>
                          <?php echo $formObservations['observations']->renderError() ?>
                          <?php echo $formObservations['observations']->render(array("maxlength" => "250", "style" => "box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
                    </td><td>
                      <div class="ligne_form champ_datepicker">
                          <?php if (isset($formObservations['replacement_date'])): ?>
                            <?php echo $formObservations['replacement_date']->renderError(); ?>
                            <?php echo $formObservations['replacement_date']->render(); ?><br/>
                          <?php endif; ?>
                      </div>
                    </td>
                    </tr>
                  <?php endif; ?>
                  <?php endforeach; ?>
                  </table>
                  250 caractères max.
                </div>
            </div>
  <br/>
<?php endif; ?>
        <br/>
<?php if(count($annexesForm['tavsProduits'])): ?>
<div class="table-condensable ">
    <div class="drm_tavs_toggle" style="cursor:pointer;">
        <p class="extendable ouvert"></p>
        <h2>TAV - Taux d'alcool volumique<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_texte4'); ?>"  style="padding: 0 0 0 10px;"></a></h2>
    </div>
    <div style="padding: 0px 10px 10px 10px;" class="drm_tavs_content_togglable" >
                    <table id="table_drm_tavs" class="table_recap table_drm_annexes">
                      <thead>
                                  <tr>
                                    <th style=" width: auto;">Produits&nbsp;</th>
                                    <th>TAV</th>
                                  </tr>
                                </thead>
                    <?php foreach ($annexesForm['tavsProduits'] as $formTavs): ?>
                      <?php if(isset($formTavs['tav'])): ?>
                      <tr>
                        <td><?php echo $formTavs['tav']->renderLabel() ?></td>
                        <td>
                              <?php echo $formTavs['tav']->renderError() ?>
                              <?php echo $formTavs['tav']->render() ?>
                        </td>
                        </tr>
                      <?php endif; ?>
                      <?php endforeach; ?>
                      </table>
        </div>
  </div>
<?php endif; ?>

            <br/>
            <div class = "btn_etape">
                <a class = "btn_etape_prec" href = "<?php echo url_for('drm_crd', $drm); ?>">
                    <span>Précédent</span>
                </a>
                <a class = "btn_majeur btn_annuaire save_brouillon" href = "#">
                    <span>Enregistrer le brouillon</span>
                </a>
                <a class = "drm_delete_lien" href = "#drm_delete_popup"></a>
                <button class = "btn_etape_suiv" id = "button_drm_validation" type = "submit"><span>Suivant</span></button>
            </div>
        </form>

        <br/>
    </div>
</div>

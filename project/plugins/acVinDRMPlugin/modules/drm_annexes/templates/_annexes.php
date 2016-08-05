<?php
$paiement_douane_frequence = ($societe->exist('paiement_douane_frequence')) ? $societe->paiement_douane_frequence : null;
?>
<div id="contenu_etape">
    <div id="contenu_onglet">
        <p class="choix_produit_explication"><?php echo getHelpMsgText('drm_annexes_texte1'); ?></p>
        <form action="<?php echo url_for('drm_annexes', $annexesForm->getObject()); ?>" method="post" class="hasBrouillon">
          <?php echo $annexesForm->renderGlobalErrors(); ?>
          <?php echo $annexesForm->renderHiddenFields(); ?>
          <div class="table-condensable">
        <div class="drm_annexes_toggle" style="cursor:pointer;">
          <p class="extendable <?php echo ($drm->hasAnnexes())? 'ouvert' : '' ?>"></p>
          <h2>Déclaration des documents d'accompagnement</h2>
        </div>
        <div <?php echo ($drm->hasAnnexes())? 'style="padding: 0px 10px 10px 10px;"' : 'style="display:none; padding: 0px 10px 10px 10px;"' ?> class="drm_annexes_content_togglable" >
        <div><?php echo getHelpMsgText('drm_annexes_texte2'); ?></div><br/>
            <table id="table_drm_adminitration" class="table_recap table_drm_annexes" >
                <thead >
                    <tr>
                        <th style="width: 200px;">Type de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide1'); ?>" style="float: right; padding: 0 10px 0 0;"></a></th>
                        <th>Numéro de début</th>
                        <th>Numéro de fin</th>
                    </tr>
                </thead>
                <tbody class="drm_adminitration">
                    <?php foreach ($annexesForm->getDocTypes() as $typeDoc): ?>
                        <tr>
                            <td class="drm_annexes_type"><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>
                            <td class="drm_annexes_doc_debut"><?php echo $annexesForm[$typeDoc . '_debut']->render(); ?></td>
                            <td class="drm_annexes_doc_fin"><?php echo $annexesForm[$typeDoc . '_fin']->render(); ?></td>
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
              <p class="extendable ouvert"></p>
            <h2>Statistiques européennes</h2>
          </div>
<div style="padding: 0px 10px 10px 10px;" class="drm_statistiques_content_togglable" >
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
                              </tr>
                            </thead>
                <?php foreach ($annexesForm['observationsProduits'] as $formObservations): ?>
                  <?php if(isset($formObservations['observations'])): ?>
                  <tr>
                    <td style="width: 332px;"><?php echo $formObservations['observations']->renderLabel() ?></td>
                    <td>
                          <?php echo $formObservations['observations']->renderError() ?>
                          <?php echo $formObservations['observations']->render(array("maxlength" => "250", "style" => "width: 95%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4) inset; border-radius: 3px; border: 0px none; padding: 5px;", "rows" => "2")) ?>
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

<div class="table-condensable ">
<div class="drm_informations_toggle" style="cursor:pointer;">
    <p class="extendable <?php echo ($drm->hasPaiementDouane())? 'ouvert' : '' ?>"></p>
    <h2>Paiement Douane</h2>
</div>
            <div <?php echo ($drm->hasPaiementDouane())? 'style="padding: 0px 10px 10px 10px;"' : 'style="display:none; padding: 0px 10px 10px 10px;"' ?> class="drm_informations_content_togglable" >
            <table id="table_drm_complement_informations_paiement_douane" class="table_recap table_drm_annexes">
                <thead >
                    <tr>
                        <th colspan="2">Condition de paiement des douanes&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide6'); ?>"  style="padding: 0 0 0 10px;"></a></th>
                    </tr>
                </thead>
                <tbody class="drm_non_apurement" id="nonapurement_list">
                    <tr>
                        <td class="drm_quantite_sucre_label" style="width: 255px;">
                            <?php echo $annexesForm['paiement_douane_frequence']->renderLabel(); ?>
                        </td>
                        <td class="drm_paiement_douane_frequence" style="height: 55px;">
                            <?php echo $annexesForm['paiement_douane_frequence']->renderError(); ?>
                            <?php echo $annexesForm['paiement_douane_frequence']->render(); ?>
                        </td>

                    </tr>
                    <tr  class="drm_paiement_douane_cumul" <?php echo ($paiement_douane_frequence && ($paiement_douane_frequence == DRMPaiement::FREQUENCE_ANNUELLE)) ? '' : 'style="display:none;"'; ?>  >
                        <td>
                            Cumul des droits douaniers (en €)
                        </td>
                        <td>
                            <ul>

                                <?php foreach ($drm->getAllGenres() as $genre): ?>
                                <li style="padding: 10px;">
                                        <?php echo $annexesForm['cumul_' . $genre]->renderLabel(); ?>
                                        <?php echo $annexesForm['cumul_' . $genre]->renderError(); ?>
                                        <?php echo $annexesForm['cumul_' . $genre]->render(); ?>
                                    </li>

                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br/>
          </div>
    </div>
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

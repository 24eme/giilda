<?php
$paiement_douane_frequence = ($societe->exist('paiement_douane_frequence')) ? $societe->paiement_douane_frequence : null;
?>
<div id="contenu_etape">
    <div id="contenu_onglet">
        <p class="choix_produit_explication"><?php echo getHelpMsgText('drm_annexes_texte1'); ?></p>
        <h2>Déclaration des documents d'accompagnement</h2>        
        <div><?php echo getHelpMsgText('drm_annexes_texte2'); ?></div><br/>
        <form action="<?php echo url_for('drm_annexes', $annexesForm->getObject()); ?>" method="post" class="hasBrouillon">

            <?php echo $annexesForm->renderGlobalErrors(); ?>
            <?php echo $annexesForm->renderHiddenFields(); ?>
            <table id="table_drm_adminitration" class="table_recap table_drm_annexes">
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
            <br/>
            <br>
            <h2>Relevé de non apurement</h2>
            <div><?php echo getHelpMsgText('drm_annexes_texte3'); ?></div><br/>
            <table id="table_drm_non_apurement" class="table_recap table_drm_annexes">
                <thead >
                    <tr>
                        <th>Numéro de document&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide2'); ?>"  style="float: right; padding: 0 10px 0 0;"></a></th>
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
            <br/>
            <br/>
            <h2>Compléments d'information</h2>
            <table id="table_drm_complement_informations_sucre" class="table_recap table_drm_annexes">   
                <thead >
                    <tr>
                        <th colspan="2">Information sur le sucre</th>
                    </tr>
                </thead>
                <tbody class="drm_non_apurement" id="nonapurement_list">
                    <tr> 
                        <td class="drm_quantite_sucre_label">
                            <?php echo $annexesForm['quantite_sucre']->renderLabel(); ?>
                        </td>
                        <td class="drm_quantite_sucre_volume">
                            <?php echo $annexesForm['quantite_sucre']->render(); ?><strong style="float: right; padding-top: 5px;">(en quintal)</strong>
                        </td>
                    </tr>
                </tbody>
            </table> 
            <br/>
            <br/>         
            <table id="table_drm_complement_informations_observation" class="table_recap">
                <thead >
                    <tr>
                        <th><?php echo $annexesForm['observations']->renderLabel(); ?>&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_annexes_aide5'); ?>"  style="padding: 0 0 0 10px;"></a></th>
                    </tr>
                </thead>
                <tbody class="drm_non_apurement" id="nonapurement_list">
                    <tr>
                        <td class="drm_observation">
                            <?php echo $annexesForm['observations']->render(); ?>
                        </td>
                    </tr>
                </tbody>

            </table>
            <br /><br /> 

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

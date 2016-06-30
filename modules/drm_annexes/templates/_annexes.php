<?php ?>

<div id="contenu_etape">
    <div id="contenu_onglet">
        <h2>Déclaration des documents d'accompagnement</h2>
        <form action="<?php echo url_for('drm_annexes', $annexesForm->getObject()); ?>" method="post">

            <?php echo $annexesForm->renderGlobalErrors(); ?>
            <?php echo $annexesForm->renderHiddenFields(); ?>
            <table id="table_drm_adminitration" class="table_recap table_drm_annexes">
                <thead >
                    <tr>
                        <th>Type de document</th>
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
            <table id="table_drm_non_apurement" class="table_recap table_drm_annexes">
                <thead >
                    <tr>
                        <th>Numéro de document</th>
                        <th class="drm_non_apurement_date_emission">Date d'expédition</th>
                        <th>Numéro d'accise</th>
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
                        <th><?php echo $annexesForm['observations']->renderLabel(); ?></th>
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
            <div class="btn_etape">
                <a class="btn_etape_prec" href="<?php echo url_for('drm_crd', $drm); ?>">
                    <span>Précédent</span>
                </a>
                 <a class="drm_delete_lien lien_drm_supprimer" href="#drm_delete_popup">
                        <span>Supprimer la DRM</span>
                    </a>
                <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button>
            </div>
        </form>

        <br/>
    </div>
</div>

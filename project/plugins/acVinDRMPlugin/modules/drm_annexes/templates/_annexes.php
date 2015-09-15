<?php ?>

<div class="row">
        <form action="<?php echo url_for('drm_annexes', $annexesForm->getObject()); ?>" method="post">

            <?php echo $annexesForm->renderGlobalErrors(); ?>
            <?php echo $annexesForm->renderHiddenFields(); ?>
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Déclaration des documents d'accompagnement</h3>
                    </div>
                    <table id="table_drm_adminitration" class="table table-bordered table-striped">
                        <thead >
                            <tr>
                                <th class="col-xs-4">Type de document</th>
                                <th class="col-xs-4">Numéro de début</th>
                                <th class="col-xs-4">Numéro de fin</th>
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
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Relevé de non apurement</h3>
                    </div>
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

                    </table>
                </div>
            </div>
            <div class="form_ligne ajouter_non_apurement">
                <a class="btn_ajouter_ligne_template btn_majeur" data-container="#nonapurement_list" data-template="#template_nonapurement" href="#">Ajouter un non apurement</a>
            </div>
            <div class="col-xs-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Compléments d'information</h3>
                    </div>
                    <table id="table_drm_complement_informations_sucre" class="table table-bordered table-striped">   
                        <thead>
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
                </div>
            </div>

            <table id="table_drm_complement_informations_observation" class="table table-bordered table-striped">
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

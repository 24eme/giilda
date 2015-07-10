<?php ?>

<div id="contenu_etape">
    <div id="contenu_onglet">
        <h2>Déclaration des documents d'accompagnement</h2>
        <form action="<?php echo url_for('drm_annexes', $annexesForm->getObject()); ?>" method="post">

            <?php echo $annexesForm->renderGlobalErrors(); ?>
            <?php echo $annexesForm->renderHiddenFields(); ?>
            <?php foreach ($annexesForm->getDocTypes() as $typeDoc): ?>
                <table id="table_drm_adminitration" class="table_recap">
                    <thead >
                        <tr>
                            <th class="drm_annexes_type"></th>
                            <th colspan="2">Document d'accompagnement <?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></th>
                        </tr>
                    </thead>
                    <tbody class="drm_adminitration">
                        <tr>
                            <td class="drm_annexes_type"><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>
                            <td class="drm_annexes_doc_debut"><?php echo $annexesForm[$typeDoc . '_debut']->render(); ?></td>
                            <td class="drm_annexes_doc_fin"><?php echo $annexesForm[$typeDoc . '_fin']->render(); ?></td>
                        </tr>
                    </tbody>
                </table>
                <br/>
            <?php endforeach; ?>
            <br>
            <h2>Relevé de non apurement</h2>
            <table id="table_drm_non_apurement" class="table_recap">
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
            <div class="btn_etape">
                <a class="btn_etape_prec" href="<?php echo url_for('drm_crd', $drm); ?>">
                    <span>Précédent</span>
                </a>
                <a class="lien_drm_supprimer" href="<?php echo url_for('drm_delete', $drm); ?>">
                    <span>Supprimer la DRM</span>
                </a>
                <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button>
            </div>
        </form>

        <br/>
    </div>
</div>

<script type="text/javascript">

    (function ($)
    {


    })(jQuery);



</script>
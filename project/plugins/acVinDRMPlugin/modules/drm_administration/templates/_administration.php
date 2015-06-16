<?php ?>

<div id="contenu_onglet"> 
    <h2>Déclaration des documents d'accompagnement</h2>
    <form action="<?php echo url_for('drm_administration', $administrationForm->getObject()); ?>" method="post">

        <?php echo $administrationForm->renderGlobalErrors(); ?>
        <?php echo $administrationForm->renderHiddenFields(); ?>  
        <?php foreach ($administrationForm->getDocTypes() as $typeDoc): ?>
            <table id="table_drm_adminitration" class="table_recap">
                <thead >
                    <tr>                        
                        <th class="drm_administration_type"></th>
                        <th colspan="2">Document d'accompagnement <?php echo DRMClient::$drm_documents_daccompagnement_libelle[$typeDoc]; ?></th>
                    </tr>
                </thead>
                <tbody class="drm_adminitration">
                    <tr> 
                        <td class="drm_administration_type"><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>                       
                        <td class="drm_administration_doc_debut"><?php echo $administrationForm[$typeDoc . '_debut']->render(); ?></td>
                        <td class="drm_administration_doc_fin"><?php echo $administrationForm[$typeDoc . '_fin']->render(); ?></td>
                    </tr>
                </tbody>
            </table>
            <br/>
        <?php endforeach; ?>  
        <br>
        <h2>Relevé de non appurement</h2>
        <table id="table_drm_non_appurement" class="table_recap">
            <thead >
                <tr>                        
                    <th>Numéro de document</th>
                    <th>Date d'emission</th>
                    <th>Numéro d'accise</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="drm_non_appurement">

                <?php
                foreach ($administrationForm['releve_non_appurement'] as $nonAppurementForm) :
                    include_partial('itemNonAppurement', array('form' => $nonAppurementForm));
                endforeach;
                ?>
            </tbody>
        </table>
        <div class="form_ligne">
            <a class="btn_ajouter_ligne_template" data-container="#nonappurement_list" data-template="#template_nonappurement" href="#">Ajouter un non appurement</a>
        </div>     
        <br/>
        <div id="btn_etape_dr">
            <a class="btn_etape_prec" href="<?php echo url_for('drm_crd', $drm); ?>">
                <span>Précédent</span>
            </a>
            <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button> 
        </div>
    </form>

    <br/>
</div>
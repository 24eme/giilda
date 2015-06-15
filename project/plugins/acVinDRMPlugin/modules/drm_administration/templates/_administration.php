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
                <th></th>
                <th colspan="2">Document d'accompagnement <?php echo DRMClient::$drm_documents_daccompagnement_libelle[$typeDoc]; ?></th>
            </tr>
        </thead>
        <tbody class="drm_adminitration_contrat">
            <tr> 
                <td><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>                       
                <td class="dsa_daa_debut"><?php echo $administrationForm[$typeDoc. '_debut']->render(); ?></td>
                <td class="dsa_daa_fin"><?php echo $administrationForm[$typeDoc.'_fin']->render(); ?></td>
            </tr>
        </tbody>
    </table>
        <br/>
 <?php endforeach; ?>     
        <div id="btn_etape_dr">
        <a class="btn_etape_prec" href="<?php echo url_for('drm_crd', $drm); ?>">
            <span>Précédent</span>
        </a>
        <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button> 
    </div>
</form>

    <br/>
</div>
<?php ?>

<div id="contenu_onglet"> 
    <h2>Déclaration des documents d'accompagnement</h2>
    <form action="<?php echo url_for('drm_administration', $administrationForm->getObject()); ?>" method="post">
   <?php if(count($drm->getVracs())): ?>
        <table id="table_drm_adminitration" class="table_recap">
        <thead >
            <tr>                        
                <th></th>
                <th colspan="2">Document d'accompagnement Contrat</th>
            </tr>
        </thead>
        <tbody class="drm_adminitration_contrat">
            <tr> 
                <td>DSA/DAA</td>                       
                <td class="dsa_daa_debut"><?php echo $administrationForm['dsa_daa_debut']->render(); ?></td>
                <td class="dsa_daa_fin"><?php echo $administrationForm['dsa_daa_fin']->render(); ?></td>
            </tr>
        </tbody>
    </table>
 <?php endif; ?>  
        <br/>
   <?php if(count($drm->getExports())): ?>     
        <table id="table_drm_adminitration" class="table_recap">
        <thead >
            <tr>                        
                <th></th>
                <th colspan="2">Document d'accompagnement Export</th>
            </tr>
        </thead>
        <tbody class="drm_adminitration_contrat">
            <tr> 
                <td>DAE</td>                       
                <td class="dae_debut"><?php echo $administrationForm['dae_debut']->render(); ?></td>
                <td class="dae_fin"><?php echo $administrationForm['dae_fin']->render(); ?></td>
            </tr>
        </tbody>
    </table>
     <?php endif; ?>     
        <div id="btn_etape_dr">
        <a class="btn_etape_prec" href="<?php echo url_for('drm_crd', $drm); ?>">
            <span>Précédent</span>
        </a>
        <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button> 
    </div>
</form>

    <br/>
</div>
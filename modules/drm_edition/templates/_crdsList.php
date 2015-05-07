<form action="<?php echo url_for('drm_crd', $crdsForms->getObject()); ?>" method="post">
    <h2>Saisie des CRD</h2>
    <?php echo $crdsForms->renderGlobalErrors(); ?>
    <?php echo $crdsForms->renderHiddenFields(); ?>  

    <table id="table_drm_crds" class="table_recap">
        <thead >
            <tr>                        
                <th>&nbsp;</th>
                <th>Stock début de mois</th>
                <th>Entrées</th>
                <th>Sorties</th>
                <th>Pertes</th>
                <th>Stock fin de mois</th>
            </tr>
        </thead>
        <tbody class="drm_crds_list">
            <?php foreach ($allCrds as $crdsKey => $crds): ?>
                <tr>                        
                    <td><?php echo 'CRD ' . $crds->getLibelle(); ?></td>
                    <td><?php echo $crds->stock_debut; ?></td>
                    <td><?php echo $crdsForms['entrees_'.$crdsKey]->render();  ?></td>
                    <td><?php echo $crdsForms['sorties_'.$crdsKey]->render();  ?></td>
                    <td><?php echo $crdsForms['pertes_'.$crdsKey]->render();  ?></td>
                    <td><?php echo $crds->stock_fin; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br/>
    <div class="drm_add_crd_categorie">
        <a class="btn_majeur ajout_crds_popup" href="#add_crds">Ajouter CRD</a> 
    </div>
    <br/>
    <div id="btn_etape_dr">
        <a class="btn_etape_prec" href="<?php echo url_for('drm_edition', $drm); ?>">
            <span>Précédent</span>
        </a>
        <button class="btn_etape_suiv" id="button_drm_validation" type="submit"><span>Suivant</span></button> 
    </div>
</form>

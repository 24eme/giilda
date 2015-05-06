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
                <td><?php echo 'CRD '.$crds->getLibelle(); ?></td>
                <td><?php echo $crds->stock_debut; ?></td>
                <td><?php echo $crds->entrees; ?></td>
                <td><?php echo $crds->sorties; ?></td>
                <td><?php echo $crds->pertes; ?></td>
                <td><?php echo $crds->stock_fin; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="drm_add_crd_categorie">
    <a class="btn_majeur ajout_crds_popup" href="#add_crds">Ajouter CRD</a> 
</div>
 <div id="contenu_onglet">
    <h2>COMPTE CAPSULES (CRD)</h2>

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
            <?php foreach ($drm->getAllCrds() as $crdsKey => $crds): ?>
            <tr class="crd_row" id="<?php echo $crdsKey;  ?>">                        
                    <td><?php echo 'CRD ' . $crds->getLibelle(); ?></td>
                    <td class="crds_debut_de_mois"><?php echo $crds->stock_debut; ?> <input type="hidden" value="<?php echo $crds->stock_debut; ?>"></td>
                    <td class="crds_entrees"><?php echo $crds->entrees;  ?></td>
                    <td class="crds_sorties"><?php echo $crds->sorties;  ?></td>
                    <td class="crds_pertes"><?php echo $crds->pertes;  ?></td>
                    <td class="crds_fin_de_mois"><?php echo (is_null($crds->stock_fin))? "0" : $crds->stock_fin;  ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

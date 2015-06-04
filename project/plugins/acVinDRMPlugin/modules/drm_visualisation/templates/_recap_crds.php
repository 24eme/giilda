<h2>COMPTE CAPSULES (CRD)</h2>
<fieldset id="crds_drm">
    <table class="table_recap">
        <thead>
            <tr>
                <th style="width: 200px;">Couleur</th>
                <th>Stock début de mois</th>
                <th>Achats</th>
                <th>Sorties</th>
                <th>Pertes</th>
                <th><strong>Stock fin de mois</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drm->getAllCrds() as $crd):
                ?>
                <tr >
                    <td><?php echo $crd->getLibelle(); ?></td>
                    <td><strong><?php // echo $crd->stock_debut; ?></strong></td>
                    <td><strong><?php // echo $crd->entrees; ?></strong></td>
                    <td><strong><?php // echo $crd->sorties; ?></strong></td>
                    <td ><strong><?php // echo $crd->pertes; ?></strong></td>
                    <td ><strong><?php // echo $crd->stock_fin; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>
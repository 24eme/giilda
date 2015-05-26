<h2>DROITS ET COTISATIONS</h2>

<div id="contenu_onglet">
    <h2>CVO</h2>
    <table id="table_drm_cvo_recap" class="table_recap">
        <thead >
            <tr>                        
                <th>&nbsp;</th>
                <th>Volumes facturables</th>
                <th>Volumes réintégrés</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody class="drm_cvo_list">
            <?php foreach ($drm->getProduitsDetails() as $detail): ?>
                <tr class="droit_cvo_row" >   
                    <td class="droit_cvo">CVO</td>
                    <td class="droit_cvo_facturable"><?php echo 'X'; ?></td>
                    <td class="droit_cvo_reintegration"><?php echo 'Y'; ?></td>
                    <td class="droit_cvo_total"><?php echo 'TOTAL X'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="contenu_onglet">
    <h2>DROITS DE CIRCULATION</h2>
    <table id="table_droit_circulation" class="table_recap">
        <thead >
            <tr>                    
                <th>Code</th>
                <th>Volumes imposables</th>
                <th>Taux</th>
                <th>Montant à payer</th>
            </tr>
        </thead>
        <tbody class="drm_droit_circulation_list">
            <?php foreach ($drm->getProduitsDetails() as $detail): ?>
                <tr class="droit_circulation_row" >                        
                    <td class="droit_circulation_code"><?php echo $detail->getCepage()->getConfig()->getCodeDouane() ; ?></td>
                    <td class="droit_circulation_volume_imposable"><?php echo "0" ; ?></td>
                    <td class="droit_circulation_taux"><?php echo "0" ; ?></td>
                    <td class="droit_circulation_montant"><?php echo "0" ; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<?php use_helper('Float'); ?>
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
            <tr class="droit_cvo_row" >   
                <td class="droit_cvo">CVO</td>
                <td class="droit_cvo_facturable"><?php echoFloat($recapCvo->totalVolumeDroitsCvo);
echo " Hl";
?></td>
                <td class="droit_cvo_reintegration"><?php echoFloat($recapCvo->totalVolumeReintegration);
                    echo " Hl";
?></td>
                <td class="droit_cvo_total"><?php echoFloat($recapCvo->totalPrixDroitCvo);
                    echo " €";
?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php if ($isTeledeclarationMode) : ?>
    <div id="contenu_onglet">
        <h2>DROITS DE CIRCULATION</h2>
        <table id="table_droit_circulation" class="table_recap">
            <thead >
                <tr>             
                    <th>Libellé</th>
                    <th>Code</th>
                    <th>Volumes imposables</th>
                    <th>Volumes retours</th>
                    <th>Taux</th>
                    <th>Montant à payer</th>
                </tr>
            </thead>
            <tbody class="drm_droit_circulation_list">
    <?php foreach ($drm->getDroitsDouane() as $droitDouane): ?>
                    <tr class="droit_circulation_row" >                        
                        <td class="droit_circulation_libelle"><?php echo $droitDouane->libelle; ?></td>
                        <td class="droit_circulation_code"><?php echo $droitDouane->code; ?></td>
                        <td class="droit_circulation_volume_imposable"><?php echoFloat($droitDouane->volume_taxe); ?></td>
                        <td class="droit_circulation_volume_reintegre"><?php echoFloat($droitDouane->volume_reintegre); ?></td>
                        <td class="droit_circulation_taux"><?php echoFloat($droitDouane->taux); ?></td>
                        <td class="droit_circulation_montant"><?php echoFloat($droitDouane->total); ?></td>
                    </tr>
    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>



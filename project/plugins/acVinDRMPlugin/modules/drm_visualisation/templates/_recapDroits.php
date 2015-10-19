<?php use_helper('Float'); ?>
<?php if ($isTeledeclarationMode) : ?>
<h3>Droits et cotisations</h3>
<?php endif; ?>
<div class="row">
    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">CVO</h3>
            </div>
            <table id="table_drm_cvo_recap" class="table table-bordered table-condensed">
                <thead >
                    <tr>                        
                        <th>&nbsp;</th>
                        <th>Volumes facturables</th>
        <?php if($recapCvo->totalVolumeReintegration) : ?> 
                       <th>Volumes réintégrés</th>
        <?php endif; ?>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody class="drm_cvo_list">
                    <tr class="droit_cvo_row" >   
                        <td class="droit_cvo">CVO</td>
                        <td class="droit_cvo_facturable"><?php
                            echoFloat($recapCvo->totalVolumeDroitsCvo);
                            echo " hl";
                            ?></td>
        <?php if($recapCvo->totalVolumeReintegration) : ?>
                        <td class="droit_cvo_reintegration"><?php
                            echoFloat($recapCvo->totalVolumeReintegration);
                            echo " hl";
                            ?></td>
        <?php endif; ?>
                        <td class="droit_cvo_total"><?php
                            echoFloat($recapCvo->totalPrixDroitCvo);
                            echo " €";
                            ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php if ($isTeledeclarationMode) : ?>
    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Droits de circulation</h3>
            </div>
            <table id="table_droit_circulation" class="table table-bordered table-condensed">
                <thead >
                    <tr>             
                        <th>Libellé</th>
                        <th>Code</th>
                        <th>Volumes imposables</th>
                        <th>Taux</th>
                        <th>Montant</th>
                        <?php if ($drm->isPaiementAnnualise()): ?>
                            <th>Cumul annuel</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="drm_droit_circulation_list">
                    <?php foreach ($drm->getDroitsDouane() as $droitDouane): ?>
                        <tr class="droit_circulation_row" >                        
                            <td class="droit_circulation_libelle"><?php echo $droitDouane->libelle; ?></td>
                            <td class="droit_circulation_code"><?php echo $droitDouane->code; ?></td>
                            <td class="droit_circulation_volume_imposable"><?php echoFloat($droitDouane->volume_taxe - $droitDouane->volume_reintegre); echo " hl" ?></td>
                            <td class="droit_circulation_taux"><?php
                                echoFloat($droitDouane->taux);
                                echo " €/hl";
                                ?></td>
                            <td class="droit_circulation_montant"><?php
                                echoFloat($droitDouane->total);
                                echo " €";
                                ?></td>
                                <?php if ($drm->isPaiementAnnualise()): ?> 
                                <td class="droit_circulation_cumul"><?php
                                echoFloat($droitDouane->cumul);
                                echo " €";
                                ?></td>
            <?php endif; ?>
                        </tr>
        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
</div>



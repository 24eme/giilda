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
            <table id="table_drm_cvo_recap" class="table table-striped table-bordered table-condensed">
                <thead >
                    <tr>                        
                        <th>&nbsp;</th>
                        <th>Volumes facturables</th>
        <?php if($recapCvos["TOTAL"]->totalVolumeReintegration) : ?> 
                       <th>Volumes réintégrés</th>
        <?php endif; ?>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($recapCvos as $recapCvo): ?>
                    <tr >   
                        <td><?php if($recapCvo->version): ?><small class="text-muted"><?php echo $recapCvo->version ?></small> <?php endif; ?>CVO</td>
                        <td><?php
                            echoFloat($recapCvo->totalVolumeDroitsCvo);
                            echo " hl";
                            ?></td>
        <?php if($recapCvo->totalVolumeReintegration) : ?>
                        <td><?php
                            echoFloat($recapCvo->totalVolumeReintegration);
                            echo " hl";
                            ?></td>
        <?php endif; ?>
                        <td><?php
                            echoFloat($recapCvo->totalPrixDroitCvo);
                            echo " €";
                            ?></td>
                    </tr>
                <?php endforeach; ?>
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
            <table class="table table-bordered table-striped table-condensed">
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
                <tbody>
                    <?php foreach ($drm->getDroitsDouane() as $droitDouane): ?>
                        <tr>                        
                            <td><?php echo $droitDouane->libelle; ?></td>
                            <td><?php echo $droitDouane->code; ?></td>
                            <td><?php echoFloat($droitDouane->volume_taxe - $droitDouane->volume_reintegre); echo " hl" ?></td>
                            <td><?php
                                echoFloat($droitDouane->taux);
                                echo " €/hl";
                                ?></td>
                            <td><?php
                                echoFloat($droitDouane->total);
                                echo " €";
                                ?></td>
                                <?php if ($drm->isPaiementAnnualise()): ?> 
                            <td><?php
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



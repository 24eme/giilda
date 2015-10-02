<?php use_helper('Float'); ?>
<?php if ($isTeledeclarationMode) : ?>
<h2>DROITS ET COTISATIONS</h2>
<?php endif; ?>
<div id="contenu_onglet">
    <h2>CVO</h2>
    <table id="table_drm_cvo_recap" class="table_recap">
        <thead >
            <tr>                        
                <th>&nbsp;</th>
                <th>Volumes facturables&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_visualisation_aide2'); ?>"></a></th>
<?php if($recapCvo->totalVolumeReintegration) : ?> 
               <th>Volumes réintégrés</th>
<?php endif; ?>
                <th>Montant&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_visualisation_aide3'); ?>"></a></th>
            </tr>
        </thead>
        <tbody class="drm_cvo_list">
            <tr class="droit_cvo_row" >   
                <td class="droit_cvo">Cotisation interprofessionnelle (INTERLOIRE)</td>
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
<?php if ($isTeledeclarationMode) : ?>
    <div id="contenu_onglet">
        <h2>DROITS DE CIRCULATION</h2>
        <table id="table_droit_circulation" class="table_recap">
            <thead >
                <tr>             
                    <th>Libellé</th>
                    <th>Code</th>
                    <th>Volumes imposables&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_visualisation_aide4'); ?>"></a></th>
                    <th>Taux&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_visualisation_aide5'); ?>"></a></th>
                    <th>Montant&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_visualisation_aide6'); ?>"></a></th>
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
<?php endif; ?>



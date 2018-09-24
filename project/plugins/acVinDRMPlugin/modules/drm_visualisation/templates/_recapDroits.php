<?php use_helper('Float'); ?>
<?php if ($isTeledeclarationMode) : ?>
<?php endif; ?>
<div id="contenu_onglet">
    <h2>Cotisation interprofessionnelle</h2>
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

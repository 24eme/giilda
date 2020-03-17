<?php use_helper('Float'); ?>
<div id="contenu_onglet">
    <?php if($csv = CSVClient::getInstance()->findFromIdentifiantPeriode($drm->identifiant, $drm->periode)): ?>
        <h2>Logiciel tiers</h2>
        Cette drm a été initialisée à partir d'un fichier issu d'une logiciel tiers : <a href="<?php echo url_for('drm_verification_fichier_edi', array('identifiant' => $drm->identifiant, 'periode' => $drm->periode, 'nocheck'=> true, 'md5' => md5(file_get_contents($csv->getAttachmentUri('import_edi_'.$drm->identifiant.'_'.$drm->periode.'.csv'))))) ?>">Voir le fichier</a>
    <?php endif; ?>

    <?php if ($isTeledeclarationMode && !$drm->isNegoce()) : ?>
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
    <?php endif; ?>
</div>

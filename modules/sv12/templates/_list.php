<?php use_helper('Float'); ?>
<fieldset id="history_sv12">
    <legend>Déclaration SV12 en cours de Saisie</legend>
        <table class="table_recap">
        <thead>
        <tr>
            <th>Campagne - Version </th>
            <th>Volume total raisins</th>
            <th>Volume total mouts</th>
            <th>Total</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $item): ?> 
            <tr>
                <td><?php echo sprintf("%s-%s", $item->periode, ($item->version) ? $mouvement->version : 'M00'); ?></td>
                <td>
                    <?php echoFloat($item->totaux->volume_raisins) ?>
                </td>
                <td>
                    <?php echoFloat($item->totaux->volume_mouts) ?>
                </td>
                <td>
                    <?php echoFloat($item->totaux->volume_raisins + $item->totaux->volume_mouts) ?>
                </td>
                <td>
                    <?php if(in_array($item->valide->statut, array(SV12Client::STATUT_VALIDE, SV12Client::STATUT_VALIDE_PARTIEL))): ?>
                        <a href="<?php echo url_for(array('sf_route' => 'sv12_visualisation', 'identifiant' => $item->identifiant, 'periode_version' => SV12Client::getInstance()->buildPeriodeAndVersion($item->periode, $item->version))) ?>">Visualiser</a>
                    <?php else: ?>
                        <a href="<?php echo url_for(array('sf_route' => 'sv12_update', 'identifiant' => $item->identifiant, 'periode_version' => SV12Client::getInstance()->buildPeriodeAndVersion($item->periode, $item->version))) ?>">Continuer</a>
                    <?php endif; ?> 
                </td>
            </tr>
            <?php endforeach; ?>   
        </tbody>
        </table>
</fieldset>
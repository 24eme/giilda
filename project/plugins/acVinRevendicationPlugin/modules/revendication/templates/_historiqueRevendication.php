<?php
use_helper('Date');
if (count($historiqueImport)):
    ?>
    <h2>Historique des Imports</h2>
    <table class="table_recap">
        <thead>
            <tr>
                <th>N° import</th>
                <th>Date</th>
                <th>Campagne</th>
                <th>Odg</th>
                <th>Suppr.</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historiqueImport as $import) : ?>
                <tr>
                    <td><?php echo link_to(RevendicationClient::getInstance()->getRevendicationLibelle($import->id), 'revendication_edition', RevendicationClient::getInstance()->getParametersFromId($import->id));
        ?></td>
                    <td><?php echo format_date($import->key[RevendicationHistoryView::KEYS_DATE], 'dd/MM/yyyy'); ?></td>
                    <td><?php echo $import->key[RevendicationHistoryView::KEYS_CAMPAGNE]; ?></td>
                    <td><?php echo $import->key[RevendicationHistoryView::KEYS_ODG]; ?></td>
                    <td><a href="<?php echo url_for('revendication_delete', array('odg' => $import->key[RevendicationHistoryView::KEYS_ODG],
                        'campagne' => $import->key[RevendicationHistoryView::KEYS_CAMPAGNE])); ?>" class="btn_majeur btn_annuler" /></td>
                </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?php
endif;
?>
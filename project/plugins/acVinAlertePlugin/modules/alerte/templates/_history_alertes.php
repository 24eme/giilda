<?php
use_helper('Date');
$statutsWithLibelles = AlerteClient::getStatutsWithLibelles();
?>
<fieldset>
        <?php if(!count($alertesHistorique)): ?>
    <div>
        <span>
            Aucune alertes ouvertes
        </span>
    </div>
        <?php else: ?>
        <table class="table_recap">
        <thead>
        <tr>
            <th>Changer Type</th>
            <th>Type d'alerte</th>
            <th>Date d'ouverture</th>
            <th>Document concerné</th>
            <th>Statut</th>
            <th>Date du statut</th>
            <th>Opérateur concerné</th>
        </tr>
        </thead>
        <tbody>
                <?php foreach ($alertesHistorique as $alerte) :
            ?>   
            <tr>
                <td>
                    <?php echo $modificationStatutForm[$alerte->id]->renderError(); ?>
                    <?php echo $modificationStatutForm[$alerte->id]->render() ?> 
                </td>
                <td><?php echo link_to(AlerteClient::$alertes_libelles[$alerte->key[AlerteHistoryView::KEY_TYPE_ALERTE]],'alerte_modification',
                                       array('type_alerte' => $alerte->key[AlerteHistoryView::KEY_TYPE_ALERTE],
                                             'id_document' => $alerte->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE])); ?></td>
                <td><?php echo format_date($alerte->key[AlerteHistoryView::KEY_DATE_CREATION_ALERTE],'dd/MM/yyyy'); ?></td>
                <td><?php echo $alerte->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE]; ?></td>
                <td><?php echo $statutsWithLibelles[$alerte->key[AlerteHistoryView::KEY_STATUT_ALERTE]]; ?></td>
                <td><?php echo format_date($alerte->key[AlerteHistoryView::KEY_DATE_ALERTE],'dd/MM/yyyy'); ?></td>
                <td><?php echo $alerte->value[AlerteHistoryView::VALUE_NOM]; ?></td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
        <?php endif; ?>
</fieldset>

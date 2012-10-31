<fieldset>
        <table class="table_recap">
        <thead>
        <tr>
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
                <td><?php echo AlerteClient::$alertes_libelles[$alerte->key[AlerteHistoryView::KEY_TYPE_ALERTE]]; ?></td>
                <td><?php echo $alerte->key[AlerteHistoryView::KEY_DATE_CREATION_ALERTE]; ?></td>
                <td><?php echo $alerte->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE]; ?></td>
                <td><?php echo $alerte->key[AlerteHistoryView::KEY_STATUT_ALERTE]; ?></td>
                <td><?php echo $alerte->key[AlerteHistoryView::KEY_DATE_ALERTE]; ?></td>
                <td><?php echo $alerte->value[AlerteHistoryView::VALUE_NOM]; ?></td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
</fieldset>
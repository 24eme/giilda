<?php use_helper('Float'); use_helper('Date'); ?>
<fieldset id="dsHistorique">
        <table class="table_recap">
        <thead>
        <tr>
            <th>Campagne</th>
            <th>N° DS</th>
            <th>Numéro d'archive</th>
            <th>Etat</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
                <?php foreach ($dsHistorique as $ds) :
            ?>   
            <tr>
                <td><?php echo $ds->key[DSHistoryView::KEY_CAMPAGNE]; ?></td>
                <td><a href="<?php echo url_for('ds_pdf',array('identifiant' => $ds->key[DSHistoryView::KEY_IDENTIFIANT], 'periode' => $ds->key[DSHistoryView::KEY_PERIODE])); ?>" id="saisie_ds" ><?php echo $ds->value[DSHistoryView::VALUE_DS_ID]; ?></a></td>
                <td><?php echo $ds->value[DSHistoryView::VALUE_DECLARANT_NUMERO_ARCHIVE]; ?> </td>
                <td><?php echo DSClient::getInstance()->getLibelleStatutForHistory($ds->key[DSHistoryView::KEY_STATUT]); ?></td>
                <td>
<?php if ($ds->key[DSHistoryView::KEY_STATUT] == DSClient::STATUT_A_SAISIR) : ?>
<a href="<?php echo url_for('ds_edition_operateur',array('identifiant' => $ds->key[DSHistoryView::KEY_IDENTIFIANT], 'periode' => $ds->key[DSHistoryView::KEY_PERIODE])); ?>" id="saisie_ds" >Saisir</a>
<?php else : ?>
<a href="<?php echo url_for('ds_edition_operateur_validation_visualisation',array('identifiant' => $ds->key[DSHistoryView::KEY_IDENTIFIANT], 'periode' => $ds->key[DSHistoryView::KEY_PERIODE])); ?>" id="saisie_ds" >Visualiser</a>
<?php endif; ?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
</fieldset>
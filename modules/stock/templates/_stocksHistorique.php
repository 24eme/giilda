<?php use_helper('Float'); use_helper('Date'); ?>
<fieldset id="stocksHistorique">
        <table class="table_recap">
        <thead>
        <tr>
            <th>Campagne</th>
            <th>N° DS</th>
            <th>CVI</th>
            <th>Etat</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($stocksHistorique as $stock) :
            ?>   
            <tr>
                <td><?php echo $stock->key[StockHistoryView::KEY_CAMPAGNE]; ?></td>
                <td><?php echo $stock->value[StockHistoryView::VALUE_STOCK_ID]; ?></td>
                <td><?php echo $stock->value[StockHistoryView::VALUE_DECLARANT_CVI]; ?> </td>
                <td><?php echo $stock->key[StockHistoryView::KEY_STATUT]; ?></td>
                <td> <a href="<?php echo url_for('stock_historique_generation'); ?>" id="saisie_stocks" >Saisir</a></td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
</fieldset>
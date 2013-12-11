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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historiqueImport as $import) : 
                $date = format_date($import->key[RevendicationHistoryView::KEYS_DATE], 'dd/MM/yyyy');
                $campagne = $import->key[RevendicationHistoryView::KEYS_CAMPAGNE];
                $odg = $import->key[RevendicationHistoryView::KEYS_ODG];
                ?>
                <tr>
                        <script>
   $(document).ready(function() {
   $(".remove_revendication_<?php echo $import->id; ?>").click(function() {
       return confirm('Voulez vous vraiment supprimer les volumes revendiquées <?php echo $campagne; ?> de la région <?php echo $odg; ?>? O/N"');
     });
     });
</script>
                    <td><?php echo link_to(RevendicationClient::getInstance()->getRevendicationLibelle($import->id), 'revendication_edition', RevendicationClient::getInstance()->getParametersFromId($import->id));
        ?></td>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $campagne; ?></td>
                    <td><?php echo $odg; ?></td>
                    <td><a class="remove_revendication_<?php echo $import->id; ?>" href="<?php echo url_for('revendication_delete', array('odg' => $odg, 'campagne' => $campagne)); ?>">Supprimer</a></td>
                </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?php
endif;
?>
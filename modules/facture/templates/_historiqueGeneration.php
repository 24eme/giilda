<?php
use_helper('Float');
?>
<h2>10 dernières facturation générées </h2>
<fieldset>
    
<?php
if(count($generations)==0):
?>
Aucune génération de facture
<?php
else :
?>
    <table class="table_recap">
    <thead>
    <tr>
    <th>Date</th>
    <th>Type</th>
    <th>Nb facture/avoir</th>
    <th>Montant</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($generations as $generation) : ?>
    <tr>
        <td><?php echo $generation->value[GenerationClient::HISTORY_VALUES_DATE]; ?></td>
        <td><?php echo 'F'; ?></td>
        <td><?php echo $generation->value[GenerationClient::HISTORY_VALUES_NBDOC]; ?></td>
        <td><?php echoFloat($generation->value[GenerationClient::HISTORY_VALUES_SOMME]); ?>&nbsp;€</td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php 
endif;
?>
</fieldset>

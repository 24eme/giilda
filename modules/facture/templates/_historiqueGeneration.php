<?php
use_helper('Float');    
?>
<h2>10 dernières générations : </h2>
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
    <th>Génération</th>
    <th>Nb facture/avoir</th>
    <th>Montant TTC</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($generations as $generation) : 
        $documents = $generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS];
    ?>
        <tr>
            <td><?php echo GenerationClient::getInstance()->getDateFromIdGeneration($generation->value[GenerationClient::HISTORY_VALUES_DATE]);?></td>
            <td><?php echo 'F';?></td>
            <td><?php echo link_to($generation->value[GenerationClient::HISTORY_VALUES_DATE], 'generation_view', array('type_document' => GenerationClient::TYPE_DOCUMENT_FACTURES, 'date_emission' => $generation->value[GenerationClient::HISTORY_VALUES_DATE]));?></td>
            <td><?php echo count($generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS]);?></td>
            <td><?php echoFloat($generation->value[GenerationClient::HISTORY_VALUES_SOMME]);?></td>       
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php 
endif;
?>
</fieldset>

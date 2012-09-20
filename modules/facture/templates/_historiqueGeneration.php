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
    <th>PDF</th>
    <th>Nb facture/avoir</th>
    <th>Montant TTC</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($generations as $generation) : 
        $documents = $generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS];
     ?>       
     <?php
        foreach ($documents as $key => $documentId) :
                $first = ($key == 0);
        ?>        
     <tr>
     <?php
        if($first) :
      ?>
         <td rowspan="<?php echo count($documents); ?>">
             <?php echo GenerationClient::getInstance()->getDateFromIdGeneration($generation->value[GenerationClient::HISTORY_VALUES_DATE]); ?>
         </td>
        <?php
        endif;
        ?>
         <td>
             <?php echo 'F'; ?>
         </td>
         <td>
             <?php
                $documentIdArr = explode('-', $documentId);
                $idEtablissement = $documentIdArr[1];
                $factureid = $documentIdArr[2];
                $d = $idEtablissement.' '.$factureid;                
                echo link_to($d, array('sf_route' => 'facture_pdf', 'identifiant' => $idEtablissement, 'factureid' => $factureid)); 
             ?>
         </td>
         <?php 
         if($first) :
        ?>
        <td rowspan="<?php echo count($documents); ?>"><?php echo $generation->value[GenerationClient::HISTORY_VALUES_NBDOC]; ?></td>
        <td rowspan="<?php echo count($documents); ?>"><?php echoFloat($generation->value[GenerationClient::HISTORY_VALUES_SOMME]); ?>&nbsp;€</td>
        <?php
       endif;
        ?>
    </tr>
        <?php
        endforeach;
        ?>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php 
endif;
?>
</fieldset>

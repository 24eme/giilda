<?php
use_helper('Display');
?>

<table class="mandataire_infos">
        <tr>
            <td class="bold">
                Nom du mandataire*
            </td>
            <td>
               <?php display_field($mandataire,'nom'); ?>
            </td>
        </tr>
        <tr>
            <td class="bold">
                N° carte professionnelle
            </td>
            <td>
               <?php display_field($mandataire,'carte_pro'); ?>
            </td>    
            
        </tr> 
        <tr>
            <td class="bold">
                Adresse
            </td>
            <td>
               <?php  display_field($mandataire,'adresse');  ?>
            </td>
        </tr>
        <tr>
            <td class="bold">
                CP
            </td>
            <td>
               <?php  display_field($mandataire,'code_postal');  ?>
            </td>
        </tr>
         <tr>
            <td class="bold">
                Ville
            </td>
            <td>
               <?php  display_field($mandataire,'commune');  ?>
            </td>
        </tr>
</table>
<?php
use_helper('Display');
?>
<table class="vendeur_infos">
        <tr>
            <td class="bold">
                Nom du vendeur*
            </td>
            <td>
                <input type="text" value="<?php display_field($vendeur,'nom'); ?>"/>
               
            </td>    
            <td class="bold">
                Adresse*
            </td>
            <td>
                <input type="text" value=" <?php  display_field($vendeur,'adresse');  ?>"/>
              
            </td>
            
        </tr>
        <tr>
            <td class="bold">
                N° CVI
            </td>
            <td>
                <input type="text" value="<?php display_field($vendeur,'cvi'); ?>"/>
               
            </td>    
            <td class="bold">
                CP*
            </td>
            <td>
                <input type="text" value="<?php  display_field($vendeur,'code_postal');  ?>"/>
               
            </td>
        </tr>
        <tr>
            <td class="bold">
                N° ACCISE
            </td>
            <td>
                <input type="text" value="<?php display_field($vendeur,'num_accise'); ?>"/>
               
            </td>    
            <td class="bold">
                Ville*
            </td>
            <td>
                <input type="text" value="<?php  display_field($vendeur,'commune');  ?>"/>
               
            </td>
        </tr>
</table>
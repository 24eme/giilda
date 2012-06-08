<?php
use_helper('Display');
?>
<script type="text/javascript">
    $(document).ready(function() 
    { 
        init_informations('acheteur');       
       <?php
        if(!isset($numero_contrat))
        {
        ?>
        ajaxifyGet('modification','#vrac_acheteur_identifiant','#acheteur_modification_btn','#acheteur_informations');
        <?php
        }
        else
        {
        ?>        
        ajaxifyGet('modification',{field_0 : '#vrac_acheteur_identifiant',
                                   'type' : 'acheteur' ,
                                   'numero_contrat' : '<?php echo $numero_contrat;?>'
                                  }, '#acheteur_modification_btn','#acheteur_informations');
        <?php
        }
        ?>
    });
</script>
<table class="vendeur_infos">
        <tr>
            <td class="bold">
                Nom de l'acheteur*
            </td>
            <td>
               <?php display_field($acheteur,'nom'); ?>
            </td>    
            <td class="bold">
                Adresse*
            </td>
            <td>
               <?php  display_field($acheteur,'adresse');  ?>
            </td>
            
        </tr>
        <tr>
            <td class="bold">
                N° CVI
            </td>
            <td>
               <?php display_field($acheteur,'cvi'); ?>
            </td>    
            <td class="bold">
                CP*
            </td>
            <td>
               <?php  display_field($acheteur,'code_postal');  ?>
            </td>
        </tr>
        <tr>
            <td class="bold">
                N° ACCISE
            </td>
            <td>
               <?php display_field($acheteur,'num_accise'); ?>
            </td>    
            <td class="bold">
                Ville*
            </td>
            <td>
               <?php  display_field($acheteur,'commune');  ?>
            </td>
        </tr>
        <tr>
            <td class="bold">
                TVA Intracomm.
            </td>
            <td>
               <?php display_field($acheteur,'num_tva_intracomm'); ?>
            </td>    
            <td>
            </td>
            <td>
            </td>
        </tr>
</table>
<?php
use_helper('Display');
?>
<script type="text/javascript">
    $(document).ready(function() 
    { 
        init_informations('vendeur');       
       <?php
        if(!isset($numero_contrat))
        {
        ?>
        ajaxifyGet('modification','#vrac_vendeur_identifiant','#vendeur_modification_btn','#vendeur_informations');
        <?php
        }
        else
        {
        ?>        
        ajaxifyGet('modification',{field_0 : '#vrac_vendeur_identifiant',
                                   'type' : 'vendeur' ,
                                   'numero_contrat' : "<?php echo $numero_contrat;?>"
                                  },'#vendeur_modification_btn','#vendeur_informations');    
        <?php
        }
        ?>
    });
</script>
<table class="vendeur_infos">
        <tr>
            <td class="bold">
                Nom du vendeur*
            </td>
            <td>
               <?php display_field($vendeur,'nom'); ?>
            </td>    
            <td class="bold">
                Adresse*
            </td>
            <td>
               <?php  display_field($vendeur,'adresse');  ?>
            </td>
            
        </tr>
        <tr>
            <td class="bold">
                N° CVI
            </td>
            <td>
               <?php display_field($vendeur,'cvi'); ?>
            </td>    
            <td class="bold">
                CP*
            </td>
            <td>
               <?php  display_field($vendeur,'code_postal');  ?>
            </td>
        </tr>
        <tr>
            <td class="bold">
                N° ACCISE
            </td>
            <td>
               <?php display_field($vendeur,'num_accise'); ?>
            </td>    
            <td class="bold">
                Ville*
            </td>
            <td>
               <?php  display_field($vendeur,'commune');  ?>
            </td>
        </tr>
        <tr>
            <td class="bold">
                TVA Intracomm.
            </td>
            <td>
               <?php display_field($vendeur,'num_tva_intracomm'); ?>
            </td>    
            <td>
            </td>
            <td>
            </td>
        </tr>
</table>
<?php
use_helper('Display');
?>
<script type="text/javascript">
    $(document).ready(function() 
    { 
        init_informations('mandataire');       
       <?php
        if(!isset($numero_contrat))
        {
       ?>
        ajaxifyGet('modification','#vrac_mandataire_identifiant','#mandataire_modification_btn','#mandataire_informations'); 
       <?php
        }
        else
        {
       ?>        
        ajaxifyGet('modification',{field_0 : '#vrac_mandataire_identifiant',
                                   'type' : 'mandataire' ,
                                   'numero_contrat' : '<?php echo $numero_contrat;?>'
                                  },'#mandataire_modification_btn','#mandataire_informations');           
       <?php
        }
       ?>
    });
</script>
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
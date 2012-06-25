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
<div class="mandataire_infos bloc_form">
    <div class="ligne_form">
        <span>
            <label>Nom du mandataire*</label>
            <?php display_field($mandataire,'nom'); ?>
        </span>
    </div>
    <div class="ligne_form ligne_form_alt">    
        <span>
            <label>N° carte professionnelle</label>
            <?php display_field($mandataire,'carte_pro'); ?>
        </span>
    </div>
    <div class="ligne_form">       
        <span>
            <label>Adresse</label>
            <?php  display_field($mandataire,'adresse');  ?>
        </span>
    </div>
    <div class="ligne_form ligne_form_alt"> 
        <span>
            <label>CP</label>
            <?php  display_field($mandataire,'code_postal');  ?>
        </span>
    </div>
    <div class="ligne_form ">      
        <span>
            <label>Ville</label>
            <?php  display_field($mandataire,'commune');  ?>
        </span>
    </div>
</div>
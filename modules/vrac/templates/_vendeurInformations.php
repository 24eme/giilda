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

<div id="vendeur_infos" class="bloc_form">
    
    <div class="col">
        <div class="ligne_form">
            <span>
                  <label>Nom du vendeur*</label>
                  <?php display_field($vendeur,'nom'); ?>
            </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            <span>
                <label>N° CVI</label>
                <?php display_field($vendeur,'cvi'); ?>
            </span>
        </div>
        <div class="ligne_form">
            <span>
                <label>N° ACCISE*</label>
                <?php display_field($vendeur,'num_accise'); ?>
            </span>
        </div>
        <div class="ligne_form ligne_form_alt " >
            <span>
                <label>TVA Intracomm.</label>
                <?php display_field($vendeur,'num_tva_intracomm'); ?>
            </span>
        </div>
    </div>
    
    <div class="col">
        <div class="ligne_form">
            <span>
                <label>Adresse*</label>
                <?php display_field($vendeur,'adresse');  ?>
            </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            <span>
                <label>CP*</label>
                <?php display_field($vendeur,'code_postal'); ?>
            </span>
        </div>
        <div class="ligne_form">
            <span>
                <label>Ville*</label>
                <?php display_field($vendeur,'commune'); ?>
            </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            
        </div>
    </div>
<div>
<?php
use_helper('Display');
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();

$type = $form->getObject()->getFamilleType();
?>
<script type="text/javascript">
    $(document).ready(function() {
        init_ajax_modification('<?php echo $type;?>');
    });                        
</script>



<div id="vendeur_infos" class="modification_infos bloc_form">
    
    <div class="col">
        <div class="ligne_form">
            <span><label>Nom du <?php echo $type; ?> :</label>
            <?php echo $form->getObject()->nom; ?>    </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            <span><label>N° CVI </label>
                <?php echo $form->getObject()->cvi; ?>    </span>
        </div>
        <div class="ligne_form">
            <span><?php echo $form['num_accise']->renderLabel() ?>
            <?php echo $form['num_accise']->renderError(); ?>
            <?php echo $form['num_accise']->render() ?> </span>
        </div>
        <div class="ligne_form ligne_form_alt " >
            <span><?php echo $form['num_tva_intracomm']->renderLabel() ?>
            <?php echo $form['num_tva_intracomm']->renderError(); ?>
            <?php echo $form['num_tva_intracomm']->render() ?> </span>
        </div>
    </div>
    
    <div class="col">
        <div class="ligne_form">
            <span><?php echo $form['adresse']->renderLabel() ?>
            <?php echo $form['adresse']->renderError(); ?>
            <?php echo $form['adresse']->render() ?> </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            <span><?php echo $form['code_postal']->renderLabel() ?>
            <?php echo $form['code_postal']->renderError(); ?>
            <?php echo $form['code_postal']->render() ?></span>  
        </div>
        <div class="ligne_form">
            <span><?php echo $form['commune']->renderLabel() ?>
            <?php echo $form['commune']->renderError(); ?>
            <?php echo $form['commune']->render() ?> </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            
        </div>
    </div>
<div>

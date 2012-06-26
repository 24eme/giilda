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


<div class="mandataire_infos bloc_form">
    <div class="ligne_form">
        <span>
            <label>Nom du <?php echo $type; ?></label>
            <?php echo $form->getObject()->nom; ?> 
        </span>
    </div>
    <div class="ligne_form ligne_form_alt">    
        <span>
            <?php echo $form['carte_pro']->renderLabel() ?>
            <?php echo $form['carte_pro']->renderError(); ?>
            <?php echo $form['carte_pro']->render() ?> 
        </span>
    </div>
    <div class="ligne_form">       
        <span>
            <?php echo $form['adresse']->renderLabel() ?> 
            <?php echo $form['adresse']->renderError(); ?>
            <?php echo $form['adresse']->render() ?>
        </span>
    </div>
    <div class="ligne_form ligne_form_alt"> 
        <span>
            <?php echo $form['code_postal']->renderLabel() ?>
            <?php echo $form['code_postal']->renderError(); ?>
            <?php echo $form['code_postal']->render() ?>  
        </span>
    </div>
    <div class="ligne_form">      
        <span>
            <?php echo $form['commune']->renderLabel() ?>
            <?php echo $form['commune']->renderError(); ?>
            <?php echo $form['commune']->render() ?>
        </span>
    </div>
</div>


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
<table class="mandataire_infos">
        <tr>
             <td class="bold">
                Nom du <?php echo $type; ?> :            
            </td>
            <td>
                 <?php echo $form->getObject()->nom; ?>             
            </td>    
        </tr>
        <tr>
            <td class="bold">
                <?php echo $form['carte_pro']->renderLabel() ?>            
            </td>
            <td>
                 <?php echo $form['carte_pro']->renderError(); ?>
                 <?php echo $form['carte_pro']->render() ?>                   
            </td>            
        </tr> 
        <tr>
            <td class="bold">
                <?php echo $form['adresse']->renderLabel() ?>            
            </td>
            <td>
                 <?php echo $form['adresse']->renderError(); ?>
                 <?php echo $form['adresse']->render() ?>                   
            </td>    
        </tr>
        <tr>
            <td class="bold">
                <?php echo $form['code_postal']->renderLabel() ?>            
            </td>
            <td>
                 <?php echo $form['code_postal']->renderError(); ?>
                 <?php echo $form['code_postal']->render() ?>                   
            </td>   
        </tr>
         <tr>
            <td class="bold">
                <?php echo $form['commune']->renderLabel() ?>            
            </td>
            <td>
                 <?php echo $form['commune']->renderError(); ?>
                 <?php echo $form['commune']->render() ?>                   
            </td>   
        </tr>
</table>
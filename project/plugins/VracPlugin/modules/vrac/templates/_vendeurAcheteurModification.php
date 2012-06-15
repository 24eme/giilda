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
<table class="vendeur_infos">
        <tr>
            <td class="bold">
                Nom du <?php echo $type; ?> :            
            </td>
            <td>
                 <?php echo $form->getObject()->nom; ?>             
            </td>    
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
                <?php echo $form['cvi']->renderLabel() ?>
            </td>
            <td>
               <?php echo $form['cvi']->renderError(); ?>
               <?php echo $form['cvi']->render() ?>      
            </td>    
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
                 <?php echo $form['num_accise']->renderLabel() ?>
            </td>
            <td>
                <?php echo $form['num_accise']->renderError(); ?>
                <?php echo $form['num_accise']->render() ?> 
            </td>    
            <td class="bold">
               <?php echo $form['commune']->renderLabel() ?>
            </td>
            <td>
              <?php echo $form['commune']->renderError(); ?>
              <?php echo $form['commune']->render() ?> 
            </td>
        </tr>
        <tr>
            <td class="bold">
                 <?php echo $form['num_tva_intracomm']->renderLabel() ?>
            </td>
            <td>
                <?php echo $form['num_tva_intracomm']->renderError(); ?>
                <?php echo $form['num_tva_intracomm']->render() ?> 
            </td>    
            <td class="bold">
            </td>
            <td>
            </td>
        </tr>
</table>
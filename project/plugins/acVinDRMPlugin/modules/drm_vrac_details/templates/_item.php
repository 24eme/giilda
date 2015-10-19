<?php $ligneId = "ligne_".str_replace(array("[", "]"), array("-", ""), $form->renderName()) ?>

<tr id="<?php echo $ligneId ?>">
    <td class="form-group <?php if($form['identifiant']->hasError()): ?>has-error<?php endif; ?>">    
        <?php echo $form['identifiant']->renderError(); ?>
        <?php echo $form['identifiant']->render(array("class" => "form-control select2")); ?>
    </td>
    <td class="form-group <?php if($form['volume']->hasError()): ?>has-error<?php endif; ?>">    
        <?php echo $form['volume']->renderError(); ?>
        <div class="input-group" class="">
            <?php echo $form['volume']->render(array("class" => "form-control text-right")); ?>
            <div class="input-group-addon">hl</div>
        </div>
    </td>
    <?php if($isTeledeclarationMode && 1 == 2): ?>
        <td class="form-group <?php if($form['type_document']->hasError()): ?>has-error<?php endif; ?>">    
            <?php
            echo $form['type_document']->renderError();
            echo $form['type_document']->render();
            ?>
        </td>   
        <td class="form-group <?php if($form['numero_document']->hasError()): ?>has-error<?php endif; ?>">    
            <?php
            echo $form['numero_document']->renderError();
            echo $form['numero_document']->render();
            ?>
        </td>  
    <?php endif; ?>
    <td class="text-right">    
        <button type="button" data-line="#<?php echo $ligneId ?>" data-add="#drm_vrac_details_table .dynamic-element-add" data-lines="#drm_vrac_details_table tbody tr" class="btn btn-danger dynamic-element-delete"><span class="glyphicon glyphicon-remove"></span></button>
    </td>  
</tr>
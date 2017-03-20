<?php use_helper('PointsAides'); ?>
<?php $ligneId = "ligne_".str_replace(array("[", "]"), array("-", ""), $form->renderName()) ?>
<tr id="<?php echo $ligneId ?>">
    <td class="form-group <?php if($form['identifiant']->hasError()): ?>has-error<?php endif; ?>  col-xs-4">
        <?php echo $form['identifiant']->renderError(); ?>
        <?php echo $form['identifiant']->render(array("class" => "form-control select2", "autofocus" => "autofocus")); ?>
    </td>
    <td class="form-group <?php if($form['volume']->hasError()): ?>has-error<?php endif; ?> col-xs-3 volume" >
        <?php echo $form['volume']->renderError(); ?>
        <div class="input-group" class="">
            <?php echo $form['volume']->render(array("class" => "input-float form-control text-right")); ?>
            <div class="input-group-addon">hl</div>
        </div>
    </td>

        <td class="form-group  col-xs-2  <?php if($form['type_document']->hasError()): ?>has-error<?php endif; ?> typedoc_show" <?php echo ($docShow) ? '' : 'style="display: none;"' ?> >
            <?php
            echo $form['type_document']->renderError();
            echo $form['type_document']->render();
            ?>
        </td>
        <td class="form-group  col-xs-2 <?php if($form['numero_document']->hasError()): ?>has-error<?php endif; ?> typedoc_show" <?php echo ($docShow) ? '' : 'style="display: none;"' ?> >
            <?php
            echo $form['numero_document']->renderError();
            echo $form['numero_document']->render();
            ?>
        </td>
    <td class="col-xs-2 typedoc_unshow"   <?php echo (!$docShow) ? '' : 'style="display: none;"' ?>  ></td>
    <td class="text-right col-xs-2">
        <a type="button" data-line="#<?php echo $ligneId ?>" data-add="#drm_export_details_table .dynamic-element-add" data-add="#drm_export_details_table" data-lines="#drm_export_details_table tbody tr" tabindex="-1" class="btn btn-xs btn-danger dynamic-element-delete"><span class="glyphicon glyphicon-remove"></span></a><?php echo getPointAideHtml('drm','mouvements_contrats_supprimer') ?>
    </td>
</tr>

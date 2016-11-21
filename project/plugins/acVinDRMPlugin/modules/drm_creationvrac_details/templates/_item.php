<?php use_helper('PointsAides'); ?>
<?php $ligneId = "ligne_" . str_replace(array("[", "]"), array("-", ""), $form->renderName()) ?>
<tr id="<?php echo $ligneId ?>">
    <td class="form-group <?php if ($form['identifiant']->hasError()): ?>has-error<?php endif; ?> col-xs-4">
        <?php echo $form['identifiant']->renderError(); ?>
        <?php echo $form['identifiant']->render(array("class" => "form-control", "autofocus" => "autofocus")); ?>
    </td>
    <td class="form-group <?php if ($form['acheteur']->hasError()): ?>has-error<?php endif; ?> col-xs-2">
        <?php echo $form['acheteur']->renderError(); ?>
        <div class="input-group" class="">
            <?php echo $form['acheteur']->render(array("class" => "form-control select2autocomplete")); ?>
        </div>
    </td>


    <td class="form-group col-xs-2 <?php if ($form['prixhl']->hasError()): ?>has-error<?php endif; ?>" >
        <?php
        echo $form['prixhl']->renderError();
        echo $form['prixhl']->render(array("class" => "input-float text-right"));
        ?>
    </td>
    <td class="form-group col-xs-2 volume <?php if ($form['volume']->hasError()): ?>has-error<?php endif; ?>" >
        <?php
        echo $form['volume']->renderError();
        echo $form['volume']->render(array("class" => "input-float text-right"));
        ?>
    </td>

    <td class="text-right col-xs-2">
        <a type="button" data-line="#<?php echo $ligneId ?>" data-add="#drm_creationvrac_details_table dynamic-element-add" data-lines="#drm_creationvrac_details_table tbody tr" tabindex="-1" class="btn btn-xs btn-danger dynamic-element-delete"><span class="glyphicon glyphicon-remove"></span></a><?php echo getPointAideHtml('drm','mouvements_contrats_supprimer') ?>
    </td>
</tr>

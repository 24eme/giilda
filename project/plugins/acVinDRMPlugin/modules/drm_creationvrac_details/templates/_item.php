<?php use_helper('PointsAides'); ?>
<?php $ligneId = "ligne_" . str_replace(array("[", "]"), array("-", ""), $form->renderName()) ?>
<tr id="<?php echo $ligneId ?>" class="row">
    <td class="form-group <?php if ($form['acheteur']->hasError()): ?>has-error<?php endif; ?> col-xs-5">
        <?php echo $form['acheteur']->renderError(); ?>
        <?php echo $form['acheteur']->render(array("class" => "form-control select2autocomplete", "autofocus" => "autofocus")); ?>
    </td>
    <td class="form-group volume <?php if ($form['volume']->hasError()): ?>has-error<?php endif; ?> col-xs-2" >
        <?php
        echo $form['volume']->renderError();
        ?>
        <div class="input-group" class="">
            <?php echo $form['volume']->render(array("class" => "input-float form-control text-right","placeholder" => "Volume")); ?>
            <div class="input-group-addon">hl</div>
        </div>
    </td>

    <td class="form-group <?php if ($form['prixhl']->hasError()): ?>has-error<?php endif; ?> col-xs-2" >
      <?php
      echo $form['prixhl']->renderError();
      ?>
      <div class="input-group" class="">
          <?php echo $form['prixhl']->render(array("class" => "form-control text-right","placeholder" => "Prix")); ?>
          <div class="input-group-addon">€/hl</div>
      </div>
    </td>

    <td class="form-group <?php if ($form['numero_archive']->hasError()): ?>has-error<?php endif; ?> col-xs-2">
      <?php echo $form['numero_archive']->renderError(); ?>
      <?php echo $form['numero_archive']->render(array("class" => "form-control", "autofocus" => "autofocus","placeholder" => "Num. du contrat")); ?>
    </td>

    <td class="text-right">
        <a type="button" data-line="#<?php echo $ligneId ?>" data-add="#drm_creationvrac_details_table .dynamic-element-add" data-lines="#drm_creationvrac_details_table tbody tr" tabindex="-1" class="btn btn-xs btn-danger dynamic-element-delete"><span class="glyphicon glyphicon-remove"></span></a><?php echo getPointAideHtml('drm','mouvements_contrats_supprimer') ?>
    </td>
</tr>

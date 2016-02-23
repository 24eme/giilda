<div class="form-group">
    <?php
    echo $form['label']->renderError();
    echo $form['label']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
    <div class="col-xs-4"><?php echo $form['label']->render(); ?></div>
    <div class="col-xs-1"><a href="#" class="btn btn-danger btn_supprimer_ligne_template" data-container="div">X</a></div>
</div>


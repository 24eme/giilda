<div class="col-sm-12" data-key="<?php echo $form->getName() ?>">
    <div class="row">
        <div class="col-sm-6">
            <?php echo $form['date']->renderError() ?>
            <div class="form-group <?php if ($form['date']->hasError()): ?>has-error<?php endif; ?>" >
                <?php echo $form['date']->render(array('class' => 'form-control', 'placeholder' => 'Date')); ?>
                <span class="text-muted"><?php echo $form['date']->renderHelp() ?></span>
            </div>
        </div>
        <div class="col-sm-6">

            <?php echo $form['taux']->renderError() ?>
            <div class="form-group <?php if ($form['taux']->hasError()): ?>has-error<?php endif; ?>" >
                <?php echo $form['taux']->render(array('class' => 'form-control ', 'placeholder' => 'Taux')); ?>
                <span class="text-muted"><?php echo $form['taux']->renderHelp() ?></span>
            </div>
        </div>
    </div>
</div>
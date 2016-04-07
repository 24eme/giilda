<div data-key="<?php echo $form->getName() ?>">
        <div class="col-sm-6">
            <div class="form-group <?php if ($form['date']->hasError()): ?>has-error<?php endif; ?>" >
                <?php echo $form['date']->renderError() ?>
                <?php echo $form['date']->renderLabel(null, array('class' => 'col-xs-2')); ?>
                <div class="col-xs-8"><?php echo $form['date']->render(); ?></div>
                <span class="text-muted"><?php echo $form['date']->renderHelp() ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php if ($form['taux']->hasError()): ?>has-error<?php endif; ?>" >
                <?php echo $form['taux']->renderError() ?>
                <?php echo $form['taux']->renderLabel(null, array('class' => 'col-xs-2')); ?>
                <div class="col-xs-8"><?php echo $form['taux']->render(); ?></div>
            </div>
        </div>
</div>
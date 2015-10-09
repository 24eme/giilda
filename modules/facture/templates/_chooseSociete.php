<h3>Sélection d'un opérateur</h3>
<form method="post" class="form-horizontal" action="<?php echo url_for('facture'); ?>" id="facture_generation_btn">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="col-xs-10">
        <div class="form-group<?php if ($form['identifiant']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $form['identifiant']->renderError(); ?>        
            <?php echo $form['identifiant']->render(array('class' => 'form-control select2 input-md', 'placeholder' => 'Rechercher')); ?>
        </div>
    </div>
    <div class="col-xs-2">
        <button id="btn_rechercher" type="submit">Rechercher</button>
    </div>
</form>
<h3>Sélection d'une société</h3>
<form method="post" class="form-horizontal" action="<?php echo url_for('facture'); ?>" id="facture_generation_btn">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="col-xs-10">
        <div class="form-group<?php if ($form['identifiant']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $form['identifiant']->renderError(); ?>
            <?php echo $form['identifiant']->render(array('class' => 'form-control select2SubmitOnChange select2autocomplete input-md', 'placeholder' => 'Rechercher')); ?>
        </div>
    </div>
    <div class="col-xs-2">
        <button id="btn_rechercher" class="btn btn-md btn-default" type="submit">Accéder</button>
    </div>
</form>

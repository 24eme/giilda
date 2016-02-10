<h3>Sélection d'un opérateur</h3>
<?php echo $form['identifiant']->renderError(); ?>
<form method="post" class="form-horizontal" action="<?php echo $action; ?>">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="col-xs-10">
    <div class="form-group<?php if($form['identifiant']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $form['identifiant']->renderError(); ?>
        <?php echo $form['identifiant']->render(array('class' => 'form-control select2SubmitOnChange select2autocomplete input-md', 'placeholder' => 'Rechercher')); ?>
    </div>
    </div>
    <div class="col-xs-2">
    <button class="btn btn-default btn-md" type="submit" id="btn_rechercher">Accéder</button>
    </div>
</form>
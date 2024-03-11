
<h3>Sélection d'un opérateur</h3>
<?php echo $form['identifiant']->renderError(); ?>
<form method="post" class="form-horizontal" action="<?php echo $action; ?>">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="form-group<?php if($form['identifiant']->hasError()): ?> has-error<?php endif; ?>">
        <div class="col-xs-12 col-sm-10">
        <?php echo $form['identifiant']->renderError(); ?>
        <?php
          $options = array('required' => 'required', 'class' => 'form-control select2SubmitOnChange select2autocompleteAjax input-md', 'placeholder' => 'Rechercher', "autofocus" => "autofocus");
          if (isset($noautofocus) && $noautofocus) {
            unset($options['autofocus']);
          }
          echo $form['identifiant']->render($options); ?>
        </div>
        <div class="col-xs-4 col-sm-2 hidden-xs" style="padding-left: 0;">
            <button class="btn btn-default btn-md" type="submit" id="btn_rechercher">Accéder</button>
        </div>
    </div>
    </div>
</form>

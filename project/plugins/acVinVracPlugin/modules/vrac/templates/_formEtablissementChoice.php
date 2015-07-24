    <?php echo $form['identifiant']->renderError(); ?>
    <form method="post" class="form-horizontal" action="<?php echo url_for('vrac_etablissement_selection'); ?>">
        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>
        <div class="col-xs-10">
        <div class="form-group<?php if($form['identifiant']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $form['identifiant']->renderError(); ?>
            
            <?php echo $form['identifiant']->render(array('class' => 'form-control select2 input-lg', 'placeholder' => 'Séléctionner un opérateur')); ?>
        </div>
        </div>
        <div class="col-xs-2">
        <button class="btn btn-default btn-lg" type="submit" id="btn_rechercher">Rechercher</button>
        </div>
    </form>
    <!--<span id="recherche_avancee"><a href="">> Recherche avancée</a></span>-->

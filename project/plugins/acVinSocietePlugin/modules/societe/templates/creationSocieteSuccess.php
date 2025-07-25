<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <li class="active"><a href="">Création d'une société</a></li>
</ol>

<form class="form-horizontal" action="<?php echo url_for('societe_creation'); ?>" method="post">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div id="recherche_societe" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Création d'une société</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group<?php if ($form['raison_sociale']->hasError()): ?> has-error<?php endif; ?>">
                        <?php echo $form['raison_sociale']->renderError(); ?>
                        <?php echo $form['raison_sociale']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                        <div class="col-xs-8"><?php echo $form['raison_sociale']->render(array("class" => "form-control first-focus", "autofocus" => "autofocus")); ?></div>
                    </div>
                    <div class="form-group<?php if ($form['siret']->hasError()): ?> has-error<?php endif; ?>">
                        <?php echo $form['siret']->renderError(); ?>
                        <?php echo $form['siret']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                        <div class="col-xs-4"><?php echo $form['siret']->render(array("class" => "form-control first-focus")); ?></div>
                        <div class="col-xs-offset-4 col-xs-8">
                        <p class="help-block ">Facultatif, permet de rechercher automatiquement les informations depuis le site de l'INSEE</p>
                        </div>
                    </div>
                    <div class="text-right"><button id="btn_rechercher" type="submit" class="btn btn-primary">Créer la société</button></div>
                </div>
            </div>
    </div>
</form>

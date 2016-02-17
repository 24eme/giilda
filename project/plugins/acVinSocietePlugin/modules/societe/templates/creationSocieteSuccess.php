
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane">Page d'accueil > <strong>Création d'une société</strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <h2>Création d'une société</h2>
        <form class="form-horizontal" action="<?php echo url_for('societe_creation'); ?>" method="post">
            <div id="recherche_societe" class="col-md-8 panel panel-default">
                <div class="panel-body">
                <div class="form-group<?php if($form['raison_sociale']->hasError()): ?> has-error<?php endif; ?>">
                    <?php echo $form['raison_sociale']->renderError(); ?>
                    <?php echo $form['raison_sociale']->renderLabel(null, array("class" => "col-xs-6 control-label")); ?>
                    <div class="col-xs-6"><?php echo $form['raison_sociale']->render(array("class"=>"form-control first-focus")); ?></div>
                </div>
                <div class="form-group<?php if($form['type']->hasError()): ?> has-error<?php endif; ?>">
                    <?php echo $form['type']->renderError(); ?>
                    <?php echo $form['type']->renderLabel(null, array('class' => 'col-xs-6 control-label')); ?>
                    <div class="col-xs-6"><?php echo $form['type']->render(); ?></div>
                </div>
                <div class="text-right col-xs-12"><button id="btn_rechercher" type="submit" class="btn btn-success">Créer</button></div>
                </div>
        </form>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>

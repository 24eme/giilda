<ol class="breadcrumb">
    <li><a href="<?php echo url_for('compte_search'); ?>">Contacts</a></li>
    <li class="active"><a href="">Recherche avancée</a></li>
</ol>

<div class="page-header">
    <h2>Recherche avancée</h2>
</div>

<form action="" method="post">
<?php echo $form->renderHiddenFields(); ?>
<?php echo $form->renderGlobalErrors(); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-4">
                <?php echo $form["identifiants"]->renderError(); ?>
                <?php echo $form["identifiants"]->renderLabel("Liste de numéros CVI", array("class" => "col-xs-12 control-label")); ?>
                <div class="col-sm-12">
                    <?php echo $form["identifiants"]->render(array("class" => "form-control", "rows" => 15)); ?>
                </div>
        </div>
    </div>
</div>

<div class="row row-margin row-button" style="margin: 15px 0 0 0;">
    <div class="col-xs-4 text-right">
        <button type="submit" class="btn btn-info" href=""><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Rechercher</button>
    </div>
</div>

</form>
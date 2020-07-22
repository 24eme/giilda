<?php include_partial('subvention/breadcrumb', array('subvention' => $subvention)); ?>

<section id="principal" class="form-horizontal">
    <h1>Récapitulatif de votre dossier de subvention <strong><?php echo $subvention->operation ?></strong></h1>

	<?php include_partial('subvention/recap', array('subvention' => $subvention)); ?>
    
    <div class="row">
        <div class="col-xs-4">
            <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('subvention_etablissement', $subvention->getEtablissement()) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Retour à mon espace</a>
        </div>
        <div class="col-xs-4 text-center">
            <a href="<?php echo url_for('subvention_zip', $subvention) ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span>&nbsp;Télécharger le dossier complet</a>
        </div>
        <div class="col-xs-4 text-right">
            <a href="" class="btn btn-success">Vers le site de la région Occitanie&nbsp;<span class="glyphicon glyphicon-log-out"></span></a>
        </div>
    </div>
</section>

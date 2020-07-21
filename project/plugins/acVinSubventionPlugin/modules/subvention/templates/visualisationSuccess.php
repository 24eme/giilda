<?php include_partial('subvention/breadcrumb', array('subvention' => $subvention)); ?>

<section id="principal" class="form-horizontal">
    <h1>Récapitulatif de votre dossier de subvention <strong><?php echo $subvention->operation ?></strong></h1>

	<?php include_partial('subvention/recap', array('subvention' => $subvention)); ?>
    
    <div class="row text-center">
    	<a class="btn btn-lg btn-primary" href=""><span class="glyphicon glyphicon-save-file"></span>&nbsp;Télécharger le dossier complet</a>
    </div>
    
    <div class="row text-center">
    	<h2 style="margin-bottom: 20px;">Et pour finir</h2>
    </div>
    
    <div class="row text-center">
    	<a class="btn btn-lg btn-warning" href="">Déposer votre dossier sur le site de la région Occitanie</a>
    </div>
    
    <div class="row">
        <div class="col-xs-6">
            <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('subvention_etablissement', $subvention->getEtablissement()) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Retour à mon espace</a>
        </div>
    </div>
</section>

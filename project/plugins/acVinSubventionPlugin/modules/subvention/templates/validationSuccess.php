<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $subvention->identifiant)) ?>"><?php echo $subvention->declarant->nom ?> (<?php echo $subvention->identifiant ?>)</a></li>
    <li class="active"><a href=""><?php if($subvention->isNew()): ?>Ajouter<?php else: ?>Modifier<?php endif; ?></a></li>
</ol>

    <h1><strong>Etape 3</strong> - Validation du dossier</h1>

<form class="form-horizontal" role="form" action="<?php echo url_for("subvention_validation", $subvention) ?>" method="post">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>
    
    <div class="row">
    	<div class="col-xs-12">
    		<h3><?php echo $subvention->declarant->raison_sociale ?> (SIRET n°<?php echo $subvention->declarant->siret ?>)</h3>
			<a href="<?php echo url_for("subvention_pdf", $subvention) ?>" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-file"></span>&nbsp;Vos informations</a>
		</div>
	</div>
    
    <div class="row">
    	<div class="col-xs-12">
    		<h3>Engagements</h3>
        	<?php 
            $engagements = sfConfig::get('subvention_configuration_engagements');
            foreach ($engagements as $key => $libelle):
               if (!isset($form["engagement_$key"])) continue;
            ?>
            <div class="form-group <?php if($form["engagement_$key"]->hasError()): ?>has-error<?php endif; ?>">
            	<div class="col-xs-12">
    				<?php echo $form["engagement_$key"]->renderError() ?>
    			</div>
				<div class="col-xs-12 checkbox">
					<label for="validation_<?php echo "engagement_$key" ?>">
    				<?php echo $form["engagement_$key"]->render() ?>&nbsp;<?php echo $libelle ?>
    				</label>
              	</div>
          	</div>
          	<?php endforeach; ?>
		</div>
    </div>
    
    <div class="row">
    	<div class="col-xs-12">
    		<h3>Commentaire</h3>
        	<div class="form-group <?php if($form['commentaire']->hasError()): ?>has-error<?php endif; ?>">
    			<div class="col-xs-12">
    				<?php echo $form['commentaire']->renderError() ?>
    			</div>
    			<div class="col-xs-10">
    				<?php echo $form['commentaire']->render(array('class' => 'form-control', 'placeholder' => "Détailler les opérations pour lesquelles vous souhaitez utiliser la subvention")) ?>
    			</div>
    		</div>
		</div>
    </div>
    
    <div class="row">
    	<div class="col-xs-12">
    		<h3>Dossier joint</h3>
    		<a href="#" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-file"></span>&nbsp;XLS</a>
    	</div>
    </div>
    <br />
    <br />
    <div class="row row-margin row-button">
        <div class="col-xs-6">
        	<a href="<?php echo url_for('subvention_dossier', $subvention) ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Etape précédente</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-success">Valider le dossier&nbsp;<span class="glyphicon glyphicon-ok"></span></button>
        </div>
    </div>
</form>
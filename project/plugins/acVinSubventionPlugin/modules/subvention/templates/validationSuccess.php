<section id="contenu">

<div class="page-header">
    <h2>Validation du dossier</h2>
</div>

<h2><?php echo $subvention->declarant->raison_sociale ?> (SIRET n°<?php echo $subvention->declarant->siret ?>)</h2>

<a href="#" class="btn btn-lg btn-primary">Vos informations</a>

<form class="form-horizontal" role="form" action="<?php echo url_for("subvention_validation", $subvention) ?>" method="post">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>
    
    <h2>Je m'engage</h2>
    
    <div class="row">
    	<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            	<?php 
                $engagements = sfConfig::get('subvention_configuration_engagements');
	            foreach ($engagements as $key => $libelle):
	               if (!isset($form["engagement_$key"])) continue;
	            ?>
	            <div class="<?php if($form["engagement_$key"]->hasError()): ?>has-error<?php endif; ?>">
	            	<div class="col-xs-8 col-xs-offset-4">
        				<?php echo $form["engagement_$key"]->renderError() ?>
        			</div>
    				<div class="checkbox">
                        <label>
        					<?php echo $form["engagement_$key"]->render() ?>&nbsp;<?php echo $form["engagement_$key"]->renderLabel() ?>
                        </label>
                  	</div>
              	</div>
              	<?php endforeach; ?>
            </div>
		</div>
    </div>
    
    <h2>Commentaire</h2>

    <div class="row">
    	<div class="form-group <?php if($form['commentaire']->hasError()): ?>has-error<?php endif; ?>">
			<div class="col-xs-8 col-xs-offset-4">
				<?php echo $form['commentaire']->renderError() ?>
			</div>
			<div class="col-xs-6">
				<?php echo $form['commentaire']->render(array('class' => 'form-control', 'placeholder' => "Détailler les opérations pour lesquelles vous utiliserez la subvention")) ?>
			</div>
		</div>
    </div>
    
    <h2>Dossier joint</h2>
    
    <a href="#" class="btn btn-lg btn-primary">XLS</a>
    
    <div class="row row-margin row-button">
        <div class="col-xs-6">
        	<a href="<?php echo url_for('subvention_dossier', $subvention) ?>" class="annuler btn btn-default btn-danger">Retour</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-default btn-lg btn-upper">Signer</button>
        </div>
    </div>
</form>

</section>
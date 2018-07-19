<?php use_helper('Date'); ?>
<?php include_partial('dae/preTemplate'); ?>
<ol class="breadcrumb">
    <li><a href="<?php echo url_for('dae') ?>">Activités mensuelle</a></li>
    <li><a href="<?php echo url_for('dae_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->identifiant ?>)</a></li>
    <li><a href="<?php echo url_for('dae_nouveau', array('identifiant' => $etablissement->identifiant)) ?>" class="active">Nouveau</a></li>
</ol>

<div class="col-md-12">

	<h4><?php if ($dae->isNew()): ?>Ajout<?php else: ?>Modification<?php endif; ?> d'un flux de <strong><?php echo ucfirst(format_date($periode->format('Y-m-d'), 'MMMM yyyy', 'fr_FR')) ?></strong></h4>
	
	<div class="col-md-10 col-md-offset-1" style="margin-top: 20px;">
	
	<form class="form-horizontal" action="<?php echo ($dae->isNew())? url_for('dae_nouveau', array('identifiant' => $etablissement->identifiant, 'periode' => $periode->format('Y-m-d'))) : url_for('dae_nouveau', array('identifiant' => $etablissement->identifiant, 'id' => $dae->_id, 'periode' => $periode->format('Y-m-d'))); ?>" method="post">
		<?php
		    echo $form->renderHiddenFields();
		    echo $form->renderGlobalErrors();
	    ?>
	    <div class="panel panel-default">
        	<div class="panel-heading"><h4 class="panel-title">Produit</h4></div>
        	<div class="panel-body">
			    <div class="form-group<?php if($form['produit_key']->hasError()): ?> has-error<?php endif; ?>">
			        <?php echo $form['produit_key']->renderError(); ?>
			        <?php echo $form['produit_key']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
			        <div class="col-xs-10"><?php echo $form['produit_key']->render(); ?></div>
			    </div>
			
			    <div class="form-group">
				    <?php echo $form['label_key']->renderError(); ?>
				    <?php echo $form['millesime']->renderError(); ?>
			    	<div class="<?php if($form['label_key']->hasError()): ?> has-error<?php endif; ?>">
				        <?php echo $form['label_key']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				        <div class="col-xs-4"><?php echo $form['label_key']->render(); ?></div>
			        </div>
			    	<div class="<?php if($form['millesime']->hasError()): ?> has-error<?php endif; ?>">
				        <?php echo $form['millesime']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				        <div class="col-xs-4"><?php echo $form['millesime']->render(); ?></div>
				    </div>
			    </div>
			</div>
		</div>
		<div class="panel panel-default">
        	<div class="panel-heading"><h4 class="panel-title">Acheteur</h4></div>
        	<div class="panel-body">
			    <div class="form-group<?php if($form['no_accises_acheteur']->hasError()): ?> has-error<?php endif; ?>">
			            <?php echo $form['no_accises_acheteur']->renderError(); ?>
			            <?php echo $form['no_accises_acheteur']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
			            <div class="col-xs-10"><?php echo $form['no_accises_acheteur']->render(); ?></div>
			    </div>
			    <div class="form-group">
				    <?php echo $form['type_acheteur_key']->renderError(); ?>
				    <?php echo $form['destination_key']->renderError(); ?>
				    <div class="<?php if($form['type_acheteur_key']->hasError()): ?> has-error<?php endif; ?>">
				            <?php echo $form['type_acheteur_key']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				            <div class="col-xs-4"><?php echo $form['type_acheteur_key']->render(); ?></div>
				    </div>
				    <div class="<?php if($form['destination_key']->hasError()): ?> has-error<?php endif; ?>">
				        <?php echo $form['destination_key']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				        <div class="col-xs-4"><?php echo $form['destination_key']->render(); ?></div>
				    </div>
			    </div>
			</div>
		</div>
		<div class="panel panel-default">
        	<div class="panel-heading"><h4 class="panel-title">Marché</h4></div>
        	<div class="panel-body">
        	
        		<div class="form-group" style="height: 34px;">
				    <?php echo $form['conditionnement_key']->renderError(); ?>
        			<?php echo $form['contenance_key']->renderError(); ?>
				    <div class="<?php if($form['conditionnement_key']->hasError()): ?> has-error<?php endif; ?>">
				        <?php echo $form['conditionnement_key']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				        <div class="col-xs-4 bloc_condition" data-condition-cible="#bloc_contenance|#bloc_hl|#bloc_btl|#bloc_euro_hl|#bloc_euro_btl"><?php echo $form['conditionnement_key']->render(); ?></div>
				    </div>
				    <div class="<?php if($form['contenance_key']->hasError()): ?> has-error<?php endif; ?>" id="bloc_contenance" data-condition-value="bouteille">
				        <?php echo $form['contenance_key']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				        <div class="col-xs-4"><?php echo $form['contenance_key']->render(); ?></div>
				    </div>
			    </div>
			    
			    <div class="form-group">
				    <?php echo $form['quantite']->renderError(); ?>
				    <?php echo $form['prix_unitaire']->renderError(); ?>
				    <div class="<?php if($form['quantite']->hasError()): ?> has-error<?php endif; ?>">
				        <?php echo $form['quantite']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				        <div class="input-group col-xs-4">
				        	<?php echo $form['quantite']->render(); ?>
				        	<div class="input-group-addon" id="bloc_hl" data-condition-value="vrac">hl</div>
				        	<div class="input-group-addon" id="bloc_btl" data-condition-value="bouteille">btl / bib</div>
				        </div>
				    </div>
				    <div class="<?php if($form['prix_unitaire']->hasError()): ?> has-error<?php endif; ?>">
				        <?php echo $form['prix_unitaire']->renderLabel(null, array("class" => "col-xs-2 control-label")); ?>
				        <div class="input-group col-xs-3">
				        	<?php echo $form['prix_unitaire']->render(); ?>
				        	<div class="input-group-addon" id="bloc_euro_hl" data-condition-value="vrac">€ / hl</div>
				        	<div class="input-group-addon" id="bloc_euro_btl" data-condition-value="bouteille">€ / btl / bib</div>
				        </div>
				    </div>
			    
			    </div>
			    
			</div>
		</div>

		<div class="col-xs-6"><a href="<?php echo url_for('dae_etablissement', array('identifiant' => $etablissement->identifiant)) ?>" class="btn btn-default">Annuler</a></div>
	    <div class="col-xs-6 text-right"><button id="btn_valider" type="submit" class="btn btn-success">Valider</button></div>
	    
	</form>
	
	</div>
	
</div>
<?php include_partial('dae/postTemplate'); ?>
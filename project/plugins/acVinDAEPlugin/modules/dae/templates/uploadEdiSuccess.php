<?php use_helper('Date'); ?>
<?php include_partial('dae/breadcrum', array('etablissement' => $etablissement)); ?>

<div class="col-md-12">

	<h4></strong></h4>

	<div class="col-md-12" style="margin-top: 20px;">

	<form action="<?php echo url_for('dae_upload_fichier_edi', array('identifiant' => $identifiant, 'periode' => $periode,'md5' => "0")); ?>" method="post" enctype="multipart/form-data">
		<?php
		    echo $form->renderHiddenFields();
		    echo $form->renderGlobalErrors();
	    ?>
		<div class="panel panel-default">
        	<div class="panel-heading"><h4 class="panel-title">Téléversement de fichier de DAEs</h4></div>
        	<div class="panel-body">
			    <div class="form-group">
					<div class="row>">
						<div class="col-xs-6">
		                    <?php echo $form['file']->renderError(); ?>
		                    <?php echo $form['file']->renderLabel() ?>
		                    <?php echo $form['file']->render(array('class' => 'drmChoixCreation_file')); ?></div>
						</div>

	                    <div class="col-xs-6 text-right"><button id="btn_valider" type="submit" class="btn btn-success">Valider</button></div>
			    </div>
			</div>
		</div>
    </form>

	<div class="panel panel-default">
        	<div class="panel-heading"><h4 class="panel-title">Problèmes recensés</h4></div>
        	<div class="panel-body">
				<table class="table table-bordered table-condensed table-striped">
					<thead>
						<tr>
							<th>Numéro de ligne</th>
							<th>Erreur</th>
							<th>Raison</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($erreurs as $erreur) : ?>
						<tr class="<?php echo ($daeCsvEdi->getCsvDoc()->getStatut() == DRMCsvEdi::STATUT_ERREUR) ? "danger" : "warning"; ?>">
							<td><?php echo $erreur->num_ligne; ?></td>
							<td><?php echo $erreur->csv_erreur; ?></td>
							<td><?php echo $erreur->diagnostic; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		            <a href="<?php echo url_for('dae_creation_fichier_edi', array('periode' => $periode, 'md5' => $md5,'identifiant' => $identifiant)); ?>" class="btn btn-success" style="float: right;" <?php echo ($daeCsvEdi->getCsvDoc()->hasCsvAttachement() && $daeCsvEdi->getCsvDoc()->getStatut() != DAECsvEdi::STATUT_ERREUR)? '' : 'disabled="disabled"'; ?> >Importer les DAE</a>

			</div>
    </div>






	</div>

</div>
<?php include_partial('dae/postTemplate'); ?>

<div id="contrats_vrac" class="clearfix">
	<div class="ajout_annuaire">
		<form id="principal" class="ui-tabs" method="post" action="<?php echo url_for('annuaire_ajouter', array('identifiant' => $identifiant, 'type' => $type, 'tiers' => $tiers)) ?>">
			
			<h2 class="titre_principal">Ajouter un contact</h2>

			<div class="fond clearfix">
				<?php echo $form->renderHiddenFields() ?>
				<?php echo $form->renderGlobalErrors() ?>
	
				<p>Saisissez ici le type et l'identifiant du tiers que vous souhaitez ajouter à votre annuaire.</p><br />
				
				<table class="table_recap" id="">
						<thead>
							<tr>
								<th style="text-align: left; padding-left: 5px;">Type</th>
								<th style="text-align: left; padding-left: 5px;"><span>Identifiant</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align: left; padding-left: 5px;">
									<span><?php echo $form['type']->renderError() ?></span>
									<?php echo $form['type']->render() ?>
								</td>
								<td style="text-align: left; padding-left: 5px;">
									<span><?php echo $form['tiers']->renderError() ?></span>
									<?php echo $form['tiers']->render() ?>
								</td>
							</tr>
						</tbody>
				</table>
	
				<?php if ($tiersObject): ?>
					<h2>INFORMATIONS</h2>
					<ul>
						<li>Nom : <strong><?php echo $tiersObject->nom ?></strong></li>
						<li>N° CVI : <strong><?php echo $tiersObject->cvi ?></strong></li>
						<li>N° ACCISE : <strong><?php echo $tiersObject->no_accises ?></strong></li>
						<li>Téléphone : <strong><?php echo $tiersObject->telephone ?></strong></li>
						<li>Fax : <strong><?php echo $tiersObject->fax ?></strong></li>
						<li>Adresse : <strong><?php echo $tiersObject->siege->adresse ?></strong></li>
						<li>Code postal : <strong><?php echo $tiersObject->siege->code_postal ?></strong></li>
						<li>Commune : <strong><?php echo $tiersObject->siege->commune ?></strong></li>
					</ul>
				<?php endif; ?>
			</div>
			<div style="margin: 10px 0; clear: both;">
	            <a style="float: left;" class="btn_orange btn_majeur" href="<?php echo url_for('annuaire_selectionner', array('identifiant' => $identifiant, 'type' => $type)) ?>">Retour</a>
		    	<button type="submit" name="valider" class="btn_vert btn_majeur" style="cursor: pointer; float: right;">
		    		Valider
		    	</button>
			</div>
		</form>
	</div>
</div>
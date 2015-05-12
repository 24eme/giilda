<div id="principal" class="clearfix">
	<div class="ajout_annuaire">
		<form id="principal" class="ui-tabs" method="post" action="<?php echo url_for('annuaire_selectionner', array('identifiant' => $identifiant)); ?><?php if (isset($redirect)): ?>?redirect=<?php echo $redirect ?><?php endif; ?>">
			<h2 class="titre_principal">Ajouter un contact</h2>
	
			<div class="fond">
				<?php echo $form->renderHiddenFields() ?>
				<?php echo $form->renderGlobalErrors() ?>
				<p>Saisissez ici le type et l'identifiant du tiers que vous souhaitez ajouter à votre annuaire.</p><br />
				<table class="table_recap" id="table_annuaire_selection">
						<thead>
							<tr>
								<th>Type</th>
								<th><span>Identifiant</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
                                                            <?php if($isCourtierResponsable && (isset($form['type']))): ?>
									<span><?php echo $form['type']->renderError() ?></span>
									<?php echo $form['type']->render() ?>
                                                            <?php else: ?>
                                                                            Viticulteur
                                                                        <?php endif; ?>
								</td>
								<td>
									<span><?php echo $form['tiers']->renderError() ?></span>
									<?php echo $form['tiers']->render() ?>
								</td>
							</tr>
						</tbody>
					</table>
			</div>
			<div class="btn_block">
	            <a class="btn_orange btn_majeur" href="<?php echo url_for('annuaire_retour', array('identifiant' => $identifiant)) ?>">Retour</a>
		    	<button type="submit" name="valider" class="btn_vert btn_majeur" >
		    		Valider
		    	</button>
			</div>
		</form>
	</div>
    <?php include_partial('vrac/popup_notices'); ?> 
</div>

<?php

include_partial('vrac/colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));

?>
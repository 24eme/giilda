<?php $libellesForErrorsType = RevendicationErrorException::getLibellesForErrorsType(); ?>
<h2>Rapport d'erreurs :</h2>
<div id="rapport_erreurs">
    <p class="nb_erreurs">
        Nombre d'erreurs total : <?php echo $revendication->getNbErreurs(); ?>
    </p>
	
	<?php foreach ($revendication->erreurs as $type => $erreursType) : ?>
	<div class="type_erreurs">
		<h3><?php echo $libellesForErrorsType[$type]; ?></h3>
		
		<?php foreach ($erreursType as $unmatched_data => $erreurs) : ?>
		<div class="item">
		
			<div class="produit">
				<?php if ($type == RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS): ?>
				<a href="<?php
				echo url_for('revendication_add_alias_to_configuration', array('odg' => $revendication->odg,
				'campagne' => $revendication->campagne,
				'alias' => $unmatched_data)) ?>" class="btn_majeur btn_voir">Trouver le produit</a>
				<?php endif; ?>
				<p><?php echo $erreurs[0]->libelle_erreur; ?></p>
			</div>
		
			<ul class="num_erreurs">
				<?php foreach ($erreurs as $pos => $erreur) : ?>
				<li><a href="#erreur_<?php echo $erreur->num_ligne; ?>"><?php echo $erreur->num_ligne; ?></a></li>
				<?php endforeach; ?>
			</ul>
		
		</div>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
</div>

<h2>Tableau d'erreurs :</h2>
<table id="table_erreurs" class="table_recap">
	<thead>
		<tr>
			<th>N° de ligne</th>
			<th>Libellé de l'erreur</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($revendication->erreurs as $type => $erreursType) :
			foreach ($erreursType as $unmatched_data => $erreurs):
				foreach ($erreurs as $pos => $erreur):
					?>
					<tr id="erreur_<?php echo $erreur->num_ligne; ?>">
						<th><?php echo $erreur->num_ligne; ?></th>
						<td>
							<p class="libelle"><?php echo $erreur->libelle_erreur; ?></p>
							<p><?php echo str_replace('#', ';', $erreur->ligne); ?></p>
						</td>
					</tr>
					<?php
				endforeach;
			endforeach;
		endforeach;
		?>
	</tbody>
</table>
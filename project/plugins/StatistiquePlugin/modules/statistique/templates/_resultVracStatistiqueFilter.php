<table class="table table-striped">
	<thead>
		<tr>
			<th>Visa</th>
			<th>Produit</th>
			<th>Volume (hl)</th>
			<th>Prix unitaire (€)</th>
			<th>Date de saisie</th>
			<th>Date de signature</th>
			<th>Vendeur</th>
			<th>Acheteur</th>
			<th>Courtier</th>
			<th>Statut</th>
		</tr>
	</thead>
		<tbody>
		<?php 
		foreach ($hits as $hit): 
		$item = $hit->getData();
		$etab = null; 
		?>
			<tr>
				<td>
					<a href="<?php echo ($item['doc']['numero_archive'])? url_for("vrac_visualisation", array('numero_contrat' => $item['doc']['numero_contrat'])) : url_for("vrac_redirect_saisie", array('numero_contrat' => $item['doc']['numero_contrat'])); ?>"><?php echo $item['doc']['numero_archive'] ?></a>
				</td>
				<td><?php echo $item['doc']['produit_libelle'] ?></td>
				<td><?php echo number_format($item['doc']['volume_propose'], 2, ',', ' ') ?></td>
				<td><?php echo number_format($item['doc']['prix_unitaire'], 2, ',', ' ') ?></td>
				<td><?php echo ($item['doc']['valide']['date_saisie'])? strftime('%d/%m/%Y', strtotime($item['doc']['valide']['date_saisie'])) : null; ?></td>
				<td><?php echo ($item['doc']['date_signature'])? strftime('%d/%m/%Y', strtotime($item['doc']['date_signature'])) : null; ?></td>
				<td>
					<?php if ($item['doc']['vendeur_identifiant']): ?>
						<a href="<?php echo url_for('drm_etablissement', array('identifiant' => $item['doc']['vendeur_identifiant'])) ?>"><?php echo $item['doc']['vendeur']['nom'] ?></a>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
				<td>
					<?php if ($item['doc']['acheteur_identifiant']): ?>
						<a href="<?php echo url_for('drm_etablissement', array('identifiant' => $item['doc']['acheteur_identifiant'])) ?>"><?php echo $item['doc']['acheteur']['nom'] ?></a>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
				<td>
					<?php if ($item['doc']['mandataire_identifiant']): ?>
						<a href="<?php echo url_for('drm_etablissement', array('identifiant' => $item['doc']['mandataire_identifiant'])) ?>"><?php echo $item['doc']['mandataire']['nom'] ?></a>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
				<td><?php echo $item['doc']['valide']['statut'] ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

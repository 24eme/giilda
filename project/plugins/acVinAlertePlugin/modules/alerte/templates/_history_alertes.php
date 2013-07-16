<div id="toutes_alertes">
	<h2>Historique des alertes (<?php echo $nbResult ?>)</h2>
	
	<?php
	use_helper('Date');
	$statutsWithLibelles = AlerteClient::getStatutsWithLibelles();
	?>

	<?php if(!count($alertesHistorique)): ?>
	<div>
		<span>
			Aucune alerte ouverte
		</span>
	</div>
	
	<?php else: ?>
	<table class="table_recap table_selection">
		<thead>
			<tr>
				<th class="selecteur"><input type="checkbox" /></th>
				<th>Date du statut</th>
				<th>Statut</th>
				<th>Opérateur concerné</th>
				<th>Type d'alerte</th>
				<th>Document concerné</th>
			</tr>
		</thead>
		<tbody>
				<?php foreach ($alertesHistorique as $a) :
				$alerte = $a->getData()->getRawValue();
				$derniereAlerte = array_pop($alerte['statuts']);
			?>   
			<tr>
				<td class="selecteur">
					<?php echo $modificationStatutForm[$alerte['_id']]->renderError(); ?>
					<?php echo $modificationStatutForm[$alerte['_id']]->render() ?> 
				</td>
				<td>
					<?php echo ($derniereAlerte)? format_date($derniereAlerte['date'],'dd/MM/yyyy') : null; ?>
					(Ouv.: <?php echo format_date($alerte['date_creation'],'dd/MM/yyyy'); ?>)
				</td>
				<td><?php echo $statutsWithLibelles[$alerte['statut_courant']]; ?></td>
				<td><?php echo link_to($alerte['declarant_nom'],'alerte_etablissement',
                                        array('identifiant' => $alerte['identifiant'])); ?></td>
				<td><?php echo link_to(AlerteClient::$alertes_libelles[$alerte['type_alerte']],'alerte_modification',
									   array('type_alerte' => $alerte['type_alerte'],
											 'id_document' => $alerte['id_document'])); ?></td>
				<td><?php echo link_to($alerte['libelle_document'], 'redirect_visualisation', array('id_doc' => $alerte['id_document'])); ?></td>
			</tr>
			<?php
			endforeach;
			?>
		</tbody>
	</table> 
	<div id="consultation_pagination">
		<?php if ($page > 1): ?>
		<a class="pagination_link" href="<?php echo url_for('alerte', array('p' => ($page - 1))) ?>&<?php echo esc_raw($consultationFilter) ?>">&lt;&lt;</a>
		<?php endif; ?>
		(<strong><?php echo $page ?></strong>/<?php echo $nbPage ?>)
		<?php if ($page < $nbPage): ?>
		<a class="pagination_link" href="<?php echo url_for('alerte', array('p' => ($page + 1))) ?>&<?php echo esc_raw($consultationFilter) ?>">&gt;&gt;</a>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>
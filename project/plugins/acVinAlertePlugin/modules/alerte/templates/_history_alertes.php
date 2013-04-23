<div id="toutes_alertes">
	<h2>Historique des alertes</h2>
	
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
				<?php foreach ($alertesHistorique as $alerte) :
			?>   
			<tr>
				<td class="selecteur">
					<?php echo $modificationStatutForm[$alerte->id]->renderError(); ?>
					<?php echo $modificationStatutForm[$alerte->id]->render() ?> 
				</td>
				<td>
					<?php echo format_date($alerte->key[AlerteHistoryView::KEY_DATE_ALERTE],'dd/MM/yyyy'); ?>
					(Ouv.: <?php echo format_date($alerte->key[AlerteHistoryView::KEY_DATE_CREATION_ALERTE],'dd/MM/yyyy'); ?>)
				</td>
				<td><?php echo $statutsWithLibelles[$alerte->key[AlerteHistoryView::KEY_STATUT_ALERTE]]; ?></td>
				<td><?php echo link_to($alerte->value[AlerteHistoryView::VALUE_NOM],'alerte_etablissement',
                                        array('identifiant' => $alerte->key[AlerteHistoryView::KEY_IDENTIFIANT])); ?></td>
				<td><?php echo link_to(AlerteClient::$alertes_libelles[$alerte->key[AlerteHistoryView::KEY_TYPE_ALERTE]],'alerte_modification',
									   array('type_alerte' => $alerte->key[AlerteHistoryView::KEY_TYPE_ALERTE],
											 'id_document' => $alerte->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE])); ?></td>
				<td><?php echo link_to($alerte->value[AlerteHistoryView::VALUE_LIBELLE_DOCUMENT], 'redirect_visualisation', array('id_doc' => $alerte->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE])); ?></td>
			</tr>
			<?php
			endforeach;
			?>
		</tbody>
	</table> 
	<?php endif; ?>
</div>
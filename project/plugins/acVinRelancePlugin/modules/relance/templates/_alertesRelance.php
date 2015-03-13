<?php
use_helper('Date');
$statutsWithLibelles = AlerteClient::getStatutsWithLibelles();
?>

	<?php if(!count($alertesARelancer)): ?>
	<div>
		<span>
			Aucune alerte n'est à relancer
		</span>
	</div>
	
	<?php else: ?>
	<table class="table_recap table_selection">
		<thead>
			<tr>
				<th>Identifiant</th>
				<th>Date du statut</th>
				<th>Statut</th>
				<th>Opérateur concerné</th>
				<th>Type d'alerte</th>
				<th>Document concerné</th>
			</tr>
		</thead>
		<tbody>
				<?php foreach ($alertesARelancer as $alerte) :
			?>   
			<tr>
				<td>
					<?php echo $alerte->id; ?>
				</td>
				<td>
					<?php echo format_date($alerte->value[AlerteRelanceView::VALUE_DATE_MODIFICATION],'dd/MM/yyyy'); ?>
					(Ouv.: <?php echo format_date($alerte->value[AlerteRelanceView::VALUE_DATE_CREATION],'dd/MM/yyyy'); ?>)
				</td>
				<td><?php echo $statutsWithLibelles[$alerte->key[AlerteRelanceView::KEY_STATUT]]; ?></td>
				<td><?php echo $etablissement->raison_sociale; ?></td>
				<td><?php echo link_to(AlerteClient::$alertes_libelles[$alerte->key[AlerteRelanceView::KEY_TYPE_ALERTE]],'alerte_modification',
									   array('type_alerte' => $alerte->key[AlerteRelanceView::KEY_TYPE_ALERTE],
											 'id_document' => $alerte->value[AlerteRelanceView::VALUE_ID_DOC])); ?></td>
				<td><?php echo $alerte->value[AlerteRechercheView::VALUE_LIBELLE_DOCUMENT]; ?></td>
			</tr>
			<?php
			endforeach;
			?>
		</tbody>
	</table> 
	<?php endif; ?>
<br/>
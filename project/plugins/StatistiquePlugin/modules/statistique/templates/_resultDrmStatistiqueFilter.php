<?php use_helper('Date'); ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Période</th>
			<th style="width: 110px;">Date</th>
			<th>Etablissement</th>
			<th>Total début</th>
			<th>Total entrées</th>
			<th>Total récolte</th>
			<th>Total sorties</th>
			<th>Total facturable</th>
			<th>Total fin</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	foreach ($hits as $hit): 
	$item = $hit->getData();
	$parameters = array();
	$parameters['identifiant'] = $item['doc']['identifiant'];
	$parameters['periode_version'] = ($item['doc']['version'])? $item['doc']['periode'].'-'.$item['doc']['version'] : $item['doc']['periode'];
	?>
		<tr>
			<td><a href="<?php echo url_for('drm_visualisation', $parameters) ?>"><?php echo $item['doc']['periode'] ?></a></td>
				<td>
					<?php echo ($item['doc']['valide']['date_saisie'])? '<span class="text-muted"><span class="glyphicon glyphicon-check" aria-hidden="true" title="Date de saisie (validation interpro)"></span> ' . strftime('%d/%m/%Y', strtotime($item['doc']['valide']['date_saisie'])) : null; ?>
				</td>
			<td><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $item['doc']['identifiant'])) ?>"><?php echo $item['doc']['declarant']['nom'] ?></a></td>
			<td class="text-right"><?php echo number_format($item['doc']['declaration']['total_debut_mois'], 2, ',', ' ') ?></td>
			<td class="text-right"><?php echo number_format($item['doc']['declaration']['total_entrees'], 2, ',', ' ') ?></td>
			<td class="text-right"><?php echo number_format($item['doc']['declaration']['total_recolte'], 2, ',', ' ') ?></td>
			<td class="text-right"><?php echo number_format($item['doc']['declaration']['total_sorties'], 2, ',', ' ') ?></td>
			<td class="text-right"><?php echo number_format($item['doc']['declaration']['total_facturable'], 2, ',', ' ') ?></td>
			<td class="text-right"><?php echo number_format($item['doc']['declaration']['total'], 2, ',', ' ') ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
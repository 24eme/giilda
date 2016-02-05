<table class="table table-striped">
	<thead>
		<tr>
			<th>Id.</th>
			<th>Période</th>
			<th>Etablissement</th>
			<th>Total début de mois (hl)</th>
			<th>Total entrées (hl)</th>
			<th>Total récolte (hl)</th>
			<th>Total sorties (hl)</th>
			<th>Total facturable (hl)</th>
			<th>Total fin de mois (hl)</th>
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
			<td><a href="<?php echo url_for('drm_visualisation', $parameters) ?>" target="_blank"><?php echo $hit->getId() ?></a></td>
			<td><?php echo $item['doc']['periode'] ?></td>
			<td><a href="<?php echo url_for('drm_etablissement', array('identifiant' => $item['doc']['identifiant'])) ?>" target="_blank"><?php echo $item['doc']['declarant']['nom'] ?></a></td>
			<td><?php echo number_format($item['doc']['declaration']['total_debut_mois'], 2, ',', ' ') ?></td>
			<td><?php echo number_format($item['doc']['declaration']['total_entrees'], 2, ',', ' ') ?></td>
			<td><?php echo number_format($item['doc']['declaration']['total_recolte'], 2, ',', ' ') ?></td>
			<td><?php echo number_format($item['doc']['declaration']['total_sorties'], 2, ',', ' ') ?></td>
			<td><?php echo number_format($item['doc']['declaration']['total_facturable'], 2, ',', ' ') ?></td>
			<td><?php echo number_format($item['doc']['declaration']['total'], 2, ',', ' ') ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
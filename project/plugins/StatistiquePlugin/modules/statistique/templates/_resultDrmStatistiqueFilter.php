<?php use_helper('Date'); ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Statut</th>
			<th>Id.</th>
			<th>Période</th>
			<th style="width: 110px;">Date</th>
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
			<td>
				<?php echo $item['doc']['valide']['statut']; ?>
			</td>
			<td>
				<a href="<?php echo url_for('drm_visualisation', $parameters) ?>"><?php echo $hit->getId() ?></a>
					<br />
                    <?php if($item['doc']['teledeclare']): ?>
                    Télédeclaré
                    <?php endif; ?>
			</td>
			<td><?php echo $item['doc']['periode'] ?></td>
				<td>
					<?php echo ($item['doc']['valide']['date_signee'])? '<span class="glyphicon glyphicon-pencil" aria-hidden="true" title="Date de signature"></span> ' . strftime('%d/%m/%Y', strtotime($item['doc']['valide']['date_signee'])) : null; ?>
					<br />
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
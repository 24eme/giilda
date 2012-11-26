<?php
use_helper('Date');
$statutsWithLibelles = AlerteClient::getStatutsWithLibelles();
?>

<div id="historique_alerte">	
	<h2>Historique de l'alerte</h2>
	
	<table class="table_recap">
		<thead>
		<tr>
			<th>Type d'alerte</th>
			<th>Date du statut</th>
			<th>Commentaire</th>
		</tr>
		</thead>
		<tbody>
				<?php 
				$cpt = count($alerte->statuts)-1;
				while($cpt>=0) :
				$statut = $alerte->statuts[$cpt]
			?>   
			<tr>
				<td><?php echo $statutsWithLibelles[$statut->statut]; ?></td>
				<td><?php echo format_date($statut->date,'dd/MM/yyyy'); ?></td>
				<td class="gauche"><?php echo $statut->commentaire; ?></td>
			</tr>
			<?php
				$cpt--;
			endwhile;
			?>
		</tbody>
		</table>
</div>
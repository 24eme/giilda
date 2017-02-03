<?php
use_helper('Date');
$statutsWithLibelles = AlerteClient::getStatutsWithLibelles();
?>

<div id="historique_alerte">
	<h2>Historique de l'alerte</h2>

	<table class="table table-condensed table-bordered">
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
				$statut = $alerte->statuts[$cpt];
                                 $styleRow = "";
                                $classRow = "";
                                if($statut->statut == AlerteClient::STATUT_FERME){
                                    $styleRow = 'style="opacity: 0.5"';
                                }
                                if($statut->statut == AlerteClient::STATUT_EN_SOMMEIL){
                                    $styleRow = 'style="opacity: 0.5"';
                                }
                                if(($statut->statut == AlerteClient::STATUT_A_RELANCER) || ($statut->statut == AlerteClient::STATUT_A_RELANCER_AR)){
                                    $classRow = 'success';
                                }
                                if($statut->statut == AlerteClient::STATUT_EN_ATTENTE_REPONSE){
                                    $classRow = 'warning';
                                }
                                if($statut->statut == AlerteClient::STATUT_EN_ATTENTE_REPONSE_AR){
                                     $classRow = 'danger';
                                }

			?>
			<tr class="<?php echo $classRow; ?>" <?php echo $styleRow; ?> >
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

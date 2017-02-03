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
	<div>
		<span>
			<?php echo $nbResult ?> alerte<?php if ($nbResult > 1): ?>s<?php endif; ?> trouvée<?php if ($nbResult > 1): ?>s<?php endif; ?>
		</span>
	</div>
	<?php include_partial('history_alertes_pagination', array('page' => $page, 'nbPage' => $nbPage, 'consultationFilter' => $consultationFilter, )); ?>
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
				<?php
                                foreach ($alertesHistorique as $a) :
				$alerteRaw = $a->getHit()->getRawValue();
				$alerteId = $alerteRaw['_id'];
				$alerte = $alerteRaw['_source']['doc'];
				$derniereAlerte = array_pop($alerte['statuts']);
                                $document_link = link_to($alerte['libelle_document'], 'redirect_visualisation', array('id_doc' => $alerte['id_document']));
                                if(($alerte['type_alerte'] == AlerteClient::DRM_MANQUANTE) || ($alerte['type_alerte'] == AlerteClient::DRA_MANQUANTE)){
                                   $document_link = link_to($alerte['libelle_document'], 'drm_etablissement', array('identifiant' => $alerte['identifiant'], 'campagne' => $alerte['campagne']));
                                }
                                $styleRow = "";
                                $classRow = "";
                                if($alerte['statut_courant'] == AlerteClient::STATUT_FERME){
                                    $styleRow = 'style="opacity: 0.5"';
                                }
                                if($alerte['statut_courant'] == AlerteClient::STATUT_EN_SOMMEIL){
                                    $styleRow = 'style="opacity: 0.5"';
                                }
                                if(($alerte['statut_courant'] == AlerteClient::STATUT_A_RELANCER) || ($alerte['statut_courant'] == AlerteClient::STATUT_A_RELANCER_AR)){
                                    $classRow = 'statut_solde';
                                }
                                if($alerte['statut_courant'] == AlerteClient::STATUT_EN_ATTENTE_REPONSE){
                                    $classRow = 'statut_non-solde';
                                }
                                if($alerte['statut_courant'] == AlerteClient::STATUT_EN_ATTENTE_REPONSE_AR){
                                     $classRow = 'statut_annule';
                                }

			?>
                    <tr class="<?php echo $classRow; ?>" <?php echo $styleRow; ?> >
                            <td class="selecteur">
					<?php echo $modificationStatutForm[$alerteId]->renderError(); ?>
					<?php echo $modificationStatutForm[$alerteId]->render() ?>
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
				<td><?php echo $document_link; ?></td>
			</tr>
			<?php
			endforeach;
			?>
		</tbody>
	</table>
	<?php include_partial('history_alertes_pagination', array('page' => $page, 'nbPage' => $nbPage, 'consultationFilter' => $consultationFilter, )); ?>
	<?php endif; ?>
</div>

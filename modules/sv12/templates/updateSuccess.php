<div id="contenu" class="sv12">    
	<!-- #principal -->
	<section id="principal">
		<p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <a href="<?php echo url_for('sv12_etablissement', $sv12->getEtablissementObject()) ?>"><?php echo $sv12->declarant->nom ?></a> &gt; <strong><?php echo $sv12 ?></strong></p>
		
		<!-- #contenu_etape -->
		<section id="contenu_etape">
			<h2>Déclaration SV12</h2>
			
			<!--<p id="num_sv12"><span>N° SV12 :</span> <?php echo $sv12->get('_id') ?></p>-->
			
			<?php include_partial('negociant_infos', array('sv12' => $sv12)); ?>
			
			<script type="text/javascript">	
				var source_tags = <?php echo json_encode($sv12->getContratsWords()->getRawValue()); ?>;
			</script>
			
			<form name="sv12_update" method="POST" action="<?php echo url_for('sv12_update', $sv12); ?>" >
				<?php 
				echo $form->renderHiddenFields();
				echo $form->renderGlobalErrors();
				?>
				
				<fieldset id="edition_sv12">
					<legend>Saisie des volume</legend>
					
					<div id="recherche_sv12">
						<div class="autocompletion_tags" data-table="#table_contrats" data-source="source_tags">
							<label>Saisissez le nom d'un viticulteur ou d'une appellation pour effectuer une recherche dans l'historique ci-dessous :</label>
							
							<ul id="recherche_sv12_tags" class="tags"></ul>
							<!--
							<button class="btn_majeur btn_rechercher" type="button">Rechercher</button>
							-->
						</div>
						
						<div class="volumes_vides">
							<label for="champ_volumes_vides"><input type="checkbox" id="champ_volumes_vides" /> Afficher uniquement les volumes non-saisis</label>
						</div>
					</div>
					<table id="table_contrats" class="table_recap">
						<thead>
							<tr>
								<th style="width: 200px;">Viticulteur </th>
								<th>Produit</th>
								<th>Contrat</th>
								<th>Volume</th>
							</tr>
						</thead>
						<tbody>
							<tr class="vide">
								<td colspan="4">Aucun résultat n'a été trouvé pour cette recherche</td>
							</tr>
							<?php foreach ($sv12->contrats as $contrat) : ?> 
							<tr id="<?php echo $contrat->getHTMLId() ?>">
								<td><?php if ($contrat->vendeur_identifiant): ?><?php echo $contrat->vendeur_nom.' ('.$contrat->vendeur_identifiant.')'; ?><?php else: ?>-<?php endif; ?></td>
								<td><?php echo $contrat->produit_libelle; ?></td>	
								<td>
									<?php if (!$contrat->contrat_numero): ?>
									-
									<?php else: ?>
									<a href="<?php echo url_for(array('sf_route' => 'vrac_visualisation', 'numero_contrat' => $contrat->contrat_numero)) ?>"><?php echo VracClient::getInstance()->getLibelleFromId($contrat->contrat_numero, '&nbsp;') ?></a>
									<?php echo sprintf('(%s, %s hl)', $contrat->contrat_type, $contrat->volume_prop); ?>
									<?php endif; ?>
								</td>
								<td>
									<?php
										echo $form[$contrat->getKey()]->renderError();
										echo $form[$contrat->getKey()]->render();
									?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table> 
				</fieldset>
			
				<fieldset><a class="btn_majeur btn_orange" href="<?php echo url_for('sv12_update_addProduit', $sv12) ?>">Ajouter un produit</a></fieldset>

				<fieldset id="commentaire_sv12">
					<legend>Commentaires</legend>
					<textarea></textarea>
				</fieldset>
			
				<div class="btn_etape">
					<button class="btn_etape_suiv" type="submit"><span>Suivant</span></button>
				</div>
			</form>
		</section>
		<!-- fin #contenu_etape -->
	</section>
	
	<?php include_partial('colonne', array('negociant' => $sv12->declarant)); ?>
	<!-- fin #principal -->
</div>
    
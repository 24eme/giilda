<?php use_helper('Float'); ?>
<?php use_helper('Vrac'); ?>
<?php use_helper('Date'); ?>
<table class="table table-striped">
	<thead>
		<tr>
            <th>&nbsp;</th>
			<th>Visa</th>
            <th style="width: 110px;">Date</th>
            <th>Soussignés</th>
            <th>Produit (Millésime)</th>
            <th>Vol.&nbsp;prop. (Vol.&nbsp;enl.)</th>
            <th>Prix</th>
						<th>&nbsp;</th>
		</tr>
	</thead>
		<tbody>
		<?php
		foreach ($hits as $hit):
		$item = $hit->getData();
		$etab = null;
		?>
			<tr class="<?php echo statusCssClass($item['doc']['valide']['statut']) ?>">
				<td class="text-center"><span class="<?php echo typeToPictoCssClass($item['doc']['type_transaction']) ?>" style="font-size: 24px;"></span></td>
				<td>
					<a href="<?php echo ($item['doc']['valide']['statut'])? url_for("vrac_visualisation", array('numero_contrat' => $item['doc']['numero_contrat'])) : url_for("vrac_redirect_saisie", array('numero_contrat' => $item['doc']['numero_contrat'])); ?>"><?php
					if ($item['doc']['numero_archive']) {
						if (preg_match('/^DRM/', $item['doc']['numero_archive'])) {
							echo tooltipForPicto($item['doc']['type_transaction']);
						}else {
					 		echo $item['doc']['numero_archive'];
						}
					} elseif($item['doc']['valide']['statut'])
						echo "Non visé";
					else echo "Brouillon";
					 ?></a>

                    <br />
                    <?php if($item['doc']['numero_archive']) : if(!preg_match('/^DRM/', $item['doc']['numero_archive'])): ?>
                    <span class="text-muted" style="font-size: 12px;"><?php echo formatNumeroBordereau($item['doc']['numero_contrat']) ?></span>
									  <?php else: ?>
											<span class="text-muted" style="font-size: 12px;">Issu d'une DRM</span>
                    <?php endif; ?>
									<?php endif; ?>
                    <br />
                    <?php if($item['doc']['teledeclare']): ?>
                    Télédeclaré
                    <?php endif; ?>
				</td>
				<td>
					<?php echo ($item['doc']['date_visa'])? '<span class="glyphicon glyphicon-check" aria-hidden="true" title="Date de signature"></span> ' . strftime('%d/%m/%Y', strtotime($item['doc']['date_visa'])) : null; ?>
					<br />
					<?php echo ($item['doc']['date_signature'])? '<span class="text-muted"><span class="glyphicon glyphicon-pencil" aria-hidden="true" title="Date de signature"></span> ' . strftime('%d/%m/%Y', strtotime($item['doc']['date_signature'])) : null; ?>
				</td>
				<td>

			        <?php
			        echo ($item['doc']['vendeur_identifiant']) ?
			                'Vendeur : ' . link_to($item['doc']['vendeur']['nom'], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $item['doc']['vendeur_identifiant'])) : '';
			        ?>
			        <br />
			        <?php
			        echo ($item['doc']['acheteur_identifiant']) ?
			                'Acheteur : ' . link_to($item['doc']['acheteur']['nom'], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $item['doc']['acheteur_identifiant'])) : '';
			            ?>
			        <?php
			            $has_representant = ($item['doc']['representant_identifiant'] != $item['doc']['vendeur_identifiant']) ? $item['doc']['representant_identifiant'] : 0;
			            if ($has_representant) echo '<br/>';
			            echo ($has_representant) ?
			                'Representant : ' . link_to($item['doc']['representant']['nom'], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $item['doc']['representant_identifiant'])) : '';
			            ?>
			        <?php if($item['doc']['mandataire_identifiant']): ?>
			            <br />
			        <?php
			        echo ($item['doc']['mandataire_identifiant']) ?
			                'Courtier : ' . link_to($item['doc']['mandataire']['nom'], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $item['doc']['mandataire_identifiant'])) : '';
			        ?>
			        <?php endif; ?>


				</td>
				<td>
					<?php $produit = ($item['doc']['type_transaction'] == VracClient::TYPE_TRANSACTION_VIN_VRAC || $item['doc']['type_transaction'] == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE)? $item['doc']['produit_libelle'] : $item['doc']['cepage_libelle'];
		            $millesime = $item['doc']['millesime'] ? $item['doc']['millesime'] : 'nm';
		            if ($produit)
		                echo "<strong>$produit</strong> ($millesime)";?>

				</td>
				<td class="text-right">
					<?php
				        if (isset($item['doc']['volume_propose'])) {
				            echoFloat($item['doc']['volume_propose']);
				            echo '&nbsp;'.VracConfiguration::getInstance()->getUnites()[$item['doc']['type_transaction']]['volume_initial']['libelle'].'<br/>';
				            echo '<span class="text-muted">';
				            if ($item['doc']['volume_enleve']) {
				                echoFloat($item['doc']['volume_enleve']);
				                echo '&nbsp;'.VracConfiguration::getInstance()->getUnites()[$item['doc']['type_transaction']]['volume_vigueur']['libelle'];
				            }else{
				                echo '0.00&nbsp;'.VracConfiguration::getInstance()->getUnites()[$item['doc']['type_transaction']]['volume_vigueur']['libelle'];
				            }
				            echo '</span>';
				        }
				        ?>
				</td>

				<td class="text-right">
					<?php
			            if (isset($item['doc']['prix_initial_unitaire'])) {
			                echoFloat($item['doc']['prix_initial_unitaire']);
			                echo "&nbsp;".VracConfiguration::getInstance()->getUnites()[$item['doc']['type_transaction']]['prix_initial_unitaire']['libelle'] ;
			            }
			        ?>
						</td><td>
						<a class="btn btn-default" href="<?php echo ($item['doc']['valide']['statut'])? url_for("vrac_visualisation", array('numero_contrat' => $item['doc']['numero_contrat'])) : url_for("vrac_redirect_saisie", array('numero_contrat' => $item['doc']['numero_contrat'])); ?>">
						<?php if ($item['doc']['numero_archive']) echo "Visualiser"; elseif($item['doc']['valide']['statut']) echo "Non visé"; else "Brouillon";  ?></a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

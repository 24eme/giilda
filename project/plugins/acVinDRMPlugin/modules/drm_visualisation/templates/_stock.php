<?php use_helper('Version'); ?>
<?php use_helper('Float'); ?>
<table class="table_recap">
	<thead>
		<tr>
			<th style="width: 200px;">Produits</td>
			<th>Stock début de mois</th>
			<th>Entrées</th>
			<th>Sorties<?php if(!$isTeledeclarationMode): ?> (Fact.)<?php endif; ?></th>
			<th><strong>Stock fin de mois</strong></th>
		</tr>
	</thead>
	<tbody>
		<?php $details = $drm->getProduitsDetails($isTeledeclarationMode, $typeDetailKey); 
			  $i = 1;
		foreach($details as $detail):
                $i++; ?>
				<tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
					<td><?php echo $detail->getLibelle(ESC_RAW) ?></td>
                    <td class="<?php echo isVersionnerCssClass($detail, 'total_debut_mois') ?>">
						<strong><?php echoFloat($detail->total_debut_mois) ?></strong>&nbsp;<span class="unite">hl</span>
<?php if ($detail->tav) :
		echo "<br/>"; echoFloat($detail->total_debut_mois * $detail->tav /100); echo " hl d'alcool pur";
endif; ?>
					</td>
					<td class="<?php echo isVersionnerCssClass($detail, 'total_entrees') ?>">
						<?php echoFloat($detail->total_entrees) ?>&nbsp;<span class="unite">hl</span>
<?php if ($detail->tav) :
echo "<br/>"; echoFloat($detail->total_entrees * $detail->tav / 100); echo '&nbsp;hlap';
endif; ?>
					</td>
					<td class="<?php echo isVersionnerCssClass($detail, 'total_sorties') ?>">
						<?php echoFloat($detail->total_sorties) ?>&nbsp;<span class="unite">hl</span><?php if(!$isTeledeclarationMode): ?>&nbsp;(<?php echoFloat($detail->total_facturable) ?>&nbsp;<span class="unite">hl</span>)<?php endif; ?>
<?php if ($detail->tav) :
echo "<br/>"; echoFloat($detail->total_sorties * $detail->tav / 100); echo '&nbsp;hlap';
endif; ?>
					</td>
					<td class="<?php echo isVersionnerCssClass($detail, 'total') ?>">
						<strong><?php echoFloat($detail->total) ?></strong>&nbsp;<span class="unite">hl</span>
<?php if ($detail->tav) :
		echo "<br/>"; echoFloat($detail->total * $detail->tav /100); echo " hl d'alcool pur";
endif; ?>
					</td>
				</tr>
	<?php endforeach; ?>
	</tbody>
</table>

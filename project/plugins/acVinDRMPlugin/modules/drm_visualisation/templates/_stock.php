<?php use_helper('Version'); ?>
<?php use_helper('Float'); ?>
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th class="col-xs-4">Produits</td>
			<th class="col-xs-2">Stock début de mois</th>
			<th class="col-xs-2">Entrées</th>
			<th class="col-xs-2">Sorties<?php if(!$isTeledeclarationMode): ?> (Fact.)<?php endif; ?></th>
			<th class="col-xs-2"><strong>Stock fin de mois</strong></th>
		</tr>
	</thead>
	<tbody>
		<?php $details = $drm->getProduitsDetails($isTeledeclarationMode); ?>
		<?php foreach($details as $detail): ?>
			<tr>
				<td><?php echo $detail->getLibelle(ESC_RAW) ?></td>
                <td class="<?php echo isVersionnerCssClass($detail, 'total_debut_mois') ?>"><strong><?php echoFloat($detail->total_debut_mois) ?></strong>&nbsp;<span class="unite">hl</span></td>
				<td class="<?php echo isVersionnerCssClass($detail, 'total_entrees') ?>"><?php echoFloat($detail->total_entrees) ?>&nbsp;<span class="unite">hl</span></td>
				<td class="<?php echo isVersionnerCssClass($detail, 'total_sorties') ?>"><?php echoFloat($detail->total_sorties) ?>&nbsp;<span class="unite">hl</span><?php if(!$isTeledeclarationMode): ?>&nbsp;(<?php echoFloat($detail->total_facturable) ?>&nbsp;<span class="unite">hl</span>)<?php endif; ?></td>
				<td class="<?php echo isVersionnerCssClass($detail, 'total') ?>"><strong><?php echoFloat($detail->total) ?></strong>&nbsp;<span class="unite">hl</span></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

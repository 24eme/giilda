<?php use_helper('Float'); ?>
<?php if(count($recaps) > 0): ?>
<table class="table table-condensed table-striped table-bordered">
    <thead>
        <tr>
            <th>Produit</th>
            <th><?php echo ucfirst(preg_replace('/ /', '&nbsp;', $periode_debut)); ?> (DS)</th>
            <th>Entrées</th>
            <th>dont Rev. (ODG)</th>
            <th>Sorties (Fact.)</th>
	        <th><?php echo ucfirst(preg_replace('/ /', '&nbsp;', $periode_fin)); ?> (DS)</th>
            <th>dont Com.</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($recaps->getRawValue(ESC_RAW) as $recap): ?>
            <tr>
                <td><a class="lien_hamza_style" data-scrollto="#hamza_mouvement" href="#filtre=<?php echo str_replace(' ', '_', $recap['produit']); ?>"><?php echo $recap['produit'] ?></a></td>
                <td>
                    <?php echoFloat($recap['volume_stock_debut']) ?>&nbsp;<?php if($recap['volume_stock_debut_ds'] !== null): ?>(<?php echoFloat($recap['volume_stock_debut_ds']) ?>)<?php else: ?><small class="text-muted">(Abs.)</small><?php endif; ?>    
                </td>
                <td><?php echoFloat($recap['volume_entrees']) ?></td>
                <td>
                    <?php echoFloat($recap['volume_recolte']) ?>&nbsp;<?php if($recap['volume_revendique_drev'] !== null): ?>(<?php echoFloat($recap['volume_revendique_drev']) ?>)<?php else: ?><small class="text-muted">(Abs.)</small><?php endif; ?>
                </td>
                <td><?php echoFloat($recap['volume_sorties']) ?>&nbsp;(<?php echoFloat($recap['volume_facturable']) ?>)</td>
                <td>
                    <?php echoFloat($recap['volume_stock_fin']) ?>&nbsp;<?php if($recap['volume_stock_fin_ds'] !== null): ?>(<?php echoFloat($recap['volume_stock_fin_ds']) ?>)<?php else: ?><small class="text-muted">(Abs.)</small><?php endif; ?> 
                </td>
                <td><?php echoFloat($recap['volume_stock_commercialisable']) ?></td>
            </tr>
        <?php endforeach; ?>
</table>
<?php else: ?>
Pas de stock
<?php endif; ?>
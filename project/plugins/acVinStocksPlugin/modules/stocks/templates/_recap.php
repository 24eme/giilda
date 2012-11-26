<?php use_helper('Float'); ?>

<table class="table_recap">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Stock&nbsp;déb.&nbsp;(DS)</th>
            <th>Entrées</th>
            <th>Sorties</th>
            <th>Rev.&nbsp;(ODG)</th>
            <th>Stock fin&nbsp;(DS)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($recaps->getRawValue(ESC_RAW) as $recap): ?>
            <tr>
                <td><?php echo $recap['produit'] ?></td>
                <td>
                    <?php echoFloat($recap['volume_stock_debut']) ?>&nbsp;<?php if($recap['volume_stock_debut_ds'] !== null): ?>(<?php echoFloat($recap['volume_stock_debut_ds']) ?>)<?php else: ?>(Abs.)<?php endif; ?>    
                </td>
                <td><?php echoFloat($recap['volume_entrees']) ?></td>
                <td><?php echoFloat($recap['volume_sorties']) ?></td>
                <td>
                    <?php echoFloat($recap['volume_revendique']) ?>&nbsp;<?php if($recap['volume_revendique_drev'] !== null): ?>(<?php echoFloat($recap['volume_revendique_drev']) ?>)<?php else: ?>(Abs.)<?php endif; ?>
                </td>
                <td>
                    <?php echoFloat($recap['volume_stock_fin']) ?>&nbsp;<?php if($recap['volume_stock_fin_ds'] !== null): ?>(<?php echoFloat($recap['volume_stock_fin_ds']) ?>)<?php else: ?>(Abs.)<?php endif; ?> 
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
<tbody>

</tbody>
</table>
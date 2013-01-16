<?php use_helper('Float'); ?>
<?php if(count($recaps) > 0): ?>
<table class="table_recap">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Raisin</th>
            <th>Moût</th>
            <th>Vin</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($recaps->getRawValue(ESC_RAW) as $recap): ?>
            <tr>
                <td><a class="lien_hamza_style" data-scrollto="#hamza_mouvement" href="#filtre=<?php echo str_replace(' ', '_', $recap['produit']); ?>"><?php echo $recap['produit'] ?></a></td>
                <td><?php echoFloat($recap['volume_stock_raisin']) ?></td>
                <td><?php echoFloat($recap['volume_stock_mout']) ?></td>
                <td><?php echoFloat($recap['volume_stock_vin']) ?></td>
                <td><?php echoFloat($recap['volume_stock_total']) ?></td>
            </tr>
        <?php endforeach; ?>
</table>
<?php else: ?>
Pas de stock
<?php endif; ?>
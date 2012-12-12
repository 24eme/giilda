<?php use_helper('Float'); ?>

<table class="table_recap">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Raisins</th>
            <th>Moûts</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>(Non implémenté) Anjou rouge</td>
            <td>0.00</td>
            <td>0.00</td>
        </tr>
        <?php foreach($recaps->getRawValue(ESC_RAW) as $recap): ?>
            <tr>
                <td><?php echo $recap['produit'] ?></td>
                <td></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
<tbody>

</tbody>
</table>
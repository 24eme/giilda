<?php use_helper('Float') ?>
<div class="tableau_ajouts_liquidations">
    <table class="tableau_recap">
        <thead>
            <tr>
                <th style="font-weight: bold; border: none;">Produits</th>
                <th style="font-weight: bold; border: none;">Type</th>
                <th style="font-weight: bold; border: none;">Volume</th>
                <th style="font-weight: bold; border: none;">Détail</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        <?php foreach($mouvements as $mouvement): ?>
        <?php $i++; ?>
            <tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
                <td><?php echo $mouvement->produit_libelle ?></td>
                <td><?php echo $mouvement->type_libelle ?></td>
                <td><?php echoFloat($mouvement->volume) ?></td>
                <td><?php echo $mouvement->detail_libelle ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
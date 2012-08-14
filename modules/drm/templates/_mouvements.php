<?php use_helper('Float') ?>
<fieldset id="mouvement_drm">
    <legend>Mouvements</legend>
    <table class="table_recap">
        <thead>
            <tr>
                <th style="width: 200px;">Produits</th>
                <th>Type</th>
                <th>Volume</th>
                <th>Détail</th>
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
</fieldset>
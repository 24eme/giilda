<?php use_helper('Float'); use_helper('Date'); ?>
<table class="table_recap">
    <thead>
        <tr>
            <th style="width: 170px;">Date de modification</th>
            <th style="width: 280px;">Produits</th>
            <th>Type</th>
            <th>Volume</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    <?php foreach($mouvements as $mouvement): ?>
    <?php $i++; ?>
        <tr <?php if($i%2!=0) echo ($mouvement->volume > 0)? ' class="alt"' : 'class="alt"';  ?>>
            <td><?php echo sprintf("%s - %s", ($mouvement->version) ? $mouvement->version : 'M00', format_date($mouvement->date_version));?></td>
            <td><?php echo $mouvement->produit_libelle ?> </td>
            <td><?php echo $mouvement->type_libelle.' '.$mouvement->detail_libelle ?></td>
            <td <?php echo ($mouvement->volume > 0)? ' class="positif"' : 'class="negatif"';?> >
                <?php  echoSignedFloat($mouvement->volume); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
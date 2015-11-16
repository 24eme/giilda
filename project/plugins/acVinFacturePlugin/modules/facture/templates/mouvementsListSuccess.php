<div class="col-xs-12">
    <h2>Mouvements de facture</h2>

</div>
<div class="row">
    <div class="col-xs-12">

        <div class="list-group">
            <?php foreach ($factureMouvementsAll as $factureMouvement): ?>
                <li class="list-group-item col-xs-12">
                    <?php echo $factureMouvement->libelle; ?> <a href="<?php echo url_for('facture_mouvements_edition',array('id' => $factureMouvement->identifiant)); ?>">Modifier</a>
                </li>
            <?php endforeach; ?>
        </div>
    </div>
</div>
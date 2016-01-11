<?php
use_helper('Float');
?>

<div class="col-xs-12">
    <h2>Mouvements de facture</h2>

</div>
<br/>
<div class="row row-margin">
    <div class="col-xs-12">

        <div class="list-group">
            <li class="list-group-item col-xs-12">
                <div class="row">
                    <div class="col-xs-3 text-center lead text-muted">Intitulé / Date</div>
                    <div class="col-xs-2 text-center lead text-muted">Nb mouvements</div>
                    <div class="col-xs-2 text-center lead text-muted">Nb sociétés</div>
                    <div class="col-xs-2 text-center lead text-muted">Rest. à facturer</div>
                    <div class="col-xs-3 text-center lead text-muted">&nbsp;</div>
                </div>
            </li>
            <?php foreach ($factureMouvementsAll as $factureMouvement): ?>
                <li class="list-group-item col-xs-12">
                    <div class="row">
                        <div class="col-xs-3">
                            <span><?php echo $factureMouvement->libelle; ?></span>
                            <span class="pull-right"><?php echo Date::francizeDate($factureMouvement->date); ?></span>
                        </div>
                        <div class="col-xs-2">
                            <span class="pull-right"><?php echo $factureMouvement->getNbMvts();    ?></span>
                        </div>
                        <div class="col-xs-2">
                            <span class="pull-right"><?php echo $factureMouvement->getNbSocietes();    ?></span>
                        </div>
                        <div class="col-xs-2">
                            <span  class="pull-right"><?php echo sprintFloat($factureMouvement->getTotalHtAFacture());    ?>&nbsp;&euro;</span>
                        </div>
                        <div class="col-xs-3">

                            <a href="<?php echo url_for('facture_mouvements_edition', array('id' => $factureMouvement->identifiant)); ?>" class="pull-right btn btn-default">Modifier</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        <a href="<?php echo url_for("facture_mouvements_nouveaux"); ?>" class="btn btn-default">Nouveaux mouvements de factures</a>

    </div>
</div>
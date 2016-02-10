<?php
use_helper('Float');
?>

<div class="col-xs-12">
    <h2>Facturation libre</h2>

</div>
<br/>
<div class="row row-margin">
    <div class="col-xs-12">

        <div class="list-group">
            <li class="list-group-item col-xs-12">
                <div class="row">
                    <div class="col-xs-7 text-center lead text-muted">Intitulé</div>
                    <div class="col-xs-1 text-center text-muted">Date</div>
                    <div class="col-xs-1 text-center text-muted">Nb mouvements</div>
                    <div class="col-xs-1 text-center text-muted">Restant à facturer</div>
                    <div class="col-xs-2 text-center text-muted">&nbsp;</div>
                </div>
            </li>
            <?php foreach ($factureMouvementsAll as $factureMouvement): ?>
                <li class="list-group-item col-xs-12">
                    <div class="row">
                        <div class="col-xs-7">
                            <span><?php echo $factureMouvement->libelle; ?></span>
                        </div>
                        <div class="col-xs-1">
                            <span class="pull-right"><?php echo Date::francizeDate($factureMouvement->date); ?></span>
                        </div>
                        <div class="col-xs-1">
                            <span class="pull-right"><?php echo $factureMouvement->getNbMvts();    ?></span>
                        </div>                       
                        <div class="col-xs-1">
                            <span  class="pull-right"><?php echo sprintFloat($factureMouvement->getTotalHtAFacture());    ?>&nbsp;&euro;</span>
                        </div>
                        <div class="col-xs-2">

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
        <a href="<?php echo url_for("facture"); ?>" class="btn btn-default">Retour à la facturation</a>
        <a href="<?php echo url_for("facture_mouvements_nouveaux"); ?>" class="btn btn-default pull-right">Facturation libre</a>

    </div>
</div>

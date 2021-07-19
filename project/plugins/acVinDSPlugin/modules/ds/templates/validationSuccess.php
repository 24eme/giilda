<?php use_helper('Date'); ?>
<?php use_helper('Float'); ?>
<?php include_partial('ds/preTemplate'); ?>
<?php include_partial('ds/breadcrum', array('etablissement' => $etablissement)); ?>

<section id="principal">
    <?php include_partial('ds/etapes', array('ds' => $ds)); ?>
    <p>Dans cette étape, vous devez controler votre saisie et valider l'ensemble des stocks</p>
    <?php include_partial('ds/recap', array('ds' => $ds)); ?>


    <div class="row" style="margin-top: 20px;">
        <div class="col-xs-6">
            <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('ds_stocks', $ds) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
        </div>
        <div class="col-xs-6 text-right">
            <a onclick="return confirm('Confirmez-vous la validation de votre déclaration')" class="btn btn-success" href="<?php echo url_for('ds_validate', $ds) ?>">Valider la déclaration&nbsp;<span class="glyphicon glyphicon-ok"></span></a>
        </div>
    </div>

</div>

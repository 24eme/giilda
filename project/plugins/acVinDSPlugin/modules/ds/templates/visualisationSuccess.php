<?php use_helper('Date'); ?>
<?php use_helper('Float'); ?>
<?php include_partial('ds/preTemplate'); ?>
<?php include_partial('ds/breadcrum', array('etablissement' => $etablissement)); ?>

<section id="principal">
    <h1>
      <?php echo str_replace('%millesime%', $ds->millesime, DSConfiguration::getInstance()->getTitle()) ?> au <?php echo (format_date($ds->date_stock, 'dd MMMM yyyy', 'fr_FR')) ?>
      <span class="text-muted pull-right"><?php if($ds->teledeclare): ?>Télédéclarée et <?php endif; ?>Validée le <?php echo (format_date($ds->valide->date_signee, 'dd/MM/yyyy', 'fr_FR')) ?></span>
    </h1>

    <?php if($ds->getMaster()->_id != $ds->_id): ?>
        <p class="bg-danger" style="padding: 15px 10px;color:#000;">Cette déclaration que vous visualisez n'est pas la version la plus récente. <a href="<?php echo url_for('ds_visualisation', array('sf_subject' => $ds->getMaster())) ?>">Cliquez ici</a> pour visualiser la dernière version.</p>
    <?php endif; ?>

    <?php include_partial('ds/recap', array('ds' => $ds)); ?>

    <div class="row" style="margin-top: 20px;">
        <div class="col-xs-4">
            <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('ds_etablissement', $etablissement) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Retour</a>
        </div>
        <div class="col-xs-4 text-center">
            <?php if (!$isTeledeclarationMode && $ds->getMaster()->_id == $ds->_id): ?>
            <a onclick="return confirm('Confirmez-vous la réouverture de la déclaration')" class="btn btn-default" href="<?php echo url_for('ds_devalidate', $ds) ?>">Réouvrir la déclaration</span></a>
            <?php endif; ?>
        </div>
        <div class="col-xs-4 text-right">
            <?php if($ds->getMaster()->_id == $ds->_id): ?>
            <a class="btn btn-warning" href="<?php echo url_for('ds_rectifier', $ds) ?>">Rectifier la déclaration</span></a>
          <?php endif; ?>
        </div>
    </div>

</div>

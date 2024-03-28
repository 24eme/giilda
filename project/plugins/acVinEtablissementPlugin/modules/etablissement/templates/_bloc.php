<?php use_helper('Compte') ?>
<?php $compte = $etablissement->getMasterCompte(); ?>
<h4 style="margin-top: 0;">
	<a href="<?php echo url_for('etablissement_visualisation', $etablissement) ?>"><?php echo $etablissement->nom; ?></a>
	<?php if ($compte->isSuspendu()): ?>
    <span class="label label-default pull-right"><small style="font-weight: inherit; color: inherit;"><?php echo $compte->getStatutLibelle(); ?></small></span>
<?php endif; ?>
<?php if ($compte->exist('en_alerte') && $compte->en_alerte): ?><span class="pull-right">⛔</span><?php endif; ?>
</h4>
<?php if($etablissement->famille): ?>
    <span class="col-xs-3 text-muted">Famille&nbsp;:</span><span class="col-xs-9"><?php echo EtablissementFamilles::$familles[$etablissement->famille]; ?></span>
<?php endif; ?>

<?php if($etablissement->cvi): ?>
    <span class="col-xs-3 text-muted">CVI&nbsp;:</span><span class="col-xs-9"><?php echo $etablissement->cvi; ?></span>
<?php endif; ?>


<?php include_partial('compte/blocCoordonnees', array('compte' => $compte)); ?>

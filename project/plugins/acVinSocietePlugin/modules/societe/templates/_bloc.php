<?php use_helper('Compte') ?>
<?php $compte = $societe->getMasterCompte(); ?>
<h4 style="margin-top: 0;">
	<a href="<?php echo url_for('societe_visualisation', $societe) ?>"><?php echo $societe->raison_sociale ?></a>
	<?php if ($compte->isSuspendu()): ?>
    <span class="label label-default pull-right"><small style="font-weight: inherit; color: inherit;"><?php echo $compte->getStatutLibelle(); ?></small></span>
	<?php endif; ?>
	<?php if ($societe->getMasterCompte()->exist('en_alerte') && $societe->getMasterCompte()->en_alerte): ?><span class="pull-right">⛔</span><?php endif; ?>
</h4>
<?php if($societe->siret): ?>
    <span class="col-xs-3 text-muted">SIRET&nbsp;:</span><span class="col-xs-9"><?php echo formatSIRET($societe->siret); ?></span>
<?php endif; ?>

<?php include_partial('compte/blocCoordonnees', array('compte' => $compte, 'forceCoordonnee' => true)); ?>

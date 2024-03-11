<?php use_helper('Compte') ?>
<h4 style="margin-top: 0;">
	<a href="<?php echo url_for('compte_visualisation', $compte) ?>"><?php echo ($compte->nom_a_afficher) ? $compte->nom_a_afficher : $compte->nom; ?></a>
	<?php if ($compte->isSuspendu()): ?>
    <span class="label label-default pull-right"><small style="font-weight: inherit; color: inherit;"><?php echo $compte->getStatutLibelle(); ?></small></span>
<?php endif; ?>
	<?php if ($compte->exist('en_alerte') && $compte->en_alerte): ?><span class="pull-right">⛔</span><?php endif; ?>
</h4>
<?php if($compte->fonction): ?>
    <span class="col-xs-3 text-muted">Fonction&nbsp;:</span><span class="col-xs-9"><?php echo $compte->fonction; ?></span>
<?php endif; ?>

<?php include_partial('compte/blocCoordonnees', array('compte' => $compte)); ?>

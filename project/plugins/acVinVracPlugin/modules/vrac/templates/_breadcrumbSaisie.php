<ol class="breadcrumb">
<?php  if (!isset($isTeledeclarationMode) || !$isTeledeclarationMode) : ?>
    <li><a href="<?php echo url_for('vrac') ?>" class="active">Contrats</a></li>
<?php else: ?>
    <li><a href="<?php echo url_for('vrac_societe', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="active">Contrats</a></li>
<?php  endif; ?>
    <li><a href="" >Saisie d'un nouveau contrat<?php if($vrac->numero_contrat): ?> (n° <?php echo formatNumeroBordereau($vrac->numero_contrat) ?>)<?php  endif; ?></a></li>
</ol>

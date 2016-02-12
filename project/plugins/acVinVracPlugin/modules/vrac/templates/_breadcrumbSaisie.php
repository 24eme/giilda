<ol class="breadcrumb">
    <li><a href="<?php echo url_for('vrac') ?>" class="active">Contrats</a></li>
    <?php if(!$vrac->numero_archive): ?>
    <li><a href="" >Saisie d'un nouveau contrat (n° <?php echo formatNumeroBordereau($vrac->numero_contrat) ?>)</a></li>
    <?php else: ?>
    <li><a href="" class="active">Modification du contrat n° <?php echo $vrac->numero_archive ?></a></li>
    <?php endif; ?>
</ol>
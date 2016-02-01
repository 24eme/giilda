<ol class="breadcrumb">
    <li><a href="<?php echo url_for('vrac') ?>">Page d'accueil</a></li>
    <li><a href="<?php echo url_for('vrac_recherche', array('identifiant' => $etablissement->identifiant)) ?>" class="active"><?php echo $etablissement->nom ?></a></li>
</ol>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1">
        <?php include_component('vrac', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>

    <?php include_partial('contrat_campagne', array('vracs' => $vracs, 'visualisation' => false, 'campagne' => $campagne, 'identifiant' => $identifiant)); ?>

    <div class="col-xs-12">
            <?php if (count($vracs->rows->getRawValue())): ?>
                <?php include_partial('list', array('vracs' => $vracs, 'identifiant' => $identifiant, 'hamza_style' => true)); ?>
            <?php else: ?>
                <p>Il n'existe aucun contrat pour cette recherche</p>
            <?php endif; ?>
    </div>
</div>



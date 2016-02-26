<ol class="breadcrumb">
    <li class="visited"><a href="<?php echo url_for('vrac') ?>">Contrats</a></li>
    <li class="visited"><a href="<?php echo url_for('vrac_recherche', array('identifiant' => $etablissement->identifiant)) ?>" class="active"><?php echo $etablissement->nom ?> (<?php echo $etablissement->identifiant ?>)</a></li>
    <li class="active"><a href="">Campagne <?php echo $campagne ?></a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('vrac', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>
    <div class="col-xs-12">
    <?php include_partial('contrat_campagne', array('vracs' => $vracs, 'visualisation' => false, 'campagne' => $campagne, 'identifiant' => $identifiant)); ?>
    </div>
    <div class="col-xs-12">
            <?php if (count($vracs->rows->getRawValue())): ?>
                <?php include_partial('list', array('vracs' => $vracs, 'identifiant' => $identifiant, 'hamza_style' => true)); ?>
            <?php else: ?>
                <p>Il n'existe aucun contrat pour cette recherche</p>
            <?php endif; ?>
    </div>
</div>



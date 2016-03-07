<div class="list-group">
    <div class="list-group-item">
        <div class="row">
            <h3 style="margin-top: 5px; margin-bottom: 5px;" class="col-xs-10">
                <span class="<?php echo comptePictoCssClass($compte->getRawValue()) ?>"></span> <?php echo ($compte->nom_a_afficher) ? $compte->nom_a_afficher : $compte->nom; ?>
            </h3>
            <h3 style="margin-top: 10px; " class="col-xs-2 text-right">
                <a href="<?php echo url_for('compte_modification', $compte); ?>" class="btn btn-xs btn-default pull-right" <?php echo ($compte->isSuspendu()) ? 'disabled="disabled"' : '' ?> >Modifier</a> 
            </h3>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <span class="label label-primary"><?php echo $compte->fonction; ?></span>&nbsp;
                <?php if ($compte->isSuspendu()): ?>
                    <span class="label label-danger"><?php echo $compte->statut; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-xs-4 text-right">
                <a href="<?php echo url_for('compte_switch_statut', array('identifiant' => $compte->identifiant)); ?>" <?php echo ($compte->getSociete()->isSuspendu()) ? 'disabled="disabled"' : '' ?> class="btn btn-xs <?php echo ($compte->isActif()) ? 'btn-danger' : 'btn-success' ?> "><?php echo ($compte->isActif()) ? 'Suspendre' : 'Activer' ?></a>
            </div>
        </div>

    </div>
    <?php if ($compte->isSameAdresseThanSociete()): ?>
        <div class="list-group-item list-group-item-xs text-center text-muted disabled">
            <em>Même Adresse que la société</em>
        </div>
    <?php else : ?>
        <div class="list-group-item list-group-item-xs text-center ">
            <div class="row">
                <?php include_partial('compte/adresseVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => true)); ?>
            </div>
        </div>        
    <?php endif; ?>
    <?php if ($compte->isSameContactThanSociete()): ?>
        <div class="list-group-item list-group-item-xs text-center text-muted disabled">
            <em>Même contact que la société</em>
        </div>
    <?php else : ?>
        <div class="list-group-item list-group-item-xs text-center ">
            <div class="row">
                <?php include_partial('compte/contactVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => true)); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="list-group-item list-group-item-xs ">
        <?php include_partial('compte/tagsVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => true)); ?>
    </div>

</div>
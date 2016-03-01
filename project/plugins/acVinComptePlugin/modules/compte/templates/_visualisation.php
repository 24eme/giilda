<div class="list-group">
    <div class="list-group-item">
        <h3 style="margin-top: 5px; margin-bottom: 5px;"><span class="<?php echo comptePictoCssClass($compte->getRawValue()) ?>"></span> <?php echo ($compte->nom_a_afficher) ? $compte->nom_a_afficher : $compte->nom; ?>
            <a href="<?php echo url_for('compte_modification', $compte); ?>" class="btn btn-xs btn-default">Modifier</a></h3>
        <span class="label label-primary"><?php echo $compte->fonction; ?></span>
        <?php if($compte->statut == CompteClient::STATUT_SUSPENDU): ?>
            <span class="label label-danger"><?php echo $compte->statut; ?></span>
        <?php endif; ?>
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
    <?php 
    if ($compte->isSameContactThanSociete()): ?>
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
    <div class="list-group-item list-group-item-xs text-center ">
        <?php include_partial('compte/tagsVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => true)); ?>
    </div>

</div>
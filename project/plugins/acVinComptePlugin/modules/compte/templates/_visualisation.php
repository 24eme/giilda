<div class="list-group">
    <div class="list-group-item">
        <h3 style="margin-top: 5px; margin-bottom: 5px;"><span class="<?php echo comptePictoCssClass($compte->getRawValue()) ?>"></span> <?php echo ($compte->nom_a_afficher)? $compte->nom_a_afficher : $compte->nom ;?>
        <a href="<?php echo url_for('compte_modification', $compte); ?>" class="btn btn-xs btn-default">Modifier</a></h3>
        <span class="label label-primary"><?php echo $compte->fonction; ?></span>
        <span class="label label-success"><?php echo $compte->statut; ?></span>
    </div>
    <div class="list-group-item list-group-item-xs <?php if($compte->isSameCoordonneeThanSociete()): ?>text-center text-muted disabled<?php endif; ?>">
        <?php if($compte->isSameCoordonneeThanSociete()): ?>
            <em>Même coordonnées que la société</em>
        <?php else : ?>
            <?php include_partial('compte/coordonneesVisualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => true)); ?>
        <?php endif; ?>
    </div>
</div>
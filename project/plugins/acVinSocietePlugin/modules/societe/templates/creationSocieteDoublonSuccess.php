<?php use_helper('Compte') ?>
<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <li><a href="<?php echo url_for('societe_creation'); ?>"><span class="glyphicon glyphicon-calendar"></span>&nbsp;Création d'une société </a></li>
    <li class="active"><a href="">Sociétés existantes</a></li>
</ol>

    <h3 class="mb-2">Vérification des sociétés existantes : <strong>"<?php echo $raison_sociale; ?>"</strong></h3>

    <p class="mt-4 mb-4">
        <strong><?php echo count($societesDoublons) ?> société(s)</strong> possédant déjà une raison sociale proche ou identique à celle que vous voulez créer ont été trouvée(s) :
    </p>



    <?php foreach ($societesDoublons as $societeDoublee) : ?>
        <ul>
            <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societeDoublee->key[SocieteAllView::KEY_IDENTIFIANT])); ?>">
                <?php echo $societeDoublee->key[SocieteAllView::KEY_RAISON_SOCIALE]; ?> (<?php echo $societeDoublee->key[SocieteAllView::KEY_IDENTIFIANT]; ?>, <?php echo $societeDoublee->key[SocieteAllView::KEY_SIRET] ? formatSIRET($societeDoublee->key[SocieteAllView::KEY_SIRET]) : "Aucun SIRET"; ?>) : <?php if($societeDoublee->key[SocieteAllView::KEY_COMMUNE] || $societeDoublee->key[SocieteAllView::KEY_CODE_POSTAL]): ?><?php echo $societeDoublee->key[SocieteAllView::KEY_COMMUNE]; ?>&nbsp;<?php echo $societeDoublee->key[SocieteAllView::KEY_CODE_POSTAL]; ?><?php else: ?>Aucune adresse<?php endif; ?></a></li>
        </ul>
    <?php endforeach; ?>

    <div class="row mt-5">
        <div class="col-xs-6">
            <a class="btn btn-default" href="<?php echo url_for('societe_creation'); ?>">Annuler</a>
        </div>
        <div class="col-xs-6  text-right">
            <a class="btn btn-primary" href="<?php echo url_for('societe_nouvelle', array('raison_sociale' => $raison_sociale, 'siret' => $siret)); ?>">Continuer et créer la société</a>
        </div>
    </div>
</section>

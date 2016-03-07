<?php use_helper('Etablissement'); ?>

<div class="list-group">
    <div class="list-group-item">
        <div class="row">
            <h2 style="margin-top: 5px; margin-bottom: 5px;" class="col-xs-10"><span class="<?php echo comptePictoCssClass($etablissement->getRawValue()) ?>"></span> <?php echo $etablissement->nom; ?> 
                <small class="text-muted">(n° de chai : <?php echo $etablissement->identifiant; ?>)</small>
            </h2>
            <h2 style="margin-top: 5px; margin-bottom: 5px;"  class="col-xs-2 text-right">
                <a href="<?php echo url_for('etablissement_modification', $etablissement); ?>" class="btn btn-default" <?php echo ($etablissement->isSuspendu()) ? "disabled='disabled'" : ""; ?> >Modifier</a>
            </h2>
        </div>
        <div class="row">
            <div class="col-xs-9">
                <p class="lead" style="margin-bottom: 5px;">
                    <span class="label label-primary"><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille); ?></span>
                    <?php if ($etablissement->isSuspendu()): ?>
                        <span class="label label-danger"><?php echo $etablissement->statut; ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-xs-3 text-right">
                <a href="<?php echo url_for('etablissement_switch_statut', array('identifiant' => $etablissement->identifiant)); ?>" <?php echo ($etablissement->getSociete()->isSuspendu()) ? 'disabled="disabled"' : '' ?>  class="btn btn-xs <?php echo ($etablissement->isActif()) ? 'btn-danger' : 'btn-success' ?> "><?php echo ($etablissement->isActif()) ? 'Suspendre' : 'Activer' ?></a>
            </div>
        </div>            
    </div>
    <?php if ($etablissement->isSameAdresseThanSociete()): ?>
        <div class="list-group-item text-center text-muted disabled">
            <div class="row">
                <em>Même adresse que la société</em>
            </div>
        </div>
    <?php else : ?>
        <div class="list-group-item text-center">
            <div class="row">
                <?php include_partial('compte/adresseVisualisation', array('compte' => $etablissement->getMasterCompte(), 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => false)); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($etablissement->isSameContactThanSociete()): ?>
        <div class="list-group-item text-center text-muted disabled">
            <div class="row">
                <em>Même contact que la société</em>
            </div>
        </div>
    <?php else : ?>
        <div class="list-group-item text-center">
            <div class="row">
                <?php include_partial('compte/contactVisualisation', array('compte' => $etablissement->getMasterCompte(), 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => true)); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="list-group-item <?php echo ($etablissement->isSameCompteThanSociete())? ' text-center text-muted disabled ' : ''; ?>"> 
        <?php if ($etablissement->isSameCompteThanSociete()): ?>
        <div class="row">
                <em>Même tags que la société</em>
            </div>
        <?php else: ?>
            <?php include_partial('compte/tagsVisualisation', array('compte' => $etablissement->getMasterCompte(), 'modification' => $modification, 'reduct_rights' => $reduct_rights, 'smallBlock' => true)); ?>
        <?php endif; ?>
    </div>
    <div class="list-group-item">
        <ul class="list-inline">
            <?php if ($etablissement->recette_locale && $etablissement->recette_locale->nom) : ?>
                <li><attr>Recette locale :</attr> <a href="<?php echo url_for('societe_visualisation', SocieteClient::getInstance()->find($etablissement->recette_locale->id_douane)); ?>">
                    <?php echo $etablissement->recette_locale->nom; ?></a></li>
            <?php endif; ?>
            <?php if ($etablissement->cvi): ?>
                <li>CVI : <?php echo $etablissement->cvi; ?></li>
            <?php endif; ?>
            <?php if ($etablissement->no_accises): ?>
                <li>Numéro d'accises : <?php echo $etablissement->no_accises; ?></li>
            <?php endif; ?>
            <?php if ($etablissement->carte_pro && $etablissement->isCourtier()) : ?>
                <li>Carte professionnelle : <?php echo $etablissement->carte_pro; ?></li>  
            <?php endif; ?>
            <li>Région : <?php echo $etablissement->region; ?></li>
        </ul>

        <?php if ($etablissement->commentaire) : ?>  
            <strong>Commentaires :</strong> <?php echo $etablissement->commentaire; ?>
        <?php endif; ?>
    </div>
</div>


<?php
$typesLiaisons = EtablissementClient::getTypesLiaisons();
if (!isset($fromSociete))
    $fromSociete = false;
?>

<?php use_helper('Etablissement'); ?>

<div class="list-group">
    <div class="list-group-item">
        <h2 style="margin-top: 5px; margin-bottom: 5px;"><span class="<?php echo comptePictoCssClass($etablissement->getRawValue()) ?>"></span> <?php echo $etablissement->nom; ?> 
        <small class="text-muted">(n° de chai : <?php echo $etablissement->identifiant; ?>)</small>
        <a href="<?php echo url_for('etablissement_modification', $etablissement); ?>" class="btn btn-default">Modifier</a></h2>
        <p class="lead" style="margin-bottom: 5px;">
            <span class="label label-primary"><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille); ?></span>
             <?php if($etablissement->statut == EtablissementClient::STATUT_SUSPENDU): ?>
                <span class="label label-danger"><?php echo $etablissement->statut; ?></span>
            <?php endif; ?>
        </p>
    </div>
    <div class="list-group-item <?php if($etablissement->isSameCoordonneeThanSociete()): ?>text-center text-muted disabled<?php endif; ?>">
        <?php if($etablissement->isSameCoordonneeThanSociete()): ?>
            <em>Même coordonnées que la société</em>
        <?php else : ?>
            <?php include_partial('compte/coordonneesVisualisation', array('compte' => $etablissement->getMasterCompte(), 'modification' => $modification, 'reduct_rights' => $reduct_rights)); ?>
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
            <strong>Commentaires :</strong> <?php echo $etablissement->commentaire;?>
        <?php endif; ?>
    </div>
</div>


<?php $typesLiaisons = EtablissementClient::getTypesLiaisons();
if (!isset($fromSociete))
    $fromSociete = false;
?>

<!--<div id="etablissement_<?php echo $etablissement->identifiant; ?>" class="etablissement form_section ouvert">
    <h3><span class="<?php echo comptePictoCssClass($etablissement->getRawValue()) ?>"></span> <?php echo $etablissement->nom; ?></h3>
    <div class="form_contenu">
        <div class="form_modifier">
            <?php if ($fromSociete) : ?>
                <a id="btn_modifier" href="<?php echo url_for('etablissement_visualisation', $etablissement); ?>" class="btn_majeur btn_voir">Voir Etablissement</a>
            <?php endif; ?>
        </div>
        <div class="form_ligne">
            <label for="famille">
                Type établissement :
            </label>
            <?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille); ?>
        </div>
        <div class="form_ligne"> 
            <label for="nom">
                Nom du chai :
            </label>
            <?php echo $etablissement->nom; ?>
        </div>
        <div class="form_ligne">
            <label for="statut">
                Statut :</label>
            <?php echo $etablissement->statut; ?>
        </div>
        <?php if ($etablissement->recette_locale && $etablissement->recette_locale->nom) : ?>
            <div class="form_ligne">
                <label for="recette_locale">
                    Recette locale : 
                </label>
                <a href="<?php echo url_for('societe_visualisation', SocieteClient::getInstance()->find($etablissement->recette_locale->id_douane)); ?>">
                    <?php echo $etablissement->recette_locale->nom; ?>
                </a>
            </div>  
        <?php endif; ?>
        <?php if (!$fromSociete && $etablissement->relance_ds) : ?>
            <div class="form_ligne"> 
                <label for="relance_ds">
                    Relance DS : 
                </label>
                <?php echo $etablissement->relance_ds; ?>
            </div>  
        <?php endif; ?>
        <?php if (!$fromSociete && $etablissement->raisins_mouts) : ?>
            <div class="form_ligne">                   
                <label for="raisins_mouts">
                    Raisins et moûts : 
                </label>
                <?php echo $etablissement->raisins_mouts; ?>
            </div>  
        <?php endif; ?>         
        <?php if (!$fromSociete && $etablissement->exclusion_drm) : ?>
            <div class="form_ligne"> 
                <label for="exclusion_drm">
                    Exclusion DRM : 
                </label>
                <?php echo $etablissement->exclusion_drm; ?>
            </div>  
        <?php endif; ?>
        <?php if (!$fromSociete && $etablissement->type_dr && !$etablissement->isCourtier()) : ?>
            <div class="form_ligne"> 
                <label for="type_dr">
                    Type DR : 
                </label>
                <?php echo $etablissement->type_dr; ?>
            </div>  
        <?php endif; ?>
        <?php if ($etablissement->cvi): ?>
            <div class="form_ligne"> 
                <label for="cvi">
                    CVI :</label>
                <?php echo $etablissement->cvi; ?>
            </div>  
        <?php endif; ?>
        <?php if ($etablissement->no_accises): ?>
            <div class="form_ligne"> 
                <label for="no_accises">
                    Numéro d'accises :</label>
                <?php echo $etablissement->no_accises; ?>
            </div>  
        <?php endif; ?>
        <?php if (!$fromSociete && $etablissement->site_fiche): ?>
            <div class="form_ligne"> 
                <label for="site_fiche">
                    Site fiche :</label>
                <a href="<?php echo $etablissement->site_fiche; ?>"><?php echo $etablissement->site_fiche; ?></a>
            </div>  
        <?php endif; ?>        
        <?php if (!$fromSociete && $etablissement->carte_pro && $etablissement->isCourtier()) : ?>
            <div class="form_ligne"> 
                <label for="carte_pro">
                    Carte professionnelle : 
                </label>
                <?php echo $etablissement->carte_pro; ?>
            </div>  
        <?php endif; ?>
        <div class="form_ligne">
            <label for="region">
                Région :
            </label>
            <?php echo $etablissement->region; ?>
        </div>
        <div class="form_ligne">
            <label for="commune">
                Ville :
            </label>
            <?php echo $etablissement->siege->commune; ?>
        </div>
        <div class="form_ligne"> 
            <label for="adresse_societe">
                Adresse de la société :</label>
            <?php echo display_adresse_societe($etablissement); ?>
        </div>  
        <?php if (!$fromSociete && $etablissement->commentaire): ?>
            <div class="form_ligne"> 
                <label for="commentaire">
                    Commentaire :</label>
                <?php echo $etablissement->commentaire; ?>
            </div>  
        <?php endif; ?>

        <?php if (!$fromSociete && count($etablissement->liaisons_operateurs) > 0) : ?> 
            <div class="form_ligne">
                <legend>
                    Liaisons opérateurs :
                </legend>
            </div>
            <?php foreach ($etablissement->liaisons_operateurs as $liaison_operateur): ?>
                <div class="form_ligne">
                    <div class="form_colonne">

                        <label for="libelle_etablissement"><?php echo $typesLiaisons[$liaison_operateur->type_liaison]; ?></label>     
                        <a href="<?php echo url_for('etablissement_visualisation', EtablissementClient::getInstance()->find($liaison_operateur->id_etablissement)); ?>">
                            <?php echo $liaison_operateur->libelle_etablissement; ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>     
    </div>
</div>-->

<?php
use_helper('Float');
use_helper('Orthographe');
use_helper('Vrac');
use_helper('Date');
use_helper('DRM');

$isValidation = (is_null($vrac->valide->statut));
$isPrixVariable = (!$vrac->prix_total);
$liClass = ($isValidation) ? '' : 'class="lightpadding"';
$template_validation = (isset($template_validation)) ? $template_validation : false;
?>

    <div class="col-xs-12">

        <?php if (!$vrac->isVise()) : ?>
            <p>
                <span class="<?php echo typeToPictoCssClass($vrac->type_transaction) ?>" style="font-size: 24px;"><?php echo "&nbsp;Contrat de " . showType($vrac); ?></span>
            </p>
        <?php endif; ?>
    </div>

    <?php
    if ($vrac->mandataire_identifiant != null && $vrac->mandataire_exist) {
        $colsize = 4;
    } else {
        $colsize = 6;
    }
    ?>
    <div class="col-xs-<?php echo $colsize; ?>">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Vendeur</strong> <?php if ($vrac->responsable == 'vendeur'): ?><span class="glyphicon glyphicon-user text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>&nbsp;<?php endif; ?><?php if ($template_validation): ?><a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-xs btn-default pull-right" autofocus="autofocus">Modifier</a><?php endif; ?></div>
            <div class="text-center panel-body">
            	<?php if (!$isTeledeclarationMode): ?><a href="<?php echo url_for('vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $vrac->vendeur_identifiant)) ?>"><?php endif; ?>
                <strong><?php echo $vrac->getVendeurObject()->getNom(); ?></strong>
				<?php if (!$isTeledeclarationMode): ?></a><?php endif; ?>
                <small class="text-muted"><?php echo $vrac->getVendeurObject()->identifiant ?></small>
				<br/>
                <?php echo $vrac->getVendeurObject()->siege->adresse; ?> - 
                <?php echo $vrac->getVendeurObject()->siege->code_postal; ?>
                <?php echo $vrac->getVendeurObject()->siege->commune; ?><br/>
    <small class="text-muted">CVI&nbsp;: <?php echo $vrac->getVendeurObject()->cvi; ?> / SIRET&nbsp;: <?php echo $vrac->getVendeurObject()->getSociete()->siret ?></small>
                <br />
                <?php if ($vrac->representant_identifiant != $vrac->vendeur_identifiant): ?>Representé par <a href="<?php echo url_for('vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $vrac->representant_identifiant)) ?>"><?php echo $vrac->getRepresentantObject()->getNom(); ?></a><br /><?php endif; ?>
                <?php if ($vrac->logement): ?>Logement du vin : <?php echo $vrac->logement ?><br/><?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($vrac->mandataire_identifiant != null && $vrac->mandataire_exist): ?>
        <div class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Mandataire / Courtier</strong> <?php if ($vrac->responsable == 'mandataire'): ?><span class="glyphicon glyphicon-user text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>&nbsp;<?php endif; ?><?php if ($template_validation): ?><a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
                <div class="text-center panel-body">
                    <?php if (!$isTeledeclarationMode): ?><a href="<?php echo url_for('vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $vrac->mandataire_identifiant)) ?>"><?php endif; ?>
                	<strong><?php echo $vrac->getMandataireObject()->getNom(); ?></strong>
					<?php if (!$isTeledeclarationMode): ?></a><?php endif; ?>
                <small class="text-muted"><?php echo $vrac->getMandataireObject()->identifiant ?></small>
					<br />
                    <?php echo $vrac->getMandataireObject()->siege->adresse; ?> - 
                    <?php echo $vrac->getMandataireObject()->siege->code_postal; ?>
                    <?php echo $vrac->getMandataireObject()->siege->commune; ?><br/>
    <small class="text-muted">SIRET&nbsp;: <?php echo $vrac->getMandataireObject()->getSociete()->siret ?></small>
                    <br />
                    <?php if ($vrac->representant_identifiant != $vrac->vendeur_identifiant): ?><br /><?php endif; ?>
                    <?php if ($vrac->logement): ?><br/><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-xs-<?php echo $colsize; ?>">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Acheteur</strong> <?php if ($vrac->responsable == 'acheteur'): ?><span class="glyphicon glyphicon-user text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>&nbsp;<?php endif; ?><?php if ($template_validation): ?><a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <div class="text-center panel-body">                
                    <?php if (!$isTeledeclarationMode): ?><a href="<?php echo url_for('vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $vrac->acheteur_identifiant)) ?>"><?php endif; ?>
                	<strong><?php echo $vrac->getAcheteurObject()->getNom(); ?></strong>
					<?php if (!$isTeledeclarationMode): ?></a><?php endif; ?>
                <small class="text-muted"><?php echo $vrac->getAcheteurObject()->identifiant ?></small>
					<br />
                <?php echo $vrac->getAcheteurObject()->siege->adresse; ?> -
                <?php echo $vrac->getAcheteurObject()->siege->code_postal; ?>
                <?php echo $vrac->getAcheteurObject()->siege->commune; ?><br/>
        <small class="text-muted">SIRET&nbsp;: <?php echo $vrac->getAcheteurObject()->getSociete()->siret ?></small>

                <br />
                <?php if ($vrac->representant_identifiant != $vrac->vendeur_identifiant): ?><br /><?php endif; ?>
                <?php if ($vrac->logement): ?><br/><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Produit</strong><?php if ($template_validation) : ?><a href="<?php echo url_for('vrac_marche', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <div class="panel-body">
                <div class="row col-xs-8">
                    <div class="row col-xs-12 text-center">
                        <?php if (in_array($vrac->type_transaction, array(VracClient::TYPE_TRANSACTION_VIN_VRAC, VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE))): ?>
                            <h3><?php echo $vrac->produit_libelle ?> <small><?php echo ($vrac->millesime) ? $vrac->millesime : 'Non millésimé'; ?><?php if ($vrac->get('millesime_85_15')): ?> (85/15)<?php endif; ?></small></h3>
                            <?php if ($vrac->cepage_libelle): ?>
                                Cépage : <strong><?php echo $vrac->cepage_libelle ?><?php if ($vrac->get('cepage_85_15')): ?> (85/15)<?php endif; ?></strong><br />
                            <?php endif; ?>
                        <?php else: ?>
                            <h3><?php echo $vrac->cepage_libelle ?> <small><?php if ($vrac->get('cepage_85_15')): ?> (85/15)<?php endif; ?></small></h3>
                            <?php if ($vrac->produit_libelle): ?>
                                Revendiquable en <strong><?php echo $vrac->produit_libelle ?></strong><br />
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                        $haslabel = 0;
                        foreach ($vrac->label as $label):
                            echo ($haslabel++) ? ', ' : '';
                            echo $label;
                        endforeach;
                        ?>
                    </div>
                    <div class="row col-xs-12 text-center">
                        <?php if ($vrac->jus_quantite || $vrac->raisin_quantite || $vrac->prix_initial_unitaire): ?>
                            <h3>
                                <?php if ($vrac->jus_quantite): ?><?php echo $vrac->jus_quantite ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['jus_quantite']['libelle'] ?><?php endif; ?>
                                <?php if ($vrac->raisin_quantite): ?><?php echo $vrac->raisin_quantite ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['raisin_quantite']['libelle'] ?><?php endif; ?>
                                <?php if ($vrac->type_transaction == VracClient::TYPE_TRANSACTION_RAISINS && !$vrac->raisin_quantite && $vrac->surface): ?><?php echo $vrac->surface ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['surface']['libelle'] ?><?php endif; ?>
                                <?php if ($vrac->prix_initial_unitaire): ?> <small>à</small> <?php echo $vrac->prix_initial_unitaire ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['prix_initial_unitaire']['libelle'] ?><?php endif; ?>
                            </h3>
                        <?php endif; ?>
                        <?php if ($vrac->exist('volume_enleve') && $vrac->get('volume_enleve') && $vrac->valide->statut != VracClient::STATUS_CONTRAT_SOLDE): ?>
                            <p><?php echoFloat($vrac->volume_propose - $vrac->volume_enleve); ?> hl restant à enlever</p>
                        <?php elseif ($vrac->exist('volume_enleve') && $vrac->get('volume_enleve') && $vrac->valide->statut == VracClient::STATUS_CONTRAT_SOLDE): ?>
                            <p>Soldé (<?php echoFloat($vrac->volume_propose - $vrac->volume_enleve); ?> hl restant)</p>
                        <?php else: ?>
                            <p>Pas d'enlevement enregistré</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row col-xs-4">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>Compléments</strong></div>
                            <ul class="list-group">
                                <li class="list-group-item"><strong><?php echo VracConfiguration::getInstance()->getCategories()[$vrac->categorie_vin]; ?><?php if ($vrac->domaine): ?>&nbsp;:<?php echo $vrac->domaine; ?><?php endif; ?></strong></li>
                                <?php if ($vrac->lot): ?>
                                    <li class="list-group-item">Lot : <strong><?php echo $vrac->lot ?></strong></li>
                                <?php endif; ?>
                                <?php if ($vrac->degre || $vrac->surface || $vrac->bouteilles_contenance_libelle): ?>
                                    <li class="list-group-item">
                                        <?php if ($vrac->degre): ?>Degré : <strong><?php echo $vrac->degre ?></strong>°<br /><?php endif; ?>
                                        <?php if ($vrac->surface): ?>Surface : <strong><?php echo $vrac->surface ?></strong> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['surface']['libelle'] ?><br /><?php endif; ?>
                                        <?php if ($vrac->bouteilles_contenance_libelle): ?>Contenance : <strong><?php echo $vrac->bouteilles_contenance_libelle ?></strong><?php endif; ?>
                                    </li>
                                <?php endif; ?>
				<?php if ($isValidation) : ?>
                               <li class="list-group-item">Date de signature : <strong><?php echo format_date($vrac->date_signature, "dd/MM/yyyy", "fr_FR"); ?></strong></li>
				<?php endif; ?>
                               <li class="list-group-item">Campagne viticole : <strong><?php echo $vrac->campagne; ?></strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Les conditions</strong> <?php if ($template_validation) : ?><a href="<?php echo url_for('vrac_condition', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <ul class="list-group">


                <?php if ($vrac->delai_paiement_libelle || $vrac->moyen_paiement_libelle || $vrac->acompte || $vrac->courtage_taux || $vrac->tva): ?>
                    <li class="list-group-item clearfix">
                        <span class="col-xs-6">
                            <?php if ($vrac->delai_paiement_libelle): ?>Paiement : <strong><?php echo $vrac->delai_paiement_libelle; ?></strong><?php endif; ?>
                            <?php if ($vrac->moyen_paiement_libelle): ?>(<strong><?php echo $vrac->moyen_paiement_libelle; ?></strong>)<?php endif; ?>
                            <br />
                            <?php if ($vrac->tva): ?>Facturation <strong><?php echo VracConfiguration::getInstance()->getTva()[$vrac->tva] ?></strong><?php endif; ?>
                        </span>
                        <span class="col-xs-6">
                            <?php if ($vrac->courtage_taux): ?>Taux de courtage : <strong><?php echo $vrac->courtage_taux ?></strong>% (<?php if ($vrac->courtage_repartition): ?><strong><?php echo VracConfiguration::getInstance()->getRepartitionCourtage()[$vrac->courtage_repartition] ?></strong><?php endif; ?>)<?php endif; ?>
                            <br />
                            <?php if ($vrac->acompte): ?>Acompte : <strong><?php echo $vrac->acompte ?></strong>€<?php endif; ?>
                        </span>         
                    </li>
                <?php endif; ?>
                <?php if ($vrac->date_limite_retiraison || $vrac->date_debut_retiraison || $vrac->clause_reserve_propriete): ?>
                    <li class="list-group-item clearfix">
                        <span class="col-xs-6">
                            <?php if ($vrac->date_debut_retiraison): ?>Date début de retiraison : <strong><?php echo format_date($vrac->date_debut_retiraison, 'D') ?></strong><?php endif; ?>
                            <br />
                            <?php if ($vrac->clause_reserve_propriete): ?>
                                <strong>Clause de reserve de propriété</strong>
                            <?php endif; ?>
                        </span>
                        <span class="col-xs-6">
                            <?php if ($vrac->date_limite_retiraison): ?>Date limite de retiraison : <strong><?php echo format_date($vrac->date_limite_retiraison, 'D') ?></strong><?php endif; ?>
                        </span>
                    </li>
                <?php endif; ?>
                <?php if ($vrac->pluriannuel): ?>
                    <li class="list-group-item clearfix">
                        <span class="col-xs-6">
                            <strong>Contrat pluriannuel</strong> <?php if ($vrac->annee_contrat): ?>(<strong>Année <?php echo $vrac->annee_contrat ?></strong>)<?php endif; ?><br />
                        </span>
                        <span class="col-xs-6">
                            <?php if ($vrac->seuil_revision): ?>Seuil de révision du prix : <strong><?php echo $vrac->seuil_revision ?></strong>%<br /><?php endif; ?>
                            <?php if ($vrac->pourcentage_variation): ?>Variation max. du volume : <strong><?php echo $vrac->pourcentage_variation ?></strong>%<br /><?php endif; ?>
                            <?php if ($vrac->reference_contrat): ?>Référence au contrat : <strong><?php echo $vrac->reference_contrat ?></strong><?php endif; ?>
                        </span>
                    </li>
                <?php endif; ?>
                <?php if ($vrac->autorisation_nom_vin || $vrac->autorisation_nom_producteur || $vrac->cahier_charge): ?>
                    <li class="list-group-item clearfix">
                        <span class="col-xs-6">
                            <?php if ($vrac->autorisation_nom_vin): ?><strong>Autorisation d'utilisation du nom du vin</strong><?php endif; ?><br />
                            <?php if ($vrac->cahier_charge): ?><strong>Présence d'un cachier des charges entre le vendeur et l'acheteur</strong>><?php endif; ?>
                        </span>
                        <span class="col-xs-6">
                            <?php if ($vrac->autorisation_nom_producteur): ?><strong>Autorisation d'utilisation du nom du producteur</strong><?php endif; ?>
                        </span>
                    </li>
                <?php endif; ?>
                <?php if ($vrac->preparation_vin || $vrac->embouteillage || $vrac->conditionnement_crd): ?>
                    <li class="list-group-item clearfix">
                        <span class="col-xs-6 ">
                            <?php if ($vrac->preparation_vin): ?>Préparation du vin : <strong><?php echo VracConfiguration::getInstance()->getActeursPreparationVin()[$vrac->preparation_vin] ?></strong><?php endif; ?><br />
                            <?php if ($vrac->conditionnement_crd): ?>Conditionnement CRD : <strong><?php echo VracConfiguration::getInstance()->getConditionnementsCRD()[$vrac->conditionnement_crd] ?></strong><?php endif; ?>
                        </span>
                        <span class="col-xs-6">
                            <?php if ($vrac->embouteillage): ?>Mise en bouteille : <strong><?php echo VracConfiguration::getInstance()->getActeursEmbouteillage()[$vrac->embouteillage] ?></strong><?php endif; ?>
                        </span>
                    </li>
                <?php endif; ?>

                <?php if ($vrac->conditions_particulieres): ?>
                    <li class="list-group-item clearfix">
                        <span class="col-xs-12">
                            <?php if ($vrac->conditions_particulieres): ?>Observations : <strong><?php echo $vrac->conditions_particulieres ?></strong><?php endif; ?>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <?php if (!$template_validation): ?>
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Enlèvements depuis les DRM</strong> </div>
                    <?php if (count($enlevements)): ?>
                <ul class="list-group">               
                        <?php foreach ($enlevements as $mvt_id => $enlevement): ?>
                          
                                <li class="list-group-item clearfix">
                                    <div class="row">
                                        <span class="col-xs-6">
                                            <strong><a href="<?php echo url_for('drm_redirect_to_visualisation', array('identifiant_drm' => $enlevement->drm_id)); ?>"> <?php echo "DRM " . getFrPeriodeElision($enlevement->periode); ?></a></strong>
                                        </span>
                                        <span class="col-xs-6 text-right">
                                                <?php echoFloat($enlevement->volume) ; echo " hl"; ?>
                                        </span>
                                    </div>
                                </li> 
                        <?php endforeach; ?> 
			<?php else: ?>
                </ul>
            <?php endif; ?>
                <?php if (count($enlevements)): ?>
                <div class="panel-footer">
                    <div class="row">
                        <strong class="col-xs-6">
                            TOTAL
                        </strong>
                        <strong class="col-xs-6 text-right">
                                <?php echoFloat($vrac->volume_enleve) ?> hl
                        </strong>
                    </div>
                </div>
                <?php else: ?>
            <div class="panel-body text-center text-muted">
                <i>Pas d'enlèvement enregistré pour le moment sur ce contrat</i>
            </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

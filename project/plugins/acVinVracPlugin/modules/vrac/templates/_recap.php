<?php use_helper('Float'); use_helper('Vrac'); use_helper('Date'); ?>
<?php
$isValidation = (is_null($vrac->valide->statut));
$isPrixVariable = (!$vrac->prix_total);
$liClass = ($isValidation) ? '' : 'class="lightpadding"';
$template_validation = (isset($template_validation))? $template_validation : false;
?>

<div class="row">
    	<div class="col-xs-12">
    	
        	<?php if (!$vrac->isVise()) : ?>
        	<p>
            	<span class="<?php echo typeToPictoCssClass($vrac->type_transaction) ?>" style="font-size: 24px;"><?php echo "&nbsp;Contrat de " . showType($vrac); ?></span>
            </p>
            <?php endif;?>
    	</div>
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">1. Soussignés <?php if($template_validation): ?><a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <ul class="list-group">
                <li class="list-group-item clearfix">
                	<div class="row col-xs-4">
                	<?php if ($vrac->responsable == 'vendeur'): ?><span class="glyphicon glyphicon-user text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>&nbsp;<?php endif; ?>Vendeur : <a href=""><?php echo $vrac->getVendeurObject()->getNom(); ?></a><?php if ($vrac->representant_identifiant != $vrac->vendeur_identifiant): ?><br />Representé par <a href=""><?php echo $vrac->getRepresentantObject()->getNom(); ?></a><?php endif; ?><?php if($vrac->logement): ?><br />Logement du vin : <?php echo $vrac->logement ?><?php endif; ?>
                	</div>
                	<div class="row col-xs-4">
                	<?php if ($vrac->responsable == 'acheteur'): ?><span class="glyphicon glyphicon-user text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>&nbsp;<?php endif; ?>Acheteur : <a href=""><?php echo $vrac->getAcheteurObject()->getNom(); ?></a>
                	</div>
                	<div class="row col-xs-4">
	                <?php if ($vrac->mandataire_identifiant != null && $vrac->mandataire_exist): ?>
	                    <?php if ($vrac->responsable == 'mandataire'): ?><span class="glyphicon glyphicon-user text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>&nbsp;<?php endif; ?>Mandataire / Courtier : <a href=""><?php echo $vrac->getMandataireObject()->getNom(); ?></a>
	                <?php else: ?>
	                    Ce contrat ne possède pas de  mandataire / courtier
	                <?php endif; ?>
	                </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">2. Le marché <?php if ($template_validation) : ?><a href="<?php echo url_for('vrac_marche', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <ul class="list-group">
            	<li class="list-group-item">
            	<?php if ($vrac->produit): ?>
                Produit : <?php echo $vrac->produit_libelle ?><br />
                <?php endif; ?>
            	<?php if ($vrac->cepage): ?>
            	Cépage : <?php echo $vrac->cepage_libelle ?><br />
            	<?php endif; ?>
            	<?php echo ($vrac->millesime)? 'Millésime : '.$vrac->millesime : 'Non millésimé'; ?><?php if ($vrac->get('85_15')): ?> (85/15)<?php endif;?>
            	<?php foreach ($vrac->label as $label): ?>
            	<br /><?php echo ConfigurationClient::getCurrent()->labels->toArray()[$label] ?>
            	<?php endforeach; ?>
            	</li>
                <li class="list-group-item">Type : <?php echo VracConfiguration::getInstance()->getCategories()[$vrac->categorie_vin]; ?><?php if ($vrac->domaine): ?><br /><?php echo $vrac->domaine; ?><?php endif; ?></li>
                <?php if ($vrac->lot): ?>
                <li class="list-group-item">Lot : <?php echo $vrac->lot ?></li>
                <?php endif; ?>
                <?php if ($vrac->degre || $vrac->surface || $vrac->bouteilles_contenance_libelle): ?>
                <li class="list-group-item">
                <?php if ($vrac->degre): ?>Degré : <?php echo $vrac->degre ?>°<br /><?php endif; ?>
                <?php if ($vrac->surface): ?>Surface : <?php echo $vrac->surface ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['surface']['libelle'] ?><br /><?php endif; ?>
                <?php if ($vrac->bouteilles_contenance_libelle): ?>Contenance : <?php echo $vrac->bouteilles_contenance_libelle ?><?php endif; ?>
                </li>
                <?php endif; ?>
                <?php if ($vrac->jus_quantite || $vrac->raisin_quantite || $vrac->prix_initial_unitaire): ?>
                <li class="list-group-item">
                <?php if ($vrac->jus_quantite): ?>Volume : <?php echo $vrac->jus_quantite ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['jus_quantite']['libelle'] ?><br /><?php endif; ?>
                <?php if ($vrac->raisin_quantite): ?>Quantité : <?php echo $vrac->raisin_quantite ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['raisin_quantite']['libelle'] ?><br /><?php endif; ?>
                <?php if ($vrac->prix_initial_unitaire): ?>Prix : <?php echo $vrac->prix_initial_unitaire ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['prix_initial_unitaire']['libelle'] ?><?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">3. Les conditions <?php if ($template_validation) : ?><a href="<?php echo url_for('vrac_condition', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <ul class="list-group">
                <?php if ($vrac->delai_paiement || $vrac->moyen_paiement): ?>
                <li class="list-group-item">
                <?php if ($vrac->delai_paiement): ?>Délai de paiement : <?php echo VracConfiguration::getInstance()->getDelaisPaiement()[$vrac->delai_paiement] ?><br /><?php endif; ?>
                <?php if ($vrac->moyen_paiement): ?>Moyen de paiement : <?php echo VracConfiguration::getInstance()->getMoyensPaiement()[$vrac->moyen_paiement] ?><?php endif; ?>
                </li>
				<?php endif; ?>
            	<?php if ($vrac->taux_courtage || $vrac->cvo_repartition || $vrac->tva): ?>
            	<li class="list-group-item">
            	<?php if ($vrac->taux_courtage): ?>Taux de courtage : <?php echo $vrac->taux_courtage ?><br /><?php endif; ?>
            	<?php if ($vrac->cvo_repartition): ?>Répartition : <?php echo VracConfiguration::getInstance()->getRepartitionCvo()[$vrac->cvo_repartition] ?><br /><?php endif; ?>
            	<?php if ($vrac->tva): ?>Facturation <?php echo VracConfiguration::getInstance()->getTva()[$vrac->tva] ?><?php endif; ?>
            	</li>
                <?php endif; ?>
            	<?php if ($vrac->date_limite_retiraison): ?>
            	<li class="list-group-item">Date limite de retiraison : <?php echo format_date($vrac->date_limite_retiraison, 'D') ?></li>
                <?php endif; ?>
            	<?php if ($vrac->clause_reserve_propriete ): ?>
            	<li class="list-group-item">Clause de reserve de propriété</li>
                <?php endif; ?>
            	<?php if ($vrac->pluriannuel ): ?>
                <li class="list-group-item">
            	Contrat pluriannuel <?php if($vrac->annee_contrat): ?>(Année <?php echo $vrac->annee_contrat ?>)<?php endif; ?><br />
            	<?php if ($vrac->seuil_revision): ?>Seuil de révision du prix : <?php echo $vrac->seuil_revision ?>%<br /><?php endif; ?>
            	<?php if ($vrac->pourcentage_variation): ?>Variation max. du volume : <?php echo $vrac->pourcentage_variation ?>%<br /><?php endif; ?>
            	<?php if ($vrac->reference_contrat): ?>Référence au contrat : <?php echo $vrac->reference_contrat ?><br /><?php endif; ?>
            	</li>
            	<?php endif; ?>
            	
            	<?php if ($vrac->conditions_particulieres || $vrac->cahier_charge): ?>
            	<li class="list-group-item">
            		<?php if ($vrac->cahier_charge): ?>Présence d'un cachier des charges entre le vendeur et l'acheteur<br /><?php endif; ?>
            		<?php if ($vrac->conditions_particulieres): ?>Observations : <?php echo $vrac->conditions_particulieres ?><?php endif; ?>
            	</li>
                <?php endif; ?>
                 <?php if ($vrac->autorisation_nom_vin || $vrac->autorisation_nom_producteur): ?>
                <li class="list-group-item">
                <?php if ($vrac->autorisation_nom_vin): ?>Autorisation d'utilisation du nom du vin<br /><?php endif; ?>
                <?php if ($vrac->autorisation_nom_producteur): ?>Autorisation d'utilisation du nom du producteur<?php endif; ?>
                </li>
                <?php endif; ?>
                 <?php if ($vrac->preparation_vin || $vrac->embouteillage || $vrac->conditionnement_crd): ?>
                <li class="list-group-item">
                <?php if ($vrac->preparation_vin): ?>Préparation du vin : <?php echo VracConfiguration::getInstance()->getActeursPreparationVin()[$vrac->preparation_vin] ?><br /><?php endif; ?>
                <?php if ($vrac->embouteillage): ?>Mise en bouteille : <?php echo VracConfiguration::getInstance()->getActeursEmbouteillage()[$vrac->embouteillage] ?><br /><?php endif; ?>
                <?php if ($vrac->conditionnement_crd): ?>Conditionnement CRD : <?php echo VracConfiguration::getInstance()->getConditionnementsCRD()[$vrac->conditionnement_crd] ?><br /><?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
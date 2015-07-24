<?php use_helper('Float'); ?>
<?php
$isValidation = (is_null($vrac->valide->statut));
$isPrixVariable = (!$vrac->prix_total);
$liClass = ($isValidation) ? '' : 'class="lightpadding"';
$template_validation = (isset($template_validation))? $template_validation : false;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">1. Soussignés <?php if($isValidation && !$isTeledeclarationMode): ?><a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <ul class="list-group">
                <li class="list-group-item">Type : <?php echo strip_tags(VracConfiguration::getInstance()->getTransactions()[$vrac->type_transaction]); ?></li>
                <li class="list-group-item clearfix">
                	<div class="row col-xs-4">
                	Vendeur : <a href=""><?php echo $vrac->getVendeurObject()->getNom(); ?></a><?php if ($vrac->vendeur_intermediaire): ?><br />Representé par xxx pour la CVO<?php endif; ?><?php if($vrac->logement): ?><br />Logement du vin : <?php echo $vrac->logement ?><?php endif; ?>
                	</div>
                	<div class="row col-xs-4">
                	Acheteur : <a href=""><?php echo $vrac->getAcheteurObject()->getNom(); ?></a>
                	</div>
                	<div class="row col-xs-4">
	                <?php if ($vrac->mandataire_identifiant != null && $vrac->mandataire_exist): ?>
	                    Mandataire / Courtier : <a href=""><?php echo $vrac->getMandataireObject()->getNom(); ?></a>
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
            <div class="panel-heading">2. Le marché <?php if (!$isTeledeclarationMode && !$vrac->isTeledeclare() && ($isValidation || $isPrixVariable)) : ?><a href="<?php echo url_for('vrac_marche', $vrac); ?><?php endif; ?>" class="btn btn-xs btn-default pull-right">Modifier</a></div>
            <ul class="list-group">
            	<li class="list-group-item">
            	<?php if ($vrac->produit): ?>
                Produit : <?php echo $vrac->produit_libelle ?><br />
                <?php endif; ?>
            	<?php if ($vrac->cepage): ?>
            	Cépage : <?php echo $vrac->cepage_libelle ?><br />
            	<?php endif; ?>
            	<?php echo ($vrac->millesime)? 'Millésime : '.$vrac->millesime : 'Non millésimé'; ?>
            	<?php foreach ($vrac->label as $label): ?>
            	<br /><?php echo ConfigurationClient::getCurrent()->labels->toArray()[$label] ?>
            	<?php endforeach; ?>
            	</li>
                <li class="list-group-item">Type : <?php echo VracConfiguration::getInstance()->getCategories()[$vrac->categorie_vin]; ?><?php if ($vrac->domaine): ?><br /><?php echo $vrac->domaine; ?><?php endif; ?></li>
                <?php if ($vrac->volume_vigueur || $vrac->volume_initial): ?>
                <li class="list-group-item">
                <?php if ($vrac->volume_initial): ?>
                Volume initial : <?php echo $vrac->volume_initial ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['volume_initial']['libelle'] ?><br />
                <?php endif; ?>
                <?php if ($vrac->volume_vigueur): ?>
                Volume en vigueur : <?php echo $vrac->volume_vigueur ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['volume_vigueur']['libelle'] ?>
                <?php endif; ?>
                </li>
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
                <?php if ($vrac->jus_quantite): ?>Volume proposé : <?php echo $vrac->jus_quantite ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['jus_quantite']['libelle'] ?><br /><?php endif; ?>
                <?php if ($vrac->raisin_quantite): ?>Quantité : <?php echo $vrac->raisin_quantite ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['raisin_quantite']['libelle'] ?><br /><?php endif; ?>
                <?php if ($vrac->prix_initial_unitaire): ?>Prix : <?php echo $vrac->prix_initial_unitaire ?> <?php echo VracConfiguration::getInstance()->getUnites()[$vrac->type_transaction]['prix_initial_unitaire']['libelle'] ?><?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">3. Les conditions <?php if (!$isTeledeclarationMode && !$vrac->isTeledeclare() && ($isValidation || $isPrixVariable)) : ?><a href="<?php echo url_for('vrac_condition', $vrac); ?><?php endif; ?>" class="btn btn-xs btn-default pull-right">Modifier</a></div>
            <ul class="list-group">
                <?php if ($vrac->delai_paiement || $vrac->moyen_paiement): ?>
                <li class="list-group-item">
                <?php if ($vrac->delai_paiement): ?>Délai de paiement : <?php echo VracConfiguration::getInstance()->getDelaisPaiement()[$vrac->delai_paiement] ?><br /><?php endif; ?>
                <?php if ($vrac->moyen_paiement): ?>Moyen de paiement : <?php echo VracConfiguration::getInstance()->getMoyensPaiement()[$vrac->moyen_paiement] ?><?php endif; ?>
                </li>
				<?php endif; ?>
            	<?php if ($vrac->cvo_repartition || $vrac->tva): ?>
            	<li class="list-group-item">
            	<?php if ($vrac->cvo_repartition): ?>Taux de courtage : <?php echo VracConfiguration::getInstance()->getRepartitionCvo()[$vrac->cvo_repartition] ?><br /><?php endif; ?>
            	<?php if ($vrac->tva): ?>TVA : <?php echo VracConfiguration::getInstance()->getTva()[$vrac->tva] ?><?php endif; ?>
            	</li>
                <?php endif; ?>
            	<?php if ($vrac->date_limite_retiraison): ?>
            	<li class="list-group-item">Date limite de retiraison : <?php echo $vrac->date_limite_retiraison ?></li>
                <?php endif; ?>
            	<?php if ($vrac->clause_reserve_propriete || $vrac->pluriannuel): ?>
            	<li class="list-group-item">
            	<?php if ($vrac->pluriannuel): ?>Clause de reserve de propriété<br /><?php endif; ?>
            	<?php if ($vrac->pluriannuel): ?>Contrat pluriannuel<?php endif; ?>
            	</li>
                <?php endif; ?>
            	<?php if ($vrac->conditions_particulieres): ?>
            	<li class="list-group-item">Conditions particulières : <?php echo $vrac->conditions_particulieres ?></li>
                <?php endif; ?>
                 <?php if ($vrac->autorisation_nom_vin || $vrac->autorisation_nom_producteur): ?>
                <li class="list-group-item">
                <?php if ($vrac->autorisation_nom_vin): ?>Autorisation d'utilisation du nom du vin<br /><?php endif; ?>
                <?php if ($vrac->autorisation_nom_producteur): ?>Autorisation d'utilisation du nom du producteur<?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
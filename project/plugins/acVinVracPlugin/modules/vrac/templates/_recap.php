<?php
$isValidation = (is_null($vrac->valide->statut));
$isPrixVariable = (!$vrac->prix_total);
$liClass = ($isValidation) ? '' : 'class="lightpadding"';
$template_validation = (isset($template_validation))? $template_validation : false;
?>

<div class="row">
    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">1. Soussignés <?php if($isValidation && !$isTeledeclarationMode): ?><a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn btn-xs btn-default pull-right">Modifier</a><?php endif; ?></div>
            <ul class="list-group">
                <li class="list-group-item">Vendeur : <a href=""><?php echo $vrac->getVendeurObject()->getNom(); ?></a></li>
                <li class="list-group-item">Acheteur : <a href=""><?php echo $vrac->getAcheteurObject()->getNom(); ?></a></li>
                <li class="list-group-item">
                <?php if ($vrac->mandataire_identifiant != null && $vrac->mandataire_exist): ?>
                    Courtier : <a href=""><?php echo $vrac->getMandataireObject()->getNom(); ?></a>
                <?php else: ?>
                    Ce contrat ne possède pas de courtier
                <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">2. Le marché <?php if (!$isTeledeclarationMode && !$vrac->isTeledeclare() && ($isValidation || $isPrixVariable)) : ?><a href="<?php echo url_for('vrac_marche', $vrac); ?><?php endif; ?>" class="btn btn-xs btn-default pull-right">Modifier</a></div>
            <ul class="list-group">
                <li class="list-group-item">En attente de l'original : <?php echo ($vrac->attente_original) ? 'Oui' : 'Non'; ?></li>
                <li class="list-group-item">Type de transaction : <?php echo showType($vrac); ?></li>
                <li class="list-group-item">Produit : <?php echo $vrac->produit_libelle ?> - <?php echo ($vrac->millesime)? $vrac->millesime : 'Non millésimé'; ?></li>
                <?php if ($vrac->categorie_vin == VracClient::CATEGORIE_VIN_DOMAINE) : ?>
                <li class="list-group-item">Domaine : <?php echo $vrac->domaine; ?></li>
                <?php else: ?>
                <li class="list-group-item">Type : <?php echo $vrac->categorie_vin; ?></li>
                <?php endif; ?>
                <li class="list-group-item">
                    Volumes proposés : <?php echo showRecapVolumePropose($vrac); ?>
                    <?php if (!$isTeledeclarationMode && !$vrac->isVise() && $vrac->isVin()): ?>
                        (stock commercialisable <?php echoFloat($vrac->getStockCommercialisable()) ?> hl)
                    <?php endif; ?>
                </li>
                <li class="list-group-item">
                    Volumes enlevés : <?php echo (is_null($vrac->volume_enleve)) ? '0 hl' : ($vrac->volume_enleve . ' hl'); ?>
                </li>
                <li class="list-group-item">
                    Prix unitaire : <?php echo showRecapPrixTotal($vrac); ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">3. Les conditions  <?php if (!$vrac->isTeledeclare() && ($isValidation)):
            ?><a href="<?php echo url_for('vrac_condition', $vrac); ?><?php endif; ?>" class="btn btn-xs btn-default pull-right">Modifier</a></div>
            <ul class="list-group">
                <li class="list-group-item">Type de contrat : <?php echo $vrac->type_contrat; ?></li>
                <li class="list-group-item">Prix variable : <?php echo ($vrac->prix_variable) ? 'Oui' : 'Non'; echo ($vrac->prix_variable) ? ' (' . $vrac->part_variable . '%)' : ''; ?></li>
                <li class="list-group-item">Nature de la transaction : <?php echo $vrac->cvo_nature; ?></li>
                <li class="list-group-item">Repartition de la CVO : <?php if(isset(VracClient::$cvo_repartition[$vrac->cvo_repartition])): ?><?php echo VracClient::$cvo_repartition[$vrac->cvo_repartition] ?><?php endif; ?></li>
                <?php if (!$isTeledeclarationMode): ?>
                    <li class="list-group-item">Date de signature : <?php echo $vrac->date_signature; ?></li>
                    <?php if ($vrac->date_campagne && !$sf_user->hasTeledeclarationVrac()) : ?>
                        <li class="list-group-item">Date de campagne (statistique) : <?php echo $vrac->date_campagne; ?></li>
                    <?php endif; ?>
                    <?php if ($vrac->valide->date_saisie) : ?>
                        <li class="list-group-item">Date de saisie : <?php echo format_date($vrac->valide->date_saisie, 'dd/MM/yyyy'); ?></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="list-group-item">Date d'enlèvement : <?php echo format_date($vrac->getMaxEnlevement(), 'dd/MM/yyyy'); ?></li>
                    <li class="list-group-item">Frais de garde par mois : <?php echo ($vrac->exist('enlevement_frais_garde'))? echoF($vrac->enlevement_frais_garde)."&nbsp;€/hl" : ''."&nbsp;€/hl"; ?></li>
                <?php endif; ?>
                <?php if (!$isTeledeclarationMode): ?>
                    <li class="list-group-item">Commentaires : <?php echo $vrac->commentaire; ?></li>
                <?php endif; ?> 
            </ul>
        </div>
    </div>
</div>
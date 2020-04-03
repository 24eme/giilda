<?php echo "\xef\xbb\xbf" ?>
<?php echo "campagne;statut;id;numero_visa;date_saisie;date_signature;date_campagne;vendeur_identifiant;vendeur_nom;acheteur_identifiant;acheteur_nom;courtier_identifiant;courtier_nom;type;produit_hash;produit_libelle;volume_propose;volume_enleve\n"; ?>
<?php foreach($vracs->rows as $row): $vrac = VracClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON); ?>
<?php echo $vrac->campagne.";" ?>
<?php echo $vrac->valide->statut.";" ?>
<?php echo $vrac->_id.";" ?>
<?php echo "\"".$vrac->numero_archive."\";" ?>
<?php echo preg_replace("/ .+$/", "", $vrac->valide->date_saisie).";" ?>
<?php echo preg_replace("/ .+$/", "", $vrac->date_signature).";" ?>
<?php echo preg_replace("/ .+$/", "", $vrac->date_campagne).";" ?>
<?php echo "\"".$vrac->vendeur_identifiant."\";" ?>
<?php echo "\"".$vrac->vendeur->nom."\";" ?>
<?php echo "\"".$vrac->acheteur_identifiant."\";" ?>
<?php echo "\"".$vrac->acheteur->nom."\";" ?>
<?php echo "\"".$vrac->mandataire_identifiant."\";" ?>
<?php echo "\"".$vrac->mandataire->nom."\";" ?>
<?php echo $vrac->type_transaction.";" ?>
<?php echo $vrac->produit.";" ?>
<?php echo $vrac->produit_libelle.";" ?>
<?php echo round($vrac->volume_propose, 5).";" ?>
<?php echo round($vrac->volume_enleve, 5) ?>
<?php echo "\n" ?>
<?php endforeach; ?>

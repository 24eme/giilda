<?php
$societe = $compte->getSociete();
$identifiant = $societe->getIdentifiant();
$etablissement = $societe->getEtablissementPrincipal();
$organismeNom = sfConfig::get('app_organisme_nom');
?>
Madame, Monsieur,

Votre compte a bien été créé pour l’espace professionnel du <?php echo $organismeNom ?>.

Votre identifiant est : <?php echo $identifiant ?>.

Vous pouvez dès maintenant gérer toutes vos obligations déclaratives via cet espace.

Votre syndicat reste à votre disposition pour plus d'information.

Bonne journée.

<?php echo sfConfig::get('app_email_plugin_signature'); ?>

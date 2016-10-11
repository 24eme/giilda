<?php
$societe = $compte->getSociete();
$identifiant = $societe->getIdentifiant();
$etablissement = $societe->getEtablissementPrincipal();
$contactInterpro = EtablissementClient::getInstance()->buildInfosContact($etablissement);
?>
Madame, Monsieur,

Votre compte a bien été créé sur l'espace professionnel de <?php echo $contactInterpro->interpro; ?>.

Nous vous rappelons que votre identifiant restera toujours le : <?php echo $identifiant ?>.

Cordialement,

L'espace professionnel de votre interprofession

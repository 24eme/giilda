<?php
$interproNom = $facture->getConfiguration()->getNomInterproTeledeclaration();
if (!$interproNom) {
    $interproNom = sfConfig::get('app_teledeclaration_interpro');
}
?>
Bonjour,

Une nouvelle facture émise par <?php echo $interproNom ?> est disponible.

Vous pouvez la télécharger directement en cliquant sur le lien : <?php echo ProjectConfiguration::getAppRouting()->generate('facture_pdf_auth', array('id' => $facture->_id, 'auth' => FactureClient::generateAuthKey($facture->_id)), true); ?>.

Bien cordialement,

<?php echo $interproNom ?>

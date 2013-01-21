<?php

class exportFactureTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'export';
    $this->name             = 'facture';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony export:facture|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    echo "code journal;date;date de saisie;numero de facture;libelle;compte general;compte tiers;compte analytique;date echeance;sens;montant;piece;reference;\n";

    foreach(FactureEtablissementView::getInstance()->getFactureNonVerseeEnCompta() as $vfacture) {
	$facture = FactureClient::getInstance()->find($vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID]);
	foreach($facture->lignes as $t => $lignes) {
		foreach($lignes as $l) {
			echo 'VEN;'.$facture->date_facturation.';'.$facture->date_emission.';'.$facture->identifiant.';Facture n°'.$facture->identifiant.' ('.$l->produit_libelle
.');70610000;;'.$l->produit_identifiant_analytique.';;CREDIT;'.$l->montant_ht.";;\n";

		}
        }
	echo 'VEN;'.$facture->date_facturation.';'.$facture->date_emission.';'.$facture->identifiant.';Facture n°'.$facture->identifiant.' (TVA);44570000;;;;CREDIT;'.$facture->taxe.";;\n";
	$nbecheance = count($facture->echeances);
	$i = 0;
	foreach($facture->echeances as $e) {
		$i++;
		echo 'VEN;'.$facture->date_facturation.';'.$facture->date_emission.';'.$facture->identifiant.';Facture n°'.$facture->identifiant.' (Echeance '.$i.'/'.$nbecheance.');41100000;'.$facture->identifiant.';;'.$e->echeance_date.';DEBIT;'.$e->montant_ttc.";;\n";
	}
    }
  }
}

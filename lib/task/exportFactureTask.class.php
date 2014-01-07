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

    echo "code journal;date;date de saisie;numero de facture;libelle;compte general;compte tiers;compte analytique;date echeance;sens;montant;piece;reference;id couchdb\n";

    foreach(FactureEtablissementView::getInstance()->getFactureNonVerseeEnCompta() as $vfacture) {
	$facture = FactureClient::getInstance()->find($vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID]);
	foreach($facture->lignes as $t => $lignes) {
		foreach($lignes as $l) {
			echo 'VEN;'.$facture->date_facturation.';'.$facture->date_emission.';'.$facture->numero_interloire.';Facture n°'.$facture->numero_interloire.' ('.$l->produit_libelle
.');70610000;;'.$l->produit_identifiant_analytique.';;CREDIT;'.$l->montant_ht.';;;'.$facture->_id.";\n";

		}
        }
	echo 'VEN;'.$facture->date_facturation.';'.$facture->date_emission.';'.$facture->numero_interloire.';Facture n°'.$facture->numero_interloire.' (TVA);'.$this->getSageCompteGeneral($facture).';;;;CREDIT;'.$facture->taxe.';;;'.$facture->_id.";\n";
	$nbecheance = count($facture->echeances);
	if ($nbecheance) {
	  $i = 0;
	  foreach($facture->echeances as $e) {
	    $i++;
	    echo 'VEN;'.$facture->date_facturation.';'.$facture->date_emission.';'.$facture->numero_interloire.';Facture n°'.$facture->numero_interloire.' (Echeance '.($nbecheance - $i + 1).'/'.$nbecheance.');41100000;'.sprintf("%08d", $facture->code_comptable_client).';;'.$e->echeance_date.';DEBIT;'.$e->montant_ttc.';;;'.$facture->_id.";\n";
	  } 
	}else{
	  echo 'VEN;'.$facture->date_facturation.';'.$facture->date_emission.';'.$facture->numero_interloire.';Facture n°'.$facture->numero_interloire.' (Echeance unique);41100000;'.sprintf("%08d", $facture->code_comptable_client).';;'.$facture->date_facturation.';DEBIT;'.$facture->total_ttc.';;;'.$facture->_id.";\n";
	}
    }
  }
  
  protected function getSageCompteGeneral($facture) {
      if($facture->getTauxTva() == 20.0){
          return "44570100";
      }
      return "44570000";
  }
}

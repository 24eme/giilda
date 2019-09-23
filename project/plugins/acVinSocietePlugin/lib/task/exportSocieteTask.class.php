<?php

class exportSocieteTask extends sfBaseTask
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
    $this->name             = 'societe';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony export:societe|INFO]
EOF;
  }

  const ISCLIENT = 1;
  const ISFOURNISSEUR = 2;

  private function printSociete($societe, $compte, $isclient = 1) {
    print $compte.";";
    print $societe->raison_sociale.";";
    if ($isclient == self::ISCLIENT) {
      print "CLIENT;";
    }else{
      print "FOURNISSEUR;";
    }
    print $societe->raison_sociale_abregee.";";
    print preg_replace('/;.*/', '', $societe->getSiegeAdresses()).";";
    if (preg_match('/;/', $societe->getSiegeAdresses())) {
        print str_replace(';', '-', preg_replace('/.*;/', '', $societe->getSiegeAdresses()));
    }
    print ";";
    print $societe->siege->code_postal.";";
    print $societe->siege->commune.";";
    $compte = $societe->getMasterCompte();
    if ($societe->siege->exist('pays') && $societe->siege->pays) {
        print ConfigurationClient::getInstance()->getCountry($societe->siege->pays).";";
    }elseif ($compte->pays) {
        print ConfigurationClient::getInstance()->getCountry($compte->pays).";";
    }else {
        print "France;";
    }
    print ";"; //NAF
    print $societe->no_tva_intracommunautaire.";";
    print $societe->siret.";";
    print $societe->statut.";";
    print $societe->date_modification.";";
    print preg_replace('/[^\+0-9]/i', '', $societe->telephone).";";
    print preg_replace('/[^\+0-9]/i', '', $societe->fax).";";
    print $societe->email.";";
    print sfConfig::get('app_vinsi_url')."/societe/".$societe->identifiant."/visualisation;";
    try {
      if ($isclient == self::ISCLIENT) {
	print $societe->getRegionViticole(false);
      }
    }catch(sfException $e) {
      print "INCONNUE";
    }
    print ";\n";
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    echo "numéro de compte;intitulé;type (client/fournisseur);abrégé;adresse;address complément;code postal;ville;pays;code NAF;n° identifiant;n° siret;mise en sommeil;date de création;téléphone;fax;email;site;Région viticole;\n";

    $societes = SocieteAllView::getInstance()->findByInterpro('INTERPRO-inter-loire');
    $i = 0;
    foreach($societes as $socdata) {
      $soc = SocieteClient::getInstance()->find($socdata->id);
      if (!$soc->code_comptable_client && ! $soc->code_comptable_fournisseur)
	continue;
      if ($soc->code_comptable_client) {
	$this->printSociete($soc, $soc->code_comptable_client, self::ISCLIENT);
      }
      if ($soc->code_comptable_fournisseur) {
	$this->printSociete($soc, $soc->code_comptable_fournisseur, self::ISFOURNISSEUR);
      }
      if (!(++$i % 300)) {
          sleep(1);
      }
    }
  }
}

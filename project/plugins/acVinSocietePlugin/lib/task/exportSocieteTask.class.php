<?php

class exportSocieteTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('all', null, sfCommandOption::PARAMETER_OPTIONAL, 'Display all societé (suspendu included)', ''),
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
    if (!$this->includeSuspendu && !$societe->isActif()) {
	return ;
    }
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
    print "France;";
    print ";"; //NAF
    print $societe->no_tva_intracommunautaire.";";
    print $societe->siret.";";
    print $societe->statut.";";
    print $societe->date_modification.";";
    print preg_replace('/[^\+0-9]/i', '', $societe->telephone).";"; 
    print preg_replace('/[^\+0-9]/i', '', $societe->fax).";"; 
    print $societe->email.";";
    print $this->routing->generate('societe_visualisation', $societe, true).';';
    try {
      if ($isclient == self::ISCLIENT) {
	print $societe->getRegionViticole(false).';';
      }
    }catch(sfException $e) {
      print "INCONNUE;";
    }
    print $societe->isActif().';';
    print "\n";
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $this->includeSuspendu = false;
    if (isset($options['all']) && $options['all']) {
	$this->includeSuspendu = true;
    }

    $this->routing = clone ProjectConfiguration::getAppRouting();

    echo "numéro de compte;intitulé;type (client/fournisseur);abrégé;adresse;address complément;code postal;ville;pays;code NAF;n° identifiant;n° siret;mise en sommeil;date de création;téléphone;fax;email;site;Région viticole;Actif;\n";

    foreach(SocieteAllView::getInstance()->findByInterpro('INTERPRO-declaration') as $socdata) {
      $soc = SocieteClient::getInstance()->find($socdata->id);
      if (!$soc->code_comptable_client && ! $soc->code_comptable_fournisseur) 
	continue;
      if ($soc->code_comptable_client) {
	$this->printSociete($soc, $soc->code_comptable_client, self::ISCLIENT);
      }
      if ($soc->code_comptable_fournisseur) {
	$this->printSociete($soc, $soc->code_comptable_fournisseur, self::ISFOURNISSEUR);
      }
    }
  }
}

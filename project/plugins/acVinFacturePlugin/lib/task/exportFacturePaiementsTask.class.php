<?php

class exportFacturePaiementsTask extends sfBaseTask
{
    protected function configure()
    {
      // // add your own arguments here
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('factureid', null, sfCommandOption::PARAMETER_OPTIONAL, 'Export a specific Facture', ''),
            new sfCommandOption('entete', null, sfCommandOption::PARAMETER_REQUIRED, "Ligne d'entête", true),
        ));

        $this->namespace        = 'export';
        $this->name             = 'facture-paiements';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony export:facture-paiements|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if(!$options['application']){
          throw new sfException("Le choix de l'application est obligatoire");

        }
        $app = $options['application'];
        if($options["entete"]) {
            echo ExportFacturePaiementsCSV::getHeaderCsv();
        }
        if ($options['factureid']) {
            $facture = FactureClient::getInstance()->find($options['factureid']);
            if (!$facture) {
                return;
            }
            $export = new ExportFacturePaiementsCSV($facture, false, false);
            echo $export->exportFacturePaiements();
            return ;
	    }
        $all_factures = FactureEtablissementView::getInstance()->getPaiementNonVerseeEnCompta();
        foreach($all_factures as $vfacture) {
          $facture = FactureClient::getInstance()->find($vfacture->id);
          if(!$facture) {
              throw new sfException(sprintf("Document %s introuvable", $vfacture->id));
          }
          $export = new ExportFacturePaiementsCSV($facture, false, true);
          echo $export->exportFacturePaiements();
        }
    }
}

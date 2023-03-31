<?php

class exportFactureRelancesTask extends sfBaseTask
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
            new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_OPTIONAL, 'Interpro'),
        ));

        $this->namespace        = 'export';
        $this->name             = 'facture-relances';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony export:facture-relances|INFO]
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
        $factureConf = FactureConfiguration::getInstance();
        if($options["entete"]) {
            echo ExportFactureRelanceCSV::getHeaderCsv();
        }
        if ($options['factureid']) {
            $facture = FactureClient::getInstance()->find($options['factureid']);
            if (!$facture||($facture->getNumberToRelance() === false)) {
                return;
            }
            if ($options["interpro"] && $facture->getOrAdd('interpro') != $options["interpro"]) {
                return;
            }
            $export = new ExportFactureRelanceCSV($facture, false);
            echo $export->export();
            return ;
	    }
        $all_factures = FactureEtablissementView::getInstance()->getFactureNonPaye();
        foreach($all_factures as $vfacture) {
          $facture = FactureClient::getInstance()->find($vfacture->id);
          if(!$facture||($facture->getNumberToRelance() === false)) {
              continue;
          }
          if ($options["interpro"] && $facture->getOrAdd('interpro') != $options["interpro"]) {
              return;
          }
          $export = new ExportFactureRelanceCSV($facture, false);
          echo $export->export();
        }
    }
}

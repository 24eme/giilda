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
            new sfCommandOption('non_verse_comptablement', null, sfCommandOption::PARAMETER_REQUIRED, 'Que les versements comptable non réalisé (par defaut: false)', false),
            // add your own options here
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
        echo ExportFacturePaiementsCSV::getHeaderCsv();
        $all_factures = FactureEtablissementView::getInstance()->getFactureNonVerseeEnCompta();
        foreach($all_factures as $vfacture) {

          $facture = FactureClient::getInstance()->find($vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID]);
          if(!$facture) {
              throw new sfException(sprintf("Document %s introuvable", $vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID]));
          }
          $export = new ExportFacturePaiementsCSV($facture, false);
          echo $export->exportFacturePaiements();
        }
    }
}

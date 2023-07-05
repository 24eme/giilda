<?php

class exportMvtsEnAttentesTask extends sfBaseTask
{
    protected function configure()
    {
      // // add your own arguments here
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('entete', null, sfCommandOption::PARAMETER_REQUIRED, "Ligne d'entête", true),
            new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_OPTIONAL, 'Interpro'),
            // add your own options here
        ));

        $this->namespace        = 'export';
        $this->name             = 'mvts-enattentes';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony export:mvts-enattentes|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        sfContext::createInstance($this->configuration);

        $allMouvementsByRegion = FactureClient::getInstance()->getMouvementsForMasse($options['interpro'], null);
        $mouvementsBySoc = FactureClient::getInstance()->getMouvementsNonFacturesBySoc($allMouvementsByRegion);
        $export = ExportFactureCSVFactory::getObject($options['application']);
        if($options["entete"]) {
            $export->printHeader();
        }
        $prefix_sage = FactureConfiguration::getInstance($options['interpro'])->getPrefixSage();
        foreach ($mouvementsBySoc as $societeID => $mouvementsSoc) {
            if ($societe = SocieteClient::getInstance()->find($societeID)) {
                $facture = FactureClient::getInstance()->createDocFromMouvements($mouvementsSoc, $societe, FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_DRM, date('Y-m-d'), null, $options['interpro']);
                ob_start();
                $export->printFacture($facture);
                $datas = ob_get_clean();
                echo str_replace(
                    [$prefix_sage.';', $facture->_id, $facture->numero_piece_comptable],
                    ['ATT;', '', uniqid()],
                    $datas
                );
            }

        }
    }
}

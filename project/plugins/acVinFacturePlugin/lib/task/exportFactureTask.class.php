<?php

class exportFactureTask extends sfBaseTask
{
    protected function configure()
    {
      // // add your own arguments here
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('horstaxe', null, sfCommandOption::PARAMETER_REQUIRED, 'Facturation HT (par defaut False)', false),
            new sfCommandOption('factureid', null, sfCommandOption::PARAMETER_OPTIONAL, 'Export a specific Facture', ''),
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

        $export = ExportFactureCSVFactory::getObject($options['application'], $options['horstaxe']);
        $export->printHeader();

        if ($options['factureid']) {
		    $export->printFacture($options['factureid']);

            return ;
	    }
        foreach(FactureEtablissementView::getInstance()->getFactureNonVerseeEnCompta() as $vfacture) {
    	     $export->printFacture($vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID]);
        }
    }
}

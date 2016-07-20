<?php

class maintenanceFactureCVOCodeCompteTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array());

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('factureid', null, sfCommandOption::PARAMETER_REQUIRED, 'Facture id'),
                // add your own options here
        ));

        $this->namespace = 'maintenance';
        $this->name = 'facture-cvo-code-compte';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [maintenanceFactureLibreCodeCompteTask|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
	$facture = FactureClient::getInstance()->find($options['factureid']);
        $this->conf = ConfigurationClient::getInstance()->getConfiguration($facture->date_facturation);
	$this->codes = array();
	if (!$facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DRM)) {
		return ;
	}
	foreach($facture->lignes as $doc => $ligne) {
		foreach($ligne->details as $detail) {
			$detail->add('code_compte', $this->getCodeComptable($detail->libelle));

		}
	}
	$facture->save();
	echo $facture->_id."\n";
    }

    private function getCodeComptable($l) {
	if (!isset($this->codes[$l])) {
		$d = $this->conf->identifyProductByLibelle($l);
                $this->codes[$l] = $d->getCodeComptable();
                
	}
	return $this->codes[$l];
    }

}

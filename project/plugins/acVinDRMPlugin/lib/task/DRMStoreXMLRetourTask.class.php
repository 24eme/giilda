<?php

class DRMStoreXMLRetourTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('url', sfCommandArgument::REQUIRED, "Url de récupération"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'drm';
        $this->name             = 'storeXMLRetour';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
    The task does things.
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $xml = file_get_contents($arguments['url']);
        if (!$xml) {
            throw new sfException($arguments['url']." empty");
        }
        if(!preg_match('/<numero-cvi>([^<]+)</', $xml, $m)){
            throw new sfException('CVI not found');
        }
        $etablissement = EtablissementClient::getInstance()->findByCvi($m[1]);
        if(!preg_match('/<numero-agrement>([^<]+)</', $xml, $m)){
            throw new sfException('Accise not found');
        }
        if ($etablissement->no_accises != $m[1]) {
          throw new sfException('XML Accise '.$m[1].' doest not match etablissement\'s one '.$etablissement->no_accises);
        }
        if(!preg_match('/<mois>([^<]+)</', $xml, $m)){
            throw new sfException('mois not found');
        }
        $mois = $m[1];
        if(!preg_match('/<annee>([^<]+)</', $xml, $m)){
            throw new sfException('Annee not found');
        }
        $annee = $m[1];

        $drm = DRMClient::getInstance()->findOrCreateByIdentifiantAndPeriode($etablissement->identifiant, $annee.$mois);
        if (!$drm->storeXMLRetour($xml)) {
          return false;
        }
        $drm->save();
        echo $drm->_id." mis à jour avec la DRM de retour attachée\n";
    }

}

<?php

class DRMResetCrdRegimeFromEtablissementTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
           new sfCommandArgument('id', sfCommandArgument::REQUIRED, "Identifiant de la DRM"),
        ));

        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'drm';
        $this->name             = 'reset-crd-regime-from-etablissement';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
    The [importVrac|INFO] task does things.
    Call it with:

    [php symfony import:drm|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $drm = DRMClient::getInstance()->find($arguments['id']);

        if(!$drm) {
            echo sprintf("ERREUR;%s;La DRM n'existe pas\n", $arguments['id']);
            return;
        }

        if(!$drm->isTeledeclare()) {
            echo sprintf("ERREUR;%s;La DRM n'est pas télédéclarée\n", $drm->_id);
            return;
        }

        $etablissement = $drm->getEtablissement();

        if(!$etablissement->crd_regime) {
            echo sprintf("ERREUR;%s;L'établissement n'a pas de régime CRD\n", $drm->_id);
            return;
        }

        $drm->switchCrdRegime($etablissement->crd_regime);
        echo sprintf("SUCCES;%s;%s\n", $drm->_id, $etablissement->crd_regime);

        $drm->save();
    }

}

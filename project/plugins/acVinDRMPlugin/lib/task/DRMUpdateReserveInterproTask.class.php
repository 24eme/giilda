<?php

class DRMUpdateReserveInterproTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, "Id"),
            new sfCommandArgument('campagne', sfCommandArgument::REQUIRED, "Id"),
            new sfCommandArgument('hash', sfCommandArgument::REQUIRED, "Id"),
            new sfCommandArgument('valeur', sfCommandArgument::OPTIONAL, "Id"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'drm';
        $this->name             = 'update-reserveinterpro';
        $this->briefDescription = '';
        $this->detailedDescription = '';

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $historique = new DRMHistorique($arguments['identifiant'], $arguments['campagne']);
        $drm = $historique->getLast();

        if (!$drm) {
            echo $arguments['identifiant']." no drm\n";
            return;
        }

        if (!$drm->isValidee()) {

            if ($this->updateReserve($drm, $arguments['hash'], $arguments['valeur'])) {
                echo $drm->_id." updated\n";
            } else {
                echo $drm->_id." error updating\n";
            }

            $prev = $historique->getPrevious($drm->periode);
            if ($this->updateReserve($prev, $arguments['hash'], $arguments['valeur'])) {
                echo $prev->_id." updated\n";
            } else {
                echo $prev->_id." error updating\n";
            }

            return;
        }

        if ($this->updateReserve($drm, $arguments['hash'], $arguments['valeur'])) {
            echo $drm->_id." updated\n";
        } else {
            echo $drm->_id." error updating\n";
        }
    }

    private function updateReserve($drm, $hash, $valeur) {
        if ($drm->declaration->exist($hash)) {
            $produit = $drm->declaration->get($hash);
            try {
                if ($valeur === null) {
                    $produit->remove('reserve_interpro');
                } else {
                    $produit->reserve_interpro = $valeur;
                }
                $drm->save();
            } catch (Exception $e) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

}

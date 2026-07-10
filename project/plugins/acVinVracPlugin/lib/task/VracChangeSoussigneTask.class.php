<?php

class VracChangeSoussigneTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        $this->addArguments([
            new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, "id_doc"),
            new sfCommandArgument('type_soussigne', sfCommandArgument::REQUIRED, "Acheteur, vendeur, mandataire"),
            new sfCommandArgument('nouvel_id', sfCommandArgument::REQUIRED, "id du nouveau soussigné"),
        ]);

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('is_representant', null, sfCommandOption::PARAMETER_REQUIRED, 'Le nouveau soussigné est le nouveau représentant', false),
            // add your own options here
        ));

        $this->namespace        = 'vrac';
        $this->name             = 'change-soussigne';
        $this->briefDescription = 'Met à jour un des soussignés d\'un vrac';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $vrac = VracClient::getInstance()->find($arguments['doc_id']);

        if (! $vrac) {
            throw new sfException(sprintf("ERREUR;Contrat introuvable %s", $arguments['doc_id']));
        }

        if (in_array($arguments['type_soussigne'], ['vendeur', 'acheteur', 'mandataire']) === false) {
            throw new sfException(sprintf("ERREUR;Type soussigne inconnu : %s. [vendeur, acheteur, mandataire]", $arguments['type_soussigne']));
        }

        $soussigne = EtablissementClient::getInstance()->find($arguments['nouvel_id']);

        if (! $soussigne) {
            throw new sfException(sprintf("ERREUR;Etablissement inconnu : %s.", $arguments['nouvel_id']));
        }

        $setInfo = '';
        switch ($arguments['type_soussigne']) {
            case 'vendeur': $setInfo = "setVendeurIdentifiant"; break;
            case 'acheteur': $setInfo = "setAcheteurIdentifiant"; break;
            case 'mandataire': $setInfo = "setMandataireIdentifiant"; break;
        }

        if ($setInfo === '') {
            throw new LogicException("ERREUR;Fonction inconnue.");
        }

        $vrac->$setInfo($soussigne->_id);
        if ($options['is_representant']) {
            $vrac->setRepresentantIdentifiant($soussigne->_id);
        }
        $vrac->setInformations();

        $vrac->save();
    }
}

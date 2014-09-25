<?php

class TeledeclarationAnnulationContratsTask extends sfBaseTask {

    protected $date = null;

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'teledeclaration';
        $this->name = 'annulationContrats';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [generateAlertes|INFO] task does things.
Call it with:

  [php symfony generatePDF|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $routing = clone ProjectConfiguration::getAppRouting();
        $context = sfContext::createInstance($this->configuration);

        $this->date = date("Y-m-d");

        echo "\nDebut de la tache d'annulation du " . $this->date . " \n";
        $contrats_annulations_brouillons = $this->getContratsAnnulationBrouillons();
        $this->annulationContrats($contrats_annulations_brouillons);


        $contrats_annulations_attente_signature = $this->getContratsAnnulationAttenteSignature();
        $this->annulationContrats($contrats_annulations_attente_signature);
        $this->mailsAnnulationAttenteSignatureContrats($contrats_annulations_attente_signature);


        $contrats_rappel_attente_signature = $this->getContratsEnRappelAttenteSignature();
        $this->mailsRappelAttenteSignatureContrats($contrats_rappel_attente_signature);
    }

    protected function getContratsAnnulationBrouillons() {
        $contrats = array();

        $contrats_brouillons = VracStatutAndTypeView::getInstance()->findContatsByStatut(VracClient::STATUS_CONTRAT_BROUILLON);

        foreach ($contrats_brouillons as $contratBrouillonsView) {

            $contrat = VracClient::getInstance()->find($contratBrouillonsView->id);
            if ($contrat->isTeledeclare() && $contrat->date_campagne) {
                
                //A RETIRER
                if (($contrat->createur_identifiant != '80056301') && ($contrat->createur_identifiant != '80056401')) {
                    continue;
                }
                
                
                $date_campagne_max_iso = Date::addDelaiToDate('+ 10 days', Date::getIsoDateFromFrenchDate($contrat->date_campagne));
                $isMore = Date::sup($this->date, $date_campagne_max_iso);
                if ($isMore) {
                    $contrats[$contrat->_id] = $contrat;
                }
            }
        }
        return $contrats;
    }

    protected function getContratsAnnulationAttenteSignature() {
        $contrats = array();
        $contrats_attentes_signature = VracStatutAndTypeView::getInstance()->findContatsByStatut(VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE);

        foreach ($contrats_attentes_signature as $contratAttenteView) {

            $contrat = VracClient::getInstance()->find($contratAttenteView->id);
            if ($contrat->isTeledeclare() && $contrat->valide->date_saisie) {
                
                //A RETIRER
                if (($contrat->createur_identifiant != '80056301') && ($contrat->createur_identifiant != '80056401')) {
                                        continue;
                }
                
                
                $date_campagne_max_iso = Date::addDelaiToDate('+5 days', Date::getIsoDateFromFrenchDate($contrat->valide->date_saisie));
                $isMore = Date::sup($this->date, $date_campagne_max_iso);
                if ($isMore) {
                    $contrats[$contrat->_id] = $contrat;
                }
            }
        }
        return $contrats;
    }

    protected function getContratsEnRappelAttenteSignature() {
        $contrats = array();
        $contrats_attentes_signature = VracStatutAndTypeView::getInstance()->findContatsByStatut(VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE);

        foreach ($contrats_attentes_signature as $contratAttenteView) {

            $contrat = VracClient::getInstance()->find($contratAttenteView->id);
            if ($contrat->isTeledeclare() && $contrat->valide->date_saisie) {
                
                
                //A RETIRER
                if (($contrat->createur_identifiant != '80056301') && ($contrat->createur_identifiant != '80056401')) {
                    continue;
                }
                
                
                $date_contrat_rappel = Date::addDelaiToDate('+3 days', Date::getIsoDateFromFrenchDate($contrat->valide->date_saisie));
                if ($date_contrat_rappel == date("Y-m-d")) {
                    $contrats[$contrat->_id] = $contrat;
                }
            }
        }
        return $contrats;
    }

    protected function mailsAnnulationAttenteSignatureContrats($contrats_annulations_attente_signature) {
        $vracEmailManager = new VracEmailManager($this->getMailer());
        foreach ($contrats_annulations_attente_signature as $contrat_attente_signature) {
            $vracEmailManager->setVrac($contrat_attente_signature);
            $vracEmailManager->sendMailAnnulation(true);
            echo "Envoi des mails du contrat " . $contrat_attente_signature->numero_contrat . " visa " . $contrat_attente_signature->numero_archive . " \n";
        }
    }

    protected function annulationContrats($contrats) {
        if (!count($contrats)) {
            echo "Aucun contrat à annuler\n";
        }
        foreach ($contrats as $contrat) {
            $contrat->valide->statut = VracClient::STATUS_CONTRAT_ANNULE;
            $contrat->save();
            echo "Annulation du contrat " . $contrat->numero_contrat . " visa " . $contrat->numero_archive . " \n";
        }
    }

    protected function mailsRappelAttenteSignatureContrats($contrats) {
        $vracEmailManager = new VracEmailManager($this->getMailer());
        foreach ($contrats as $contrat_attente_signature_rappel) {
            $vracEmailManager->setVrac($contrat_attente_signature_rappel);
            $vracEmailManager->sendMailRappel();
            echo "Envoi des mails du contrat " . $contrat_attente_signature_rappel->numero_contrat . " visa " . $contrat_attente_signature_rappel->numero_archive . " \n";
        }
    }

}

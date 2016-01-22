<?php

class factureActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->form = new FactureSocieteChoiceForm('INTERPRO-declaration');
        $this->generationForm = new FactureGenerationForm();
        $this->generations = GenerationClient::getInstance()->findHistoryWithType(GenerationClient::TYPE_DOCUMENT_FACTURES, 10);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                return $this->redirect('facture_societe', $this->form->getSociete());
            }
        }
    }

    public function executeNouveauMouvements(sfWebRequest $request) {

        $this->factureMouvements = MouvementsFactureClient::getInstance()->createMouvementsFacture();
        $this->factureMouvements->save();
        $this->redirect('facture_mouvements_edition', array('id' => $this->factureMouvements->identifiant));
    }

    public function executeMouvementsList(sfWebRequest $request) {

        $this->factureMouvementsAll = MouvementsFactureClient::getInstance()->startkey('MOUVEMENTSFACTURE-0000000000')->endkey('MOUVEMENTSFACTURE-9999999999')->execute();
    }

    public function executeMouvementsedition(sfWebRequest $request) {

        $this->factureMouvements = MouvementsFactureClient::getInstance()->find('MOUVEMENTSFACTURE-' . $request->getParameter('id'));
        $this->form = new FactureMouvementsEditionForm($this->factureMouvements, array('interpro_id' => 'INTERPRO-declaration'));
        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if ($this->form->isValid()) {
            $this->form->save();
            $this->redirect('facture_mouvements', array('id' => $this->factureMouvements->identifiant));
        }
    }

    public function executeEdition(sfWebRequest $request) {
        $this->facture = FactureClient::getInstance()->find($request->getParameter('id'));
        if (!$this->facture) {

            return $this->forward404(sprintf("La facture %s n'existe pas", $request->getParameter('id')));
        }
        $configAppFacture = sfConfig::get('app_configuration_facture');
        $this->sans_categorie = $configAppFacture['sans_categories'];
        $this->form = new FactureEditionForm($this->facture, array('sans_categories' => $this->sans_categorie));

        if ($this->facture->isPayee()) {

            throw new sfException(sprintf("La factures %s a déjà été payée", $facture->_id));
        }

        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {
            return sfView::SUCCESS;
        }

        $this->form->save();

        if ($this->facture->isAvoir()) {
            $this->getUser()->setFlash("notice", "L'avoir a bien été modifié.");
        } else {
            $this->getUser()->setFlash("notice", "La facture a été modifiée.");
        }

        if ($request->getParameter("not_redirect")) {

            return $this->redirect('facture_edition', $this->facture);
        }
        return $this->redirect('facture_societe', $this->facture->getSociete());
    }

    public function executeGeneration(sfWebRequest $request) {
        $this->form = new FactureGenerationForm();        
        $filters_parameters = array();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $filters_parameters = $this->constuctFactureFiltersParameters();
                $generation = new Generation();
              
                $generation->arguments->add('regions', implode(',', array_values($filters_parameters['regions'])));
                if ($values['modele'] != FactureGenerationForm::TYPE_DOCUMENT_TOUS) {
                    $generation->arguments->add('modele', $filters_parameters['modele']);
                }
                $generation->arguments->add('date_facturation', $filters_parameters['date_mouvement']);
                $generation->arguments->add('date_mouvement', $filters_parameters['date_mouvement']);
                if ($filters_parameters['message_communication']) {
                    $generation->arguments->add('message_communication', $filters_parameters['message_communication'] );
                }
                $generation->type_document = GenerationClient::TYPE_DOCUMENT_FACTURES;
                $generation->save();
            }
        }
        return $this->redirect('generation_view', array('type_document' => $generation->type_document, 'date_emission' => $generation->date_emission));
    }

    public function executeEtablissement(sfWebRequest $request) {
        return $this->redirect('facture_societe', $this->getRoute()->getEtablissement()->getSociete());
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->form = new FactureGenerationForm();
        $this->societe = $this->getRoute()->getSociete();
        $this->factures = FactureSocieteView::getInstance()->findByEtablissement($this->societe);
        $this->mouvements = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($this->societe);

        $this->compte = $this->societe->getMasterCompte();
    }

    public function executeAvoir(sfWebRequest $request) {
        $this->baseFacture = FactureClient::getInstance()->find($request->getParameter('id'));

        if (!$this->baseFacture) {

            return $this->forward404(sprintf("La facture %s n'existe pas", $request->getParameter('id')));
        }
        // if ($this->baseFacture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DRM)) {
        return $this->redirect('defacturer', $this->baseFacture);
        // }


        $this->facture = FactureClient::createAvoir($this->baseFacture);

        $configAppFacture = sfConfig::get('app_configuration_facture');
        $this->sans_categorie = $configAppFacture['sans_categories'];
        $this->form = new FactureEditionForm($this->facture, array('sans_categories' => $this->sans_categorie));

        $this->setTemplate('edition');

        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {
            return sfView::SUCCESS;
        }

        $this->form->save();

        $this->getUser()->setFlash("notice", "L'avoir a été créé.");
        if ($request->getParameter("not_redirect")) {

            return $this->redirect('facturation_edition', $this->facture);
        }

        return $this->redirect('facture_societe', array("identifiant" => $this->facture->identifiant));
    }

    public function executeCreation(sfWebRequest $request) {

        $this->societe = $this->getRoute()->getSociete();
        $this->values = array();

        $default = $request->hasParameter('type-facture') ? array('modele' => $request->getParameter('type-facture')) : array();
        $this->form = new FactureGenerationForm($default);
        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {

            return sfView::SUCCESS;
        }

        $filters_parameters = $this->constuctFactureFiltersParameters();
        $mouvementsBySoc = array($this->societe->identifiant => FactureClient::getInstance()->getFacturationForSociete($this->societe));
        
        $mouvementsBySocFiltered = FactureClient::getInstance()->filterWithParameters($mouvementsBySoc, $filters_parameters);           
        
        if ($mouvementsBySocFiltered) {
            $generation = FactureClient::getInstance()->createFacturesBySoc($mouvementsBySocFiltered, $filters_parameters['modele'], $filters_parameters['date_mouvement'], $filters_parameters['message_communication']);
            $generation->save();
        }
        return $this->redirect('facture_societe', $this->societe);
    }

    public function executeDefacturer(sfWebRequest $resquest) {
        $this->facture = $this->getRoute()->getFacture();
        if (!$this->facture->hasAvoir()) {
            $this->avoir = FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($this->facture);
        }
        $this->redirect('facture_societe', array('identifiant' => $this->facture->identifiant));
    }

//    public function executeGenerer(sfWebRequest $request) {
//        $parameters = $request->getParameter('facture_generation');
//        $date_facturation = (!isset($parameters['date_facturation'])) ? null : DATE::getIsoDateFromFrenchDate($parameters['date_facturation']);
//        $message_communication = (!isset($parameters['message_communication'])) ? null : $parameters['message_communication'];
//        $parameters['date_mouvement'] = (isset($parameters['date_mouvement']) && $parameters['date_mouvement'] != '') ? $parameters['date_mouvement'] : $date_facturation;
//        if (!isset($parameters['type_document']) || !$parameters['type_document'] || $parameters['type_document'] == FactureGenerationMasseForm::TYPE_DOCUMENT_TOUS) {
//            unset($parameters['type_document']);
//        }
//        
//        $this->societe = $this->getRoute()->getSociete();
//
//        $mouvementsBySoc = array($this->societe->identifiant => FactureClient::getInstance()->getFacturationForSociete($this->societe));
//        // $mouvementsBySoc = FactureClient::getInstance()->filterWithParameters($mouvementsBySoc, $parameters);
//
//        if ($mouvementsBySoc) {
//            $generation = FactureClient::getInstance()->createFacturesBySoc($mouvementsBySoc, $date_facturation, $message_communication);
//            $generation->save();
//        }
//        $this->redirect('facture_societe', $this->societe);
//    }

    public function executeRedirect(sfWebRequest $request) {
        $iddoc = $request->getParameter('iddocument');

        if (preg_match('/^DRM/', $iddoc)) {
            $drm = DRMClient::getInstance()->find($iddoc);
            return $this->redirect('drm_visualisation', $drm);
        } else if (preg_match('/^SV12/', $iddoc)) {
            $sv12 = SV12Client::getInstance()->find($iddoc);
            return $this->redirect('sv12_visualisation', $sv12);
        } else if (preg_match('/^MOUVEMENTSFACTURE/', $iddoc)) {
            $mouvementFacture = MouvementsFactureClient::getInstance()->find($iddoc);
            return $this->redirect('facture_mouvements_edition', array('id' => $mouvementFacture->identifiant));
        }
        return $this->forward404();
    }

    public function executeLatex(sfWebRequest $request) {
        $this->setLayout(false);
        $this->facture = FactureClient::getInstance()->find($request->getParameter('id'));
        $this->forward404Unless($this->facture);
        $latex = new FactureLatex($this->facture);
        $latex->echoWithHTTPHeader($request->getParameter('type'));
        //    var_dump($latex->echoWithHTTPHeader('latex'));
        exit;
    }

    private function getLatexTmpPath() {
        return "/tmp/";
    }

    public function executeComptabiliteEdition(sfWebRequest $request) {
        $compta = ComptabiliteClient::getInstance()->find('COMPTABILITE');
        $this->form = new ComptabiliteEditionForm($compta);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                return $this->redirect('comptabilite_edition');
            }
        }
    }

    private function constuctFactureFiltersParameters() {
        $values = $this->form->getValues();
        $filters_parameters = array();
        $filters_parameters['date_mouvement'] = date('Y-m-d');
        $filters_parameters['message_communication'] = "";
        $filters_parameters['type_document'] = 'FACTURE';
        $filters_parameters['modele'] = 'DRM';

        if (isset($values['date_facturation']) && $values['date_facturation']) {
            $filters_parameters['date_mouvement'] = DATE::getIsoDateFromFrenchDate($values['date_facturation']);
        }
        if (isset($values['message_communication']) && $values['message_communication']) {
            $filters_parameters['message_communication'] = $values['message_communication'];
        }
        if (isset($values['modele']) && $values['modele']) {
            if ($values['modele'] == FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS) {
                $filters_parameters['modele'] = 'MouvementsFacture';
            } elseif ($values['modele'] == FactureClient::TYPE_FACTURE_MOUVEMENT_DRM) {
                $filters_parameters['modele'] = 'DRM';
            }
        }
        return $filters_parameters;
    }

}

<?php

class factureActions extends sfActions {

    private function getInterproFacturable(sfWebRequest $request) {
        if ($this->getUser()->hasCredential(AppUser::CREDENTIAL_ADMIN) && FactureConfiguration::isMultiInterproFacturables()) {
            return $request->getParameter('interpro', $this->getUser()->getCompte()->getInterproFacturable());
        }
        return null;
    }

    public function executeIndex(sfWebRequest $request) {
        $this->form = new FactureSocieteChoiceForm('INTERPRO-declaration');
        $this->interproFacturable = $this->getInterproFacturable($request);
        $this->generationForm = ($this->interproFacturable)? new FactureGenerationForm(['interpro' => $this->interproFacturable], ['export'=> true]) : new FactureGenerationForm(null, ['export'=> true]);
        $this->generations = GenerationClient::getInstance()->findHistoryWithType(array(
            GenerationClient::TYPE_DOCUMENT_EXPORT_SHELL,
            GenerationClient::TYPE_DOCUMENT_EXPORT_RELANCES,
            GenerationClient::TYPE_DOCUMENT_FACTURES,
            GenerationClient::TYPE_DOCUMENT_VRACSSANSPRIX
        ), 10, $this->interproFacturable);
        sfContext::getInstance()->getResponse()->setTitle('FACTURE');
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                return $this->redirect('facture_societe', $this->form->getSociete());
            }
        }
    }

    public function executeNouveauMouvements(sfWebRequest $request) {

        sfContext::getInstance()->getResponse()->setTitle('FACTURES LIBRES - (nouveau)');
        $this->factureMouvements = MouvementsFactureClient::getInstance()->createMouvementsFacture();
        $this->factureMouvements->save();
        $this->redirect('facture_mouvements_edition', array('id' => $this->factureMouvements->identifiant));
    }

    public function executeMouvementsList(sfWebRequest $request) {
        sfContext::getInstance()->getResponse()->setTitle('FACTURES LIBRES');
        $this->interproFacturable = $this->getInterproFacturable($request);
        $this->factureMouvementsAll = MouvementsFactureClient::getInstance()->startkey('MOUVEMENTSFACTURE-0000000000')->endkey('MOUVEMENTSFACTURE-9999999999')->execute();
        $this->factureMouvementsAll = $this->factureMouvementsAll->getDatas();
        krsort($this->factureMouvementsAll);
    }

    public function executeMouvementsedition(sfWebRequest $request) {
        $this->factureMouvements = MouvementsFactureClient::getInstance()->find('MOUVEMENTSFACTURE-' . $request->getParameter('id'));
        $interproFacturable = $this->getInterproFacturable($request);

        $this->form = new FactureMouvementsEditionForm($this->factureMouvements, array('interpro_id' => 'INTERPRO-declaration', 'interproFacturable' => $interproFacturable));

        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if ($this->form->isValid()) {
            $this->form->save();
            $this->redirect('facture_mouvements_edition', array('id' => $this->factureMouvements->identifiant));
        }
    }

    public function executeMouvementssupprimer(sfWebRequest $request) {
        $this->factureMouvements = MouvementsFactureClient::getInstance()->find('MOUVEMENTSFACTURE-' . $request->getParameter('id'));
        if ($this->factureMouvements->getNbMvtsAFacture()) {
            $this->redirect('facture_mouvements', array('id' => $this->factureMouvements->identifiant));
        }
        $this->factureMouvements->delete();
        $this->redirect('facture_mouvements', array('id' => $this->factureMouvements->identifiant));
    }

    public function executeGeneration(sfWebRequest $request) {
        $interproFacturable = $this->getInterproFacturable($request);
        $this->form = ($interproFacturable)? new FactureGenerationForm(['interpro' => $interproFacturable]) : new FactureGenerationForm();
        $filters_parameters = array();
        if (!$request->isMethod(sfWebRequest::POST)) {

            throw new sfException("Pas en mode post");
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {
            throw new sfException("Formulaire pas valide");
        }

        $filters_parameters = $this->constructFactureFiltersParameters();
        $generation = new Generation();

        $generation->type_document = $filters_parameters['type_document'];

        $generation->arguments->add('date_facturation', $filters_parameters['date_facturation']);
        $generation->arguments->add('date_mouvement', $filters_parameters['date_mouvement']);
        if($filters_parameters['modele']) {
            $generation->arguments->add('modele', $filters_parameters['modele']);
        }
        if ($filters_parameters['message_communication']) {
            $generation->arguments->add('message_communication', $filters_parameters['message_communication']);
        }
        if (isset($filters_parameters['seuil'])) {
            $generation->arguments->add('seuil', $filters_parameters['seuil']);
        }
        if (isset($filters_parameters['interpro'])) {
            $generation->arguments->add('interpro', $filters_parameters['interpro']);
        }
        $generation->save();

        return $this->redirect('generation_view', array('type_document' => $generation->type_document, 'date_emission' => $generation->date_emission));
    }

    public function executeSousGenerationFacture(sfWebRequest $request)
    {
        $generationMaitre = $request->getParameter('generation');
        $type = $request->getParameter('type');

        $generationMaitre = GenerationClient::getInstance()->find($generationMaitre);

        if (! $generationMaitre) {
            $this->redirect404();
        }

        $generation = $generationMaitre->getOrCreateSubGeneration($type);

        $generationMaitre->save();
        $generation->save();

        return $this->redirect('generation_view', [
          'type_document' => $generationMaitre->type_document,
          'date_emission' => $generationMaitre->date_emission.'-'.$generation->type_document
        ]);
    }

    public function executeEtablissement(sfWebRequest $request) {
        if (!$this->getRoute()->getEtablissement()->getSociete()) {
            throw new Exception('Pas de sociéte pour l\'etablissement : '.$this->getRoute()->getEtablissement()->_id);
        }
        return $this->redirect('facture_societe', $this->getRoute()->getEtablissement()->getSociete());
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->interproFacturable = $this->getInterproFacturable($request);
        $this->factures = FactureSocieteView::getInstance()->findBySociete($this->societe, $this->interproFacturable);
        $this->mouvements = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($this->societe, $this->interproFacturable);
        $this->mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($this->societe);
        $this->compte = $this->societe->getMasterCompte();
    }

    public function executeCreation(sfWebRequest $request) {
        ini_set('memory_limit', '256M');

        $this->societe = $this->getRoute()->getSociete();
        $interproFacturable = $this->getInterproFacturable($request);
        $this->values = array();

        $this->form = ($interproFacturable)? new FactureGenerationForm(['interpro' => $interproFacturable]) : new FactureGenerationForm();
        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {

            return sfView::SUCCESS;
        }

        $parameters = $this->constructFactureFiltersParameters();
        $f = FactureClient::getInstance()->createAndSaveFacturesBySociete($this->societe, $parameters);

        if(!$f) {

            return $this->redirect('facture_societe', $this->societe);

        }

        $f->save();

        $generation = FactureClient::getInstance()->createGenerationForOneFacture($f);
        $generation->save();

        return $this->redirect('facture_societe', $this->societe);
    }

    public function executeDefacturer(sfWebRequest $resquest) {
        $this->facture = $this->getRoute()->getFacture();
        if (!$this->facture->hasAvoir()) {
            $this->avoir = FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($this->facture);
        }
        $this->redirect('facture_societe', array('identifiant' => $this->facture->identifiant));
    }

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
        if(!$this->getUser()->hasCredential(AppUser::CREDENTIAL_ADMIN) && !$this->facture->isTelechargee()) {
            $this->facture->setTelechargee();
            $this->facture->save();
        }
        $latex = new FactureLatex($this->facture);
        $latex->echoWithHTTPHeader($request->getParameter('type'));
        //    var_dump($latex->echoWithHTTPHeader('latex'));
        exit;
    }

    public function executeGetFactureWithAuth(sfWebRequest $request) {
        $auth = $request->getParameter('auth');
        $id = $request->getParameter('id');

        $key = FactureClient::generateAuthKey($id);
        $auth = substr($auth, 0, strlen($key));

        if ($auth !== $key) {
            throw new sfError403Exception("Vous n'avez pas le droit d'accéder à cette page");
        }

        return $this->executeLatex($request);
    }

    private function getLatexTmpPath() {
        return "/tmp/";
    }

    public function executeComptabiliteEdition(sfWebRequest $request) {
        $interproFacturable = $this->getInterproFacturable($request);
        $compta = ComptabiliteClient::getInstance()->findCompta($interproFacturable);
        $this->form = new ComptabiliteEditionForm($compta);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                return $this->redirect('comptabilite_edition');
            }
        }
    }

    public function executePaiement(sfWebRequest $request) {
        $this->facture = FactureClient::getInstance()->find($request->getParameter('id'));

        if(!$this->facture) {
            return $this->forward404(sprintf("La facture %s n'existe pas", $request->getParameter('id')));
        }

        $this->form = new FacturePaiementForm($this->facture);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if(!$this->form->isValid()) {
            return sfView::SUCCESS;
        }

        $this->form->save();

        $this->getUser()->setFlash("notice", "Le paiement a bien été ajouté");

        $this->redirect('facture_societe', array('identifiant' => $this->facture->identifiant));
    }

    public function executePaiements(sfWebRequest $request) {
        $this->facture = FactureClient::getInstance()->find($request->getParameter('id'));

        if(!$this->facture) {
            return $this->forward404(sprintf("La facture %s n'existe pas", $request->getParameter('id')));
        }

        $this->form = new FacturePaiementsMultipleForm($this->facture);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if(!$this->form->isValid()) {
            return sfView::SUCCESS;
        }

        $this->form->save();

        $this->getUser()->setFlash("notice", "Les paiements ont bien été enregistrés");

        $this->redirect('facture_societe', array('identifiant' => $this->facture->identifiant));
    }

    public function executeAttente(sfWebRequest $request)
    {
        $interpro = $this->getInterproFacturable($request);
        $this->mvtsVersionnes = $request->getParameter('versionnes');
        $this->onlyMvtsNextFacturation = $request->getParameter('only_mvts_next_facturation');
        $this->onlyVersionnesFactures = $request->getParameter('only_versionnes_factures');
        $this->withDetails = $request->getParameter('details', false);

        $mouvements_en_attente = MouvementfactureFacturationView::getInstance()->getMouvementsEnAttente($interpro, sfConfig::get('app_facturation_region'));

        $this->mouvements = [];
        $montant_ht_total_en_attente = [];
        $docs_originaux_with_mvts_en_attente = [];
        foreach ($mouvements_en_attente as $m) {
            if (empty($m->key[MouvementfactureFacturationView::KEYS_ETB_ID])) {
                continue;
            }
            if (!isset($montant_ht_total_en_attente[$m->key[MouvementfactureFacturationView::KEYS_ETB_ID]])) {
                $montant_ht_total_en_attente[$m->key[MouvementfactureFacturationView::KEYS_ETB_ID]] = 0;
            }
            $montant_ht_total_en_attente[$m->key[MouvementfactureFacturationView::KEYS_ETB_ID]] += round($m->value[MouvementfactureFacturationView::VALUE_QUANTITE] * $m->value[MouvementfactureFacturationView::VALUE_PRIX_UNITAIRE], 2);
            if (strpos($m->id, '-R') === false && strpos($m->id, '-M') === false) {
                $docs_originaux_with_mvts_en_attente[$m->id] = 1;
            }
            if ($this->mvtsVersionnes && strpos($m->id, '-R') === false && strpos($m->id, '-M') === false) {
                continue;
            }
            $this->mouvements[$m->key[MouvementfactureFacturationView::KEYS_ETB_ID]][] = $m;
        }

        if ($this->onlyMvtsNextFacturation) {
            foreach($montant_ht_total_en_attente as $etabId => $montantHtTotal) {
                if (round($montantHtTotal*1.2, 2) < round(FactureConfiguration::getInstance($interpro)->getSeuilMinimum(),2)) {
                    unset($this->mouvements[$etabId]);
                }
            }
        }

        if ($this->onlyVersionnesFactures) {
            foreach($this->mouvements as $k => $mvts) {
                foreach($mvts as $sk => $mvt) {
                    $id = $mvt->id;
                    if (strpos($mvt->id, '-R') !== false)
                        $id = substr($mvt->id, 0, strpos($mvt->id, '-R'));
                    if (strpos($mvt->id, '-M') !== false)
                        $id = substr($mvt->id, 0, strpos($mvt->id, '-M'));
                    if (isset($docs_originaux_with_mvts_en_attente[$id])) {
                        unset($this->mouvements[$k][$sk]);
                    }
                }
                if (!count($this->mouvements[$k])) {
                    unset($this->mouvements[$k]);
                }
            }
        }

        if ($request->getParameter('csv')) {
            $csv_file = "Id etablissement;DRM;Date mvt;Produit;Type mvt;Detail mvt;Volume;Prix;Id Origine\n";
            foreach($this->mouvements as $mouvements) {
                foreach($mouvements as $mouvement) {
                    $csv_file .= $mouvement->key[MouvementfactureFacturationView::KEYS_ETB_ID].';';
                    $csv_file .= $mouvement->key[MouvementfactureFacturationView::KEYS_PERIODE].';';
                    $csv_file .= $mouvement->key[MouvementfactureFacturationView::KEYS_DATE].';';
                    $csv_file .= $mouvement->value[MouvementfactureFacturationView::VALUE_PRODUIT_LIBELLE].';';
                    $csv_file .= $mouvement->value[MouvementfactureFacturationView::VALUE_TYPE_LIBELLE].';';
                    $csv_file .= $mouvement->value[MouvementfactureFacturationView::VALUE_DETAIL_LIBELLE].';';
                    $csv_file .= round($mouvement->value[MouvementfactureFacturationView::VALUE_QUANTITE],5).';';
                    $csv_file .= round($mouvement->value[MouvementfactureFacturationView::VALUE_QUANTITE] * $mouvement->value[MouvementfactureFacturationView::VALUE_PRIX_UNITAIRE], 2).";";
                    $csv_file .= $mouvement->value[MouvementfactureFacturationView::VALUE_ID_ORIGINE]."\n";
                }
            }
            $this->response->setContentType('text/csv');
    	    $this->response->setHttpHeader('md5', md5($csv_file));
    	    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=mvts-attentes-facturation.csv");
    	    return $this->renderText($csv_file);
        }
    }

    private function constructFactureFiltersParameters() {
        $values = $this->form->getValues();
        $filters_parameters = array();
        $filters_parameters['date_mouvement'] = date('Y-m-d');
        $filters_parameters['message_communication'] = "";
        $filters_parameters['type_document'] = GenerationClient::TYPE_DOCUMENT_FACTURES;
        $filters_parameters['modele'] = $values['modele'];
        if (isset($values['date_facturation']) && $values['date_facturation']) {
            $filters_parameters['date_facturation'] = DATE::getIsoDateFromFrenchDate($values['date_facturation']);
        }
        if (isset($values['date_mouvement']) && $values['date_mouvement']) {
            $filters_parameters['date_mouvement'] = DATE::getIsoDateFromFrenchDate($values['date_mouvement']);
        }else if (isset($values['date_facturation']) && $values['date_facturation']) {
            $filters_parameters['date_mouvement'] = DATE::getIsoDateFromFrenchDate($values['date_facturation']);
        }
        if (isset($values['message_communication']) && $values['message_communication']) {
            $filters_parameters['message_communication'] = $values['message_communication'];
        }
        if (isset($values['modele']) && $values['modele']) {
            if ($values['modele'] == FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS) {
                $filters_parameters['modele'] = 'MouvementsFacture';
            }elseif($values['modele'] == FactureClient::TYPE_FACTURE_MOUVEMENT_DRM){
              $filters_parameters['modele'] = "DRM";
          }elseif($values['modele'] == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO){
              $filters_parameters['modele'] = "SV12NEGO";
          }elseif($values['modele'] == FactureClient::TYPE_FACTURE_MOUVEMENT_SV12){
              $filters_parameters['modele'] = "SV12";
          }elseif ($values['modele'] == FactureGenerationForm::TYPE_GENERATION_EXPORT) {
                $filters_parameters['type_document'] = GenerationClient::TYPE_DOCUMENT_EXPORT_SHELL;
                $filters_parameters['modele'] = null;
            }elseif ($values['modele'] == FactureGenerationForm::TYPE_GENERATION_RELANCES) {
                  $filters_parameters['type_document'] = GenerationClient::TYPE_DOCUMENT_EXPORT_RELANCES;
                  $filters_parameters['modele'] = null;
              }elseif ($values['modele'] == GenerationClient::TYPE_DOCUMENT_VRACSSANSPRIX) {
                $filters_parameters['type_document'] = GenerationClient::TYPE_DOCUMENT_VRACSSANSPRIX;
                $filters_parameters['modele'] = null;
            }
        }
        if(isset($values['seuil'])) {
            $filters_parameters['seuil'] = $values['seuil']*1.0;
        }
        if(isset($values['interpro'])) {
            $filters_parameters['interpro'] = $values['interpro'];
        }
        return $filters_parameters;
    }

    public function executeMain()
    {
    }

}

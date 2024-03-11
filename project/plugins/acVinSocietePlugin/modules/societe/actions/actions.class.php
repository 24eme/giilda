<?php

class societeActions extends sfCredentialActions {

    public function executeFullautocomplete(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
        $q = $request->getParameter('q');
        $limit = $request->getParameter('limit', 100);
        $with_inactif = $request->getParameter('inactif', false);
        $q4el = $q;
        $statut = null;
        if (!$with_inactif) {
            $q4el .= "* AND doc.statut:ACTIF";
            $statut = CompteClient::STATUT_ACTIF;
        }

        $qs = new acElasticaQueryQueryString($q4el);
        $elkquery = new acElasticaQuery();
        $elkquery->setQuery($qs);
        $elkquery->setLimit($limit);

        $index = acElasticaManager::getType('COMPTE');
        $resset = $index->search($elkquery);
        $this->resultsElk = $resset->getResults();

        $jsonElastic = $this->matchCompteElastic($this->resultsElk, $limit);

        $json = array_merge($jsonElastic,$this->matchCompte(CompteAllView::getInstance()->findByInterproAndStatut($interpro, $q, $limit, $statut), $q, $limit));

        return $this->renderText(json_encode($json));
    }

    public function executeAutocomplete(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
        $type_societe = explode(",",$request->getParameter('type'));
        $q = $request->getParameter('q');
        $limit = $request->getParameter('limit', 100);
        $societes = SocieteAllView::getInstance()->findByInterproAndStatut($interpro, SocieteClient::STATUT_ACTIF, $q, $limit);
        $json = $this->matchSociete($societes, $q, $limit);

        $this->getResponse()->setContentType('text/json');
        return $this->renderText(json_encode($json));
    }

    public function executeIndex(sfWebRequest $request) {
        return $this->redirect('compte_search');
    }

    public function executeContactChosen(sfWebRequest $request) {
        $identifiant = $request->getParameter('identifiant', false);
        if (preg_match('/^SOCIETE/', $identifiant)) {
            $docRes = SocieteClient::getInstance()->find($identifiant);
            $this->forward404Unless($docRes);
            return $this->redirect('societe_visualisation', array('identifiant' => $docRes->identifiant));
        }
        if (preg_match('/^ETABLISSEMENT/', $identifiant)) {
            $docRes = EtablissementClient::getInstance()->find($identifiant);
            $this->forward404Unless($docRes);
            return $this->redirect('etablissement_visualisation', array('identifiant' => $docRes->identifiant));
        }
        if (preg_match('/^COMPTE/', $identifiant)) {
            $docRes = CompteClient::getInstance()->find($identifiant);
            $this->forward404Unless($docRes);
            return $this->redirect('compte_visualisation', array('identifiant' => $docRes->identifiant));
        }
        $this->forward404();
    }

    public function executeCreationSociete(sfWebRequest $request) {
        $this->form = new SocieteCreationForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $this->redirect('societe_creation_doublon', array('raison_sociale' => $values['raison_sociale']));
            }
        }
    }

    public function executeCreationSocieteDoublon(sfWebRequest $request) {
        $this->raison_sociale = $request->getParameter('raison_sociale', false);
        $this->societesDoublons = SocieteClient::getInstance()->getSocietesWithRaisonSociale($this->raison_sociale);

        if (!count($this->societesDoublons)) {
            $this->redirect('societe_nouvelle', array('type' => $this->type, 'raison_sociale' => $this->raison_sociale));
        }
    }

    public function executeSocieteNew(sfWebRequest $request) {
        $this->raison_sociale = $request->getParameter('raison_sociale', false);
        $societe = SocieteClient::getInstance()->createSociete($this->raison_sociale);
        $societe->save();
        $this->redirect('societe_modification', array('identifiant' => $societe->identifiant));
    }

    public function executeModification(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->applyRights();
        if (!$this->modification && !$this->reduct_rights) {
            $this->forward('acVinCompte', 'forbidden');
        }
        $this->contactSociete = CompteClient::getInstance()->find($this->societe->compte_societe);
        $this->societeForm = new SocieteModificationForm($this->societe, $this->reduct_rights);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return;
        }

        $this->societeForm->bind($request->getParameter($this->societeForm->getName()));

        if (!$this->societeForm->isValid()) {
            return;
        }

        if ((!$this->reduct_rights)) {
            $this->societeForm->updateObject();
        }

        $this->validation = new SocieteValidation($this->societe);
        if (!$this->validation->isValide()) {
            return;
        }

        $this->societeForm->save();

        $this->redirect('societe_visualisation', array('identifiant' => $this->societe->identifiant));
    }

    public function executeAddEnseigne(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->societe->addNewEnseigne();
        $this->societe->save();
        $this->redirect('societe_modification', array('identifiant' => $this->societe->identifiant));
    }

    public function executeSwitchStatus(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->societe->switchStatusAndSave();
        return $this->redirect('compte_visualisation', array('identifiant' => $this->societe->getMasterCompte()->identifiant));
    }

    public function executeVisualisation(sfWebRequest $request) {
        if(!SocieteConfiguration::getInstance()->isVisualisationTeledeclaration() && !$this->getUser()->hasCredential(myUser::CREDENTIAL_CONTACT) && !$this->getUser()->isStalker()) {
            return $this->forwardSecure();
        }

        $this->societe = $this->getRoute()->getSociete();
        $this->mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($this->societe);
        $this->applyRights();
        $this->societe_compte = $this->societe->getMasterCompte();
        if(!$this->societe_compte->lat && !$this->societe_compte->lon){
          $compte = CompteClient::getInstance()->find($this->societe_compte->_id);
          $compte->updateCoordonneesLongLat();
          $compte->save();
        }

        $this->modifiable = $this->getUser()->hasCredential('contacts');
    }

    public function executeAnnulation(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();

        $master_compte = $this->societe->getMasterCompte();
        if ($master_compte) {
            $master_compte->delete();
        }
        $this->societe->delete();

        if ($request->getParameter('back_home')) {
            $this->redirect('societe');
        }

        $this->redirect('societe_creation');
    }

    public function executeUpload(sfWebRequest $request) {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $this->not_valid_file = false;
        $this->formUploadCSVNoCVO = new UploadCSVNoCVOForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->formUploadCSVNoCVO->bind($request->getParameter($this->formUploadCSVNoCVO->getName()), $request->getFiles($this->formUploadCSVNoCVO->getName()));
            if ($this->formUploadCSVNoCVO->isValid()) {

                $file = $this->formUploadCSVNoCVO->getValue('file');
                $this->md5 = $file->getMd5();

                $path = sfConfig::get('sf_data_dir') . '/upload/' . $this->md5;

                $typeSocietes = array(SocieteClient::TYPE_OPERATEUR => SocieteClient::TYPE_OPERATEUR);

                $societesCodeClientViewActif = SocieteExportView::getInstance()->findByInterproAndStatut("INTERPRO-declaration", SocieteClient::STATUT_ACTIF, $typeSocietes);
                $societesCodeClientViewSuspendu = SocieteExportView::getInstance()->findByInterproAndStatut("INTERPRO-declaration", SocieteClient::STATUT_SUSPENDU, $typeSocietes);
                $societesCodeClientView = array_merge($societesCodeClientViewActif, $societesCodeClientViewSuspendu);
                $this->rapport = SocieteClient::getInstance()->addTagRgtEnAttenteFromFile($path, $societesCodeClientView);
            }
        }else{

            return $this->redirect('societe');
        }
    }

    public function executeMandatSepaSwitchSigne(sfWebRequest $request) {
          if(!SocieteConfiguration::getInstance()->isVisualisationTeledeclaration() && !$this->getUser()->hasCredential(myUser::CREDENTIAL_CONTACT) && !$this->getUser()->isStalker()) {
              return $this->forwardSecure();
          }
          $societe = $this->getRoute()->getSociete();
          $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($societe);
          $mandatSepa->switchIsSigne();
          $mandatSepa->save();
          return $this->redirect('societe_visualisation', array('identifiant' => $societe->identifiant));
    }

    public function executeMandatSepaSwitchActif(sfWebRequest $request) {
          if(!SocieteConfiguration::getInstance()->isVisualisationTeledeclaration() && !$this->getUser()->hasCredential(myUser::CREDENTIAL_CONTACT) && !$this->getUser()->isStalker()) {
              return $this->forwardSecure();
          }
          $societe = $this->getRoute()->getSociete();
          $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($societe);
          $mandatSepa->switchIsActif();
          $mandatSepa->save();
          return $this->redirect('societe_visualisation', array('identifiant' => $societe->identifiant));
    }

    public function executeExport(sfWebRequest $request) {
        $identifiant = $request->getParameter('identifiant');
        if(!$identifiant) {
            $identifiant = $request->getParameter('login');
        }
        $type = $request->getParameter('type', 'csv');

        if(!in_array($type, array('csv', 'json'))) {
            $this->response->setStatusCode('404');

            return sfView::NONE;
        }



        if(!$this->getUser()->isAdmin() && !$this->getUser()->hasCredential($request->getParameter('droit'))) {
            $this->response->setStatusCode('403');

            return sfView::NONE;
        }

        $compte = CompteClient::getInstance()->findByIdentifiant($identifiant);
        if(!$compte) {
            $this->response->setStatusCode('404');

            return sfView::NONE;
        }

        if(!$request->getParameter('droit') || !$compte->hasDroit($request->getParameter('droit'))) {
            $this->response->setStatusCode('403');

            return sfView::NONE;
        }

        $this->response->setContentType('text/'.$type);

        $societe = $compte->getSociete();
        $export = new ExportSocieteCSV($societe);

        if($type == 'json') {
            $this->response->setContent($export->exportJson());
        } else {
            $this->response->setContent($export->export());
        }

        return sfView::NONE;
    }

    protected function forwardSecure() {

        throw new sfError403Exception();
    }

    protected function matchCompte($view_res, $term, $limit) {
        $json = array();
        foreach ($view_res as $key => $one_row) {
            $text = CompteAllView::getInstance()->makeLibelle($one_row->key);

            if (Search::matchTerm($term, $text)) {
                $json[$one_row->id] = $text;
            }

            if (count($json) >= $limit) {
                break;
            }
        }
        return $json;
    }

    protected function matchCompteElastic($res,$limit)
    {
      $json = array();
      foreach ($res as $key => $one_row) {
        $data = $one_row->getData();
        $text = '';
        switch ($data['doc']['compte_type']) {
            case 'INTERLOCUTEUR':
                $text = '👤 ';
                break;
            case 'SOCIETE':
                $text = '🏢 ';
                break;
            case 'ETABLISSEMENT':
                $text = '🏠 ';
                break;
        }
        $text .= $data['doc']['nom_a_afficher'];
        $text .= ' ('.$data['doc']['adresse'];
        $text .= ($data['doc']['adresse_complementaire'])? ' - '.$data['doc']['adresse_complementaire'] : "";
        $text .= ' / '.$data['doc']['commune'].' / '.$data['doc']['code_postal'].') ' ;
        $text .= $data['doc']['compte_type']." - ".$data['doc']['identifiant'];
        if($data['doc']['societe_informations']['raison_sociale'] && (substr($data['doc']['identifiant'], -2) != "01")){
          $text .= " à ".$data['doc']['societe_informations']['raison_sociale'];
        }

        $json["COMPTE-".$data['doc']['identifiant']] = $text;
        if (count($json) >= $limit) {
          break;
        }
      }
      return $json;
    }

    protected function matchSociete($view_res, $term, $limit) {

        return SocieteClient::matchSociete($view_res, $term, $limit);
    }

}

<?php

class societeActions extends sfCredentialActions {

    public function executeFullautocomplete(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
	$q = $request->getParameter('q');
	$limit = $request->getParameter('limit', 100);
	$json = $this->matchCompte(CompteAllView::getInstance()->findByInterpro($interpro, $q, $limit), $q, $limit);
        return $this->renderText(json_encode($json));
    }
    
     public function executeActifautocomplete(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
	$q = $request->getParameter('q');
	$limit = $request->getParameter('limit', 100);
	$json = $this->matchCompte(CompteAllView::getInstance()->findByInterproAndStatut($interpro,$q, $limit, SocieteClient::STATUT_ACTIF), $q, $limit);
        return $this->renderText(json_encode($json));
    }

    public function executeAutocomplete(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
	$q = $request->getParameter('q');
	$limit = $request->getParameter('limit', 100);
	$societes = SocieteAllView::getInstance()->findByInterproAndStatut($interpro, SocieteClient::STATUT_ACTIF, array(SocieteClient::SUB_TYPE_VITICULTEUR, SocieteClient::SUB_TYPE_NEGOCIANT), $q, $limit);
        $json = $this->matchSociete($societes, $q, $limit);
        return $this->renderText(json_encode($json));
    }

    public function executeIndex(sfWebRequest $request) {
        $this->contactsForm = new ContactsChoiceForm('INTERPRO-inter-loire');
        $this->societes_creation = SocieteClient::getInstance()->getSocietesWithStatut(SocieteClient::STATUT_EN_CREATION);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->contactsForm->bind($request->getParameter($this->contactsForm->getName()));
            if ($this->contactsForm->isValid()) {
                return $this->redirect('societe_contact_chosen', array('identifiant' => $this->contactsForm->getContact()));
            }
        }
    }
    
    public function executeContactChosen(sfWebRequest $request) {
        $identifiant = $request->getParameter('identifiant',false);
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
        $this->societeTypes = $this->getSocieteTypesRights();
        $this->form = new SocieteCreationForm($this->societeTypes);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $this->redirect('societe_creation_doublon', array('type' => $values['type'], 'raison_sociale' => $values['raison_sociale']));
            }
        }
    }
    
    public function executeCreationSocieteDoublon(sfWebRequest $request) {
         $this->raison_sociale = $request->getParameter('raison_sociale', false);
         $this->type = $request->getParameter('type', false);
         $this->societesDoublons = SocieteClient::getInstance()->getSocietesWithTypeAndRaisonSociale($this->type,$this->raison_sociale);
         if(!count($this->societesDoublons)){
                    $this->redirect('societe_nouvelle', array('type' => $this->type, 'raison_sociale' => $this->raison_sociale));
        }
    }
    
    public function executeSocieteNew(sfWebRequest $request) {
        $this->raison_sociale = $request->getParameter('raison_sociale', false);
        $this->type = $request->getParameter('type', false);
        $societe = SocieteClient::getInstance()->createSociete($this->raison_sociale, $this->type);
        $societe->save();
        $this->redirect('societe_modification', array('identifiant' => $societe->identifiant));
    }

    public function executeModification(sfWebRequest $request) {  
        $this->societe = $this->getRoute()->getSociete();       
        $this->applyRights();
        if(!$this->modification && !$this->reduct_rights){
          $this->forward('acVinCompte','forbidden');
        }
        $this->contactSociete = CompteClient::getInstance()->find($this->societe->compte_societe);
        $this->societeForm = new SocieteModificationForm($this->societe, $this->reduct_rights);
        $this->contactSocieteForm = new CompteCoordonneeForm($this->contactSociete, $this->reduct_rights);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return;
        }

        $this->societeForm->bind($request->getParameter($this->societeForm->getName()));
        $this->contactSocieteForm->bind($request->getParameter($this->contactSocieteForm->getName()));

        if ((!$this->societeForm->isValid()) || !$this->contactSocieteForm->isValid()) {
            return ;
        }
        
        if((!$this->reduct_rights)){
            $this->societeForm->updateObject();
        }
        $this->societeForm->update();

        $this->validation = new SocieteValidation($this->societe);
        if(!$this->validation->isValide()) {
            return;   
        }
        
        $this->societeForm->save();
        
        $this->contactSociete = CompteClient::getInstance()->find($this->societe->compte_societe);
        $this->contactSocieteForm = new CompteCoordonneeForm($this->contactSociete,$this->reduct_rights);
        $this->contactSocieteForm->disabledRevisionVerification();
        $this->contactSocieteForm->bind($request->getParameter($this->contactSocieteForm->getName()));
        $this->contactSocieteForm->save();
        $this->redirect('societe_visualisation', array('identifiant' => $this->societe->identifiant));
            
        
    }

    public function executeAddEnseigne(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->societe->addNewEnseigne();
        $this->societe->save();          
        $this->redirect('societe_modification', array('identifiant' => $this->societe->identifiant));
    }

    public function executeVisualisation(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->etablissements = $this->societe->getEtablissementsObj();
        $this->applyRights();
        
    }

    public function executeAnnulation(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();

        
        if (!$this->societe->isInCreation()) {
            $this->redirect('societe_visualisation', $this->societe);
        }

        $master_compte = $this->societe->getMasterCompte();
        if($master_compte) {
            $master_compte->delete();
        }
        $this->societe->delete();

        if($request->getParameter('back_home')){
            $this->redirect('societe');
        }
        
        $this->redirect('societe_creation');
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

    protected function matchSociete($view_res, $term, $limit) {
        $json = array();
        foreach ($view_res as $key => $one_row) {
            $text = SocieteAllView::getInstance()->makeLibelle($one_row->key);

            if (Search::matchTerm($term, $text)) {
                $json[$one_row->id] = $text;
            }

            if (count($json) >= $limit) {
                break;
            }
        }
        return $json;
    }
    
}

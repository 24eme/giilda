<?php

class societeActions extends sfActions {

    public function executeFullautocomplete(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
	$q = $request->getParameter('q');
	$limit = $request->getParameter('limit', 100);
	$json = $this->matchCompte(CompteAllView::getInstance()->findByInterpro($interpro, $q, $limit), $q, $limit);
        return $this->renderText(json_encode($json));
    }

    public function executeAutocomplete(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
	$q = $request->getParameter('q');
	$limit = $request->getParameter('limit', 100);
	$societes = SocieteAllView::getInstance()->findByInterpro($interpro, 'ACTIF', array(SocieteClient::SUB_TYPE_VITICULTEUR, SocieteClient::SUB_TYPE_NEGOCIANT), $q, $limit);
        $json = $this->matchSociete($societes, $q, $limit);
        return $this->renderText(json_encode($json));
    }

    public function executeIndex(sfWebRequest $request) {
        $this->contactsForm = new ContactsChoiceForm('INTERPRO-inter-loire');
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
        $this->raison_sociale = $request->getParameter('raison_sociale', false);
        $this->form = new SocieteCreationForm($this->raison_sociale);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                if (SocieteClient::getInstance()->existSocieteWithRaisonSociale($values['raison_sociale'])) {
                    $this->redirect('societe_creation', array('raison_sociale' => $values['raison_sociale']));
                }
                $societe = SocieteClient::getInstance()->createSociete($values['raison_sociale'], $values['type']);
                $this->redirect('societe_modification', array('identifiant' => $societe->identifiant));
            }
        }
    }

    public function executeModification(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->contactSociete = CompteClient::getInstance()->find($this->societe->compte_societe);
        $this->societeForm = new SocieteModificationForm($this->societe);
        $this->contactSocieteForm = new CompteModificationForm($this->contactSociete);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->societeForm->bind($request->getParameter($this->societeForm->getName()));
            $this->contactSocieteForm->bind($request->getParameter($this->contactSocieteForm->getName()));
            if ($this->societeForm->isValid() && $this->contactSocieteForm->isValid()) {
                $this->societeForm->update();
                $this->societeForm->save();
                $this->contactSocieteForm->save();  
                
                $this->redirect('societe_visualisation', array('identifiant' => $this->societe->identifiant));
            }
        }
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
    }


    /*     * *************
     * Intégration
     * ************* */

    public function executeCreateSocieteInt(sfWebRequest $request) {
        $this->societe = null;
        if (!is_null($societeParam = $request->getParameter('societe'))) {
            $this->societe = SocieteClient::getInstance()->find($societeParam['identifiant']);
        }
    }

    public function executeDetailSocieteInt(sfWebRequest $request) {
        $this->societe = null;
        if (!is_null($societeParam = $request->getParameter('societe'))) {
            $this->societe = SocieteClient::getInstance()->find($societeParam['identifiant']);
        }
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

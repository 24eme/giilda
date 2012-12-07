<?php

class societeActions extends sfActions {

    public function executeAll(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');
        $json = $this->matchSocieteEtablissementCompte(array('Societe' => SocieteAllView::getInstance()->findByInterpro($interpro)->rows,
            'Etablissement' => EtablissementAllView::getInstance()->findByInterpro($interpro)->rows,
            'Compte' => CompteAllView::getInstance()->findByInterpro($interpro)->rows), $request->getParameter('q'), $request->getParameter('limit', 100));
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
        $this->identifiant = $request->getParameter('identifiant',false);
        if(preg_match('/^SOCIETE[-]{1}[0-9]*$/', $this->identifiant)){
            $docRes = SocieteClient::getInstance()->find($this->identifiant);
            if(!$docRes) throw new sfException("Le document $docRes n'existe plus");
            $this->redirect('societe_visualisation', array('identifiant' => $docRes->identifiant));
        }
        if(preg_match('/^ETABLISSEMENT[-]{1}[0-9]*$/', $this->identifiant)){
            $docRes = EtablissementClient::getInstance()->find($this->identifiant);
            if(!$docRes) throw new sfException("Le document $docRes n'existe plus");
            $this->redirect('societe_visualisation', array('identifiant' => $docRes->id_societe));
        }
        if(preg_match('/^COMPTE[-]{1}[0-9]*$/', $this->identifiant)){
           $docRes = CompteClient::getInstance()->find($this->identifiant);
            if(!$docRes) throw new sfException("Le document $docRes n'existe plus");
            $this->redirect('compte_modification', array('identifiant' => $docRes->identifiant));
        }
        if(!$this->identifiant) throw new sfException("L'identifiant $this->identifiant n'existe pas");
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

    public function executeAddContact(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->contact = $this->societe->addNewContact();
        $this->societe->save();
        $this->redirect('compte_new', array('identifiant' => $this->contact->identifiant));
    }

    public function executeAddEtablissement(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->etablissement = $this->societe->addNewEtablissement();
        $this->redirect('etablissement_new', array('identifiant' => $this->etablissement->identifiant));
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

    protected function matchSocieteEtablissementCompte($view_results, $term, $limit) {
        $json = array();
        foreach ($view_results as $type => $view_res) {
            foreach ($view_res as $key => $one_row) {
                $classView = $type . 'AllView';
                $classClient = $type . 'Client';
                $text = $classView::getInstance()->makeLibelle($one_row->key);

                if (Search::matchTerm($term, $text)) {
                    $json[$one_row->id] = $text;
                }

                if (count($json) >= $limit) {
                    break;
                }
            }
        }
        return $json;
    }

}

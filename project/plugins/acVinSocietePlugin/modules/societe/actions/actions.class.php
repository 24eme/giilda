<?php

class societeActions extends sfActions {

    public function executeCreationSociete(sfWebRequest $request) {
        $this->form = new SocieteCreationForm();

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $societe = SocieteClient::getInstance()->createSociete($values['raison_sociale'], $values['type']);
                $this->redirect('societe_modification', array('identifiant' => $societe->identifiant));
            }
        }
    }

    public function executeModification(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->contactSociete = CompteClient::getInstance()->find($this->societe->id_compte_societe);
        $this->societeForm = new SocieteModificationForm($this->societe);
        $this->contactSocieteForm = new CompteSocieteModificationForm($this->contactSociete);
        
        $this->etablissementSocieteForm = null;
        if ($this->societe->hasChais()) {
            $idEtablissement = $this->societe->etablissements[0]->id_etablissement;
            $etablissement = EtablissementClient::getInstance()->find($idEtablissement);
            $this->etablissementSocieteForm = new EtablissementModificationForm($etablissement);
        }
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->societeForm->bind($request->getParameter($this->societeForm->getName()));
            $this->contactSocieteForm->bind($request->getParameter($this->contactSocieteForm->getName()));
            if($this->societe->hasChais()) $this->etablissementSocieteForm->bind($request->getParameter($this->etablissementSocieteForm->getName()));
            if ($this->societeForm->isValid() && $this->contactSocieteForm->isValid()) {
                $this->societeForm->save();
                $this->contactSocieteForm->save();
                if($this->societe->hasChais()) $this->etablissementSocieteForm->save();
                var_dump('KIFFE ENGER');
                exit;
            }
        }
    }

	/***************
	 * Intégration
	 ***************/
	public function executeCreateSocieteInt(sfWebRequest $request) {
        $this->societe = null;
        if(!is_null($societeParam = $request->getParameter('societe'))){
            $this->societe = SocieteClient::getInstance()->find($societeParam['identifiant']);
        }
    }
	
	public function executeDetailSocieteInt(sfWebRequest $request) {
        $this->societe = null;
        if(!is_null($societeParam = $request->getParameter('societe'))){
            $this->societe = SocieteClient::getInstance()->find($societeParam['identifiant']);
        }
    }
}

<?php

class societeActions extends sfActions {

    
    public function executeChooseSociete(sfWebRequest $request) {        
        $this->form = new SocieteChoiceForm('INTERPRO-inter-loire');

       if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                return $this->redirect('societe_choose', $this->form->getSociete());
            }
        }
    }
    
    public function executeIndex(sfWebRequest $request) {
        $this->societe = null;
        if(!is_null($societeParam = $request->getParameter('societe'))){
            $this->societe = SocieteClient::getInstance()->find($societeParam['identifiant']);
        }
    }

    public function executeAll(sfWebRequest $request) {
        $interpro = $request->getParameter('interpro_id');

        $json = $this->matchSociete(SocieteAllView::getInstance()->findByInterpro($interpro)->rows, $request->getParameter('q'), $request->getParameter('limit', 100));

        return $this->renderText(json_encode($json));
    }

    public function executeCreateSociete(sfWebRequest $request) {
        $this->form = new SocieteCreationForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $societe = new Societe();
                $societe->interpro = 'INTERPRO-inter-loire';
                $societe->identifiant = $values['identifiant'];
                $societe->siret = $values['siret'];
                $societe->raison_sociale = $values['raison_sociale'];
                $societe->telephone = $values['telephone'];
                $societe->save();
                return $this->redirect('societe');
            }
        }
    }
    
        protected function matchSociete($societes, $term, $limit) {
    	$json = array();

	  	foreach($societes as $key => $societe) {
	      $text = SocieteAllView::getInstance()->makeLibelle($societe->key);
	     
	      if (Search::matchTerm($term, $text)) {
	        $json[SocieteClient::getInstance()->getIdentifiant($societe->id)] = $text;
	      }

	      if (count($json) >= $limit) {
	        break;
	      }
	    }
	    return $json;
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

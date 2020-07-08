<?php

class subventionActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        var_dump("ici les subventions"); exit;
    }

    public function executeEtablissement(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
    }

    public function executeCreation(sfWebRequest $request) {
        $etablissement = $this->getRoute()->getEtablissement();

        $subvention = SubventionClient::getInstance()->createDoc($etablissement->identifiant, $request->getParameter('operation'));
        $subvention->save();

        return $this->redirect('subvention_infos', $subvention);
    }

    public function executeInfos(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();

        $this->form = new SubventionsInfosForm($this->subvention);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }
        $this->form->bind($request->getParameter($this->form->getName()));

      	if (!$this->form->isValid()) {
      		return sfView::SUCCESS;
      	}
        $this->form->save();

        $this->redirect('subvention_dossier', $this->subvention);

    }

    public function executeValidation(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();
        $this->form = new SubventionValidationForm($this->subvention);
        
        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }
        
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if (!$this->form->isValid()) {
            return sfView::SUCCESS;
        }
        
        $this->form->save();
        
        return $this->redirect($this->generateUrl('subvention_visualisation', $this->subvention));
    }

    public function executeVisualisation(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();
    }
    
    public function executeDossier(sfWebRequest $request) {

        $this->subvention = $this->getRoute()->getSubvention();
        $this->form = new SubventionDossierForm($this->subvention);

        if (!$request->isMethod(sfWebRequest::POST)) {
      		return sfView::SUCCESS;
      	}
      	$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

      	if (!$this->form->isValid()) {
      		return sfView::SUCCESS;
      	}
        $this->form->save();
        $this->redirect('subvention_infos', array('identifiant' => $this->subvention->identifiant,'operation' => $this->subvention->operation));

    }
}

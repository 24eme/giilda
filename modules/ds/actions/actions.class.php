<?php
class dsActions extends sfActions {
    
  public function executeIndex(sfWebRequest $request) {
      $this->form = new DSEtablissementChoiceForm('INTERPRO-inter-loire');
      $this->generationForm = new DSGenerationForm();
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	   return $this->redirect('ds_etablissement', $this->form->getEtablissement());
	 }
       }
    }
    
     public function executeMasse(sfWebRequest $request) {
       $parameters = $request->getParameter('ds_generation');
       var_dump($parameters); exit;
       $this->setTemplate('index');
    }
    
     public function executeMonEspace(sfWebRequest $request) {    
         
        $this->operateur = $this->getRoute()->getEtablissement();        
        $this->dsHistorique = DSClient::getInstance()->getHistoryByOperateur($this->operateur);
        $this->generationOperateurForm = new DSGenerationOperateurForm();
    }
    
    
     public function executeGenerationOperateur(sfWebRequest $request) { 
        $parameters = $request->getParameter('ds_generation');        
        $campagne = (!isset($parameters['campagne']))? null : $parameters['campagne'];        
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->declarationDs = DSClient::getInstance()->createDsByEtb($campagne,$this->etablissement);     
        $this->declarationDs->save();
        $this->redirect('ds_etablissement', $this->etablissement);
         
    }
    
    
}
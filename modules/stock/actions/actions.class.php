<?php
class stockActions extends sfActions {
    
  public function executeIndex(sfWebRequest $request) {
      $this->form = new StockEtablissementChoiceForm('INTERPRO-inter-loire');
      $this->generationForm = new StockGenerationForm();
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	   return $this->redirect('stock_etablissement', $this->form->getEtablissement());
	 }
       }
    }
    
     public function executeMasse(sfWebRequest $request) {
       $parameters = $request->getParameter('stock_generation');
       var_dump($parameters); exit;
       $this->setTemplate('index');
    }
    
     public function executeMonEspace(sfWebRequest $request) {    
        $this->operateur = $this->getRoute()->getEtablissement();
        $this->stocksHistorique = StockClient::getInstance()->getHistoryByOperateur($this->operateur);
        $this->generationOperateurForm = new StockGenerationOperateurForm();
    }
    
    
     public function executeGenerationOperateur(sfWebRequest $request) { 
        $parameters = $request->getParameter('stock_generation');        
        $campagne = (!isset($parameters['campagne']))? null : $parameters['campagne'];        
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->declarationStock = StockClient::getInstance()->createStockByEtb($campagne,$this->etablissement);     
        $this->declarationStock->save();
        $this->redirect('stock_etablissement', $this->etablissement);
         
    }
    
    
}
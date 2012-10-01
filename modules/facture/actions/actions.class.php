<?php
class factureActions extends sfActions {
    
  public function executeIndex(sfWebRequest $request) {
      $this->form = new FactureEtablissementChoiceForm('INTERPRO-inter-loire');
      $this->generationForm = new FactureGenerationMasseForm();
      $this->generations = GenerationClient::getInstance()->findHistory();
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	   return $this->redirect('facture_etablissement', $this->form->getEtablissement());
	 }
       }
    }
        
   public function executeMasse(sfWebRequest $request) {
       $parameters = $request->getParameter('facture_generation');
       
       $parameters['date_facturation'] = (!isset($parameters['date_facturation']))? null : $parameters['date_facturation'];
       $regions = (!isset($parameters['region']))? null : $parameters['region'];
       $allMouvements = FactureClient::getInstance()->getMouvementsForMasse($regions,9); 
       $mouvementsByEtb = FactureClient::getInstance()->getMouvementsNonFacturesByEtb($allMouvements);       
       $mouvementsByEtb = FactureClient::getInstance()->filterWithParameters($mouvementsByEtb,$parameters);
       
       if($mouvementsByEtb)
       {
       $generation = FactureClient::getInstance()->createFacturesByEtb($mouvementsByEtb,$parameters['date_facturation']);
       $generation->save();
       $this->redirect('generation_facture', array('type_document' => $generation->type_document,'date_emission' => $generation->date_emission));
       }
       $this->generations = GenerationClient::getInstance()->findHistory();
       $this->redirect('facture');
       
    }
    
    public function executeMonEspace(sfWebRequest $resquest) {        
        $this->form = new FactureGenerationForm();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->factures = FactureEtablissementView::getInstance()->findByEtablissement($this->etablissement);
        $this->mouvements = MouvementFacturationView::getInstance()->getMouvementsNonFacturesByEtablissement($this->etablissement);
    }
    
    public function executeDefacturer(sfWebRequest $resquest) {
        $this->facture = $this->getRoute()->getFacture();
	$this->avoir = FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($this->facture);
        $this->etablissement = EtablissementClient::getInstance()->findByIdentifiant($this->facture->identifiant);
        $this->redirect('facture_etablissement', $this->etablissement);        
    }


    public function executeGenerer(sfWebRequest $request) {
        $parameters = $request->getParameter('facture_generation');
        $parameters['date_facturation'] = (!isset($parameters['date_facturation']))? null : $parameters['date_facturation'];
        
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->facturations = FactureClient::getInstance()->getFacturationForEtablissement($this->etablissement,9);
        $mouvementsByEtb = array($this->etablissement->identifiant => $this->facturations);        
        $mouvementsByEtb = FactureClient::getInstance()->filterWithParameters($mouvementsByEtb,$parameters);
        
        if($mouvementsByEtb)
        {
            $generation = FactureClient::getInstance()->createFacturesByEtb($mouvementsByEtb,$parameters['date_facturation']);
            $generation->save();
        }
        $this->redirect('facture_etablissement', $this->etablissement);
    }



    public function executeLatex(sfWebRequest $request) {
        
        $this->setLayout(false);
        $this->facture = FactureClient::getInstance()->findByEtablissementAndId($this->getRoute()->getEtablissement()->identifiant, $request->getParameter('factureid'));
        $this->forward404Unless($this->facture);
	$latex = new FactureLatex($this->facture);
	$latex->echoFactureWithHTTPHeader($request->getParameter('type'));
        exit;
    }
    
    private function getLatexTmpPath() {
        return "/tmp/";
    }
    
}

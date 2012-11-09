<?php
class factureActions extends sfActions {
    
  public function executeIndex(sfWebRequest $request) {
      $this->form = new FactureEtablissementChoiceForm('INTERPRO-inter-loire');
      $this->generationForm = new FactureGenerationMasseForm();
      $this->generations = GenerationClient::getInstance()->findHistoryWithType(GenerationClient::TYPE_DOCUMENT_FACTURES,10);
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	   return $this->redirect('facture_etablissement', $this->form->getEtablissement());
	 }
       }
    }
        
   public function executeGeneration(sfWebRequest $request) {
       $this->generationForm = new FactureGenerationMasseForm();
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->generationForm->bind($request->getParameter($this->generationForm->getName()));
         $values = $this->generationForm->getValues();
         if ($this->generationForm->isValid()) {
	   $generation = new Generation();
           
           $date_facturation = DATE::getIsoDateFromFrenchDate($values['date_facturation']);
           $date_mouvements = DATE::getIsoDateFromFrenchDate($values['date_mouvements']);
           
           $generation->arguments->add('regions', implode(',', array_values($values['regions'])));
	   $generation->arguments->add('date_facturation', $date_facturation);           
	   $generation->arguments->add('date_mouvements', $date_mouvements);           
	   $generation->arguments->add('seuil', $values['seuil']);
	   $generation->type_document = GenerationClient::TYPE_DOCUMENT_FACTURES;
           $generation->save();
	 }
       }
       return $this->redirect('generation_view', array('type_document' => $generation->type_document,'date_emission' => $generation->date_emission));

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
//	$latex->echoFactureWithHTTPHeader('latex');
        $latex->echoFactureWithHTTPHeader($request->getParameter('type'));
        exit;
    }
    
    private function getLatexTmpPath() {
        return "/tmp/";
    }
    
}

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
    
     public function executeGeneration(sfWebRequest $request) {
       $this->generationForm = new DSGenerationForm();
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->generationForm->bind($request->getParameter($this->generationForm->getName()));
	 if ($this->generationForm->isValid()) {
	   $values = $this->generationForm->getValues();
	   $generation = new Generation();
	   $generation->arguments->add('regions', implode(',', array_values($values['regions'])));
	   $generation->arguments->add('operateur_types', implode(',', array_values($values['operateur_types'])));
	   $generation->arguments->add('date_declaration', $values['date_declaration']);
	   $generation->type_document = 'DS';
	   $generation->save();
	 }
       }
       $this->setTemplate('index');
    }
    
     public function executeMonEspace(sfWebRequest $request) {    
         
        $this->etablissement = $this->getRoute()->getEtablissement();        
        $this->dsHistorique = DSClient::getInstance()->getHistoryByOperateur($this->etablissement);
        $this->generationOperateurForm = new DSGenerationOperateurForm();
        
        if ($request->isMethod(sfWebRequest::POST)) {
	 $this->generationOperateurForm->bind($request->getParameter($this->generationOperateurForm->getName()));
	 if ($this->generationOperateurForm->isValid()) {
           $values = $this->generationOperateurForm->getValues();
	   $declarationDs = DSClient::getInstance()->createDsByEtb($this->etablissement, $values["date_declaration"]);     
           $declarationDs->save();
           $this->redirect('ds_generation_operateur', array('identifiant' => $declarationDs->identifiant, 'periode' => $declarationDs->periode));
	 }
       }
    }
    
    
     public function executeGenerationOperateur(sfWebRequest $request) { 
        $this->ds = $this->getRoute()->getDS();
        $this->etablissement = EtablissementClient::getInstance()->retrieveById($this->ds->identifiant);
        $generation = new Generation();
	//$generation->arguments->add('regions',array('tours' => 'tours'));
	//$generation->arguments->add('operateur_types', array($this->etablissement->famille));
	$generation->arguments->add('date_declaration', $this->ds->date_stock);
	$generation->type_document = 'DS';
        $generation->add('documents')->add(0, $this->ds->_id);
	$generation->save();
        return $this->redirect('generation_view', array('type_document' => $generation->type_document,'date_emission' => $generation->date_emission));
      
    }
    
     public function executeEditionDS(sfWebRequest $request) {        
         $this->ds = $this->getRoute()->getDS();
         $this->form = new DSEditionForm($this->ds);
         if ($request->isMethod(sfWebRequest::POST)) {
             $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdateObject();
                $this->ds->save();
                return $this->redirect('ds_edition_operateur_validation_visualisation', $this->ds);
            }
       }
    } 
    
    public function executeEditionDSAddProduit(sfWebRequest $request)
    {
        $this->ds = $this->getRoute()->getDS();
        $this->form = new DSEditionForm($this->ds->declarations);
    }
    
    public function executeEditionDSValidationVisualisation(sfWebRequest $request) {
        $this->ds = $this->getRoute()->getDS();        
        if($this->ds->isStatutASaisir())
        {
            if ($request->isMethod(sfWebRequest::POST)) {
                $this->ds->updateStatut();
                $this->ds->save();
                return $this->redirect('ds_edition_operateur_validation_visualisation', $this->ds);
            }
        }
    }

    public function executeLatex(sfWebRequest $request) {
        
        $this->setLayout(false);
        $this->ds = $this->getRoute()->getDS();
        $this->forward404Unless($this->ds);
	$latex = new DSLatex($this->ds);
	$latex->echoFactureWithHTTPHeader($request->getParameter('type'));
        exit;
    }
    
}
<?php
class dsActions extends sfActions {

  public function executeIndex(sfWebRequest $request) {

    $this->form = new DSEtablissementChoiceForm('INTERPRO-declaration');
    $this->generations = GenerationClient::getInstance()->findHistoryWithType(GenerationClient::TYPE_DOCUMENT_DS,10);
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
	   $generation->type_document = GenerationClient::TYPE_DOCUMENT_DS;
	   $generation->save();
	   return $this->redirect('generation_view', array('type_document' => $generation->type_document, 'date_emission' => $generation->date_emission));
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
           $date = $values["date_declaration"];
	   try {
	     $declarationDs = DSClient::getInstance()->findOrCreateDsByEtbId($this->etablissement->identifiant, $date);
	     $declarationDs->save();
	   }catch(sfException $e) {
	     $this->getUser()->setFlash('global_error', $e->getMessage());
	     $this->redirect('ds_etablissement', $this->etablissement);
	   }
           $this->redirect('ds_generation_operateur', array('identifiant' => $declarationDs->identifiant, 'periode' => $declarationDs->periode));
	 }
       }
    }

}

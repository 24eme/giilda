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
         
        $this->etablissement = $this->getRoute()->getEtablissement();        
        $this->dsHistorique = DSClient::getInstance()->getHistoryByOperateur($this->etablissement);
        $this->generationOperateurForm = new DSGenerationOperateurForm();
    }
    
    
     public function executeGenerationOperateur(sfWebRequest $request) { 
        $parameters = $request->getParameter('ds_generation');        
        $campagne = (!isset($parameters['campagne']))? null : $parameters['campagne'];   
        $this->etablissement = $this->getRoute()->getEtablissement();
        
        $dsExist = DSClient::getInstance()->findByCampagneAndIdentifiant($campagne,$this->etablissement->identifiant);
        if(!$dsExist){
            $this->declarationDs = DSClient::getInstance()->createDsByEtb($campagne,$this->etablissement);     
            $this->declarationDs->save();
            $this->redirect('ds_etablissement', $this->etablissement);    
        }
        else
        {
            $this->redirect('ds_etablissement', $this->etablissement); // + popup existe déja
        }
            
    }
    
     public function executeEditionDS(sfWebRequest $request) {        
         $this->ds = $this->getRoute()->getDS();
         $this->form = new DSEditionForm($this->ds);
         if ($request->isMethod(sfWebRequest::POST)) {
             $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdateObject();
                $this->ds->save();
                return $this->redirect('ds_edition_operateur_validation_visualisation',
                                        array('identifiant' => $this->ds->identifiant,'campagne' => $this->ds->campagne));
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
                return $this->redirect('ds_edition_operateur_validation_visualisation',
                                       array('identifiant' => $this->ds->identifiant,'campagne' => $this->ds->campagne));
            }
        }
    }
    
}
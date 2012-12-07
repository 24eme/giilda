<?php
class revendicationActions extends sfActions {
  
    public function executeIndex(sfWebRequest $request) {
        $this->formEtablissement = new RevendicationEtablissementChoiceForm('INTERPRO-inter-loire');
        $this->form = new CreateRevendicationForm();
        $this->historiqueImport = RevendicationClient::getInstance()->getHistory();
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                    
                    $values = $this->form->getValues();
                    $this->campagne = $values['campagne'][0];
                    $this->odg = $values['odg'][0];
                    $this->revendication = RevendicationClient::getInstance()->createOrFindDoc($this->odg,$this->campagne); 
                    return $this->redirect('revendication_edition_create', array('odg' => $this->odg, 'campagne' => $this->campagne));
            }
        }
    }
    
    public function executeEditionCreation(sfWebRequest $request) {
        $this->form = new CreateLignesForm();

//        
//        if ($request->isMethod(sfWebRequest::POST)) {
//            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
//            if ($this->form->isValid()) {
//                    
//                    $values = $this->form->getValues();
//                    $campagne = $values['campagne'][0];
//                    $odg = $values['odg'][0];
//                    $this->revendication = RevendicationClient::getInstance()->createOrFindDoc($odg,$campagne); 
//                    return $this->redirect('revendication_edition_create', array('odg' => $this->odg, 'campagne' => $this->campagne));
//            }
//        }
    }
    
    
    public function executeUpload(sfWebRequest $request) {
	$this->initIndex();
        $this->revendication = new stdClass();
	if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $file = $this->form->getValue('file');
                $this->md5 = $file->getMd5();
                RevendicationCsvFile::convertTxtToCSV(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);
                $this->csv = new RevendicationCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);
                $this->odg = $this->form->getValue('odg'); $this->odg = $this->odg[0];
                $this->campagne = $this->form->getValue('campagne'); $this->campagne = $this->campagne[0];
                if(!$this->csv->check())
                {
                    $this->errors = $this->csv->getErrors(); 
                    $this->revendication->etape = 1;
                    return sfView::SUCCESS;
                }
                    
                return $this->redirect('revendication_create', array('md5' => $file->getMD5(), 'odg' => $this->odg, 'campagne' => $this->campagne));
           }
    	}
  }
    
    public function executeMonEspace(sfWebRequest $request) {
        $this->revendication_etablissement = null;
        $this->etablissement = $this->getRoute()->getEtablissement();
        $revendication = RevendicationClient::getInstance()->find('REVENDICATION-TOURS-20122013');
        if($revendication && $revendication->getDatas()->exist($this->etablissement->cvi))
            $this->revendication_etablissement = $revendication->getDatas()->get($this->etablissement->cvi);
    }
    
    public function executeChooseEtablissement(sfWebRequest $request) {
      $this->initIndex();      
      if ($request->isMethod(sfWebRequest::POST)) {
	 $this->formEtablissement->bind($request->getParameter($this->formEtablissement->getName()));
	 if ($this->formEtablissement->isValid()) {
	   return $this->redirect('revendication_etablissement', $this->formEtablissement->getEtablissement());
	 }
       }
       $this->setTemplate('revendication_upload');
    }

    protected function initIndex() {
      $this->formEtablissement = new RevendicationEtablissementChoiceForm('INTERPRO-inter-loire');
      $this->form = new UploadCSVRevendicationForm();
      $this->errors = array();
    }
    

  public function executeDownloadCSV(sfWebRequest $request) {
        $md5 = $request->getParameter('md5');
	header("Content-type: text/csv\n");
        readfile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
        set_time_limit(600);
        $this->csv = new RevendicationCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);
        exit;
  }


  public function executeUploadCSV(sfWebRequest $request) {
        $campagne = $request->getParameter('campagne');
        $odg = $request->getParameter('odg');
        $this->csv = new RevendicationCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $file->getMd5());
  }
  
  public function executeCreate(sfWebRequest $request) {  
        set_time_limit(0);
        ini_set('memory_limit','1024M');
        $this->md5 = $request->getParameter('md5');
    	$this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id"))); 
        $path = sfConfig::get('sf_data_dir') . '/upload/' . $this->md5;
        $this->csv = new RevendicationCsvFile($path);
        $odg = $request->getParameter('odg');
        $campagne = $request->getParameter('campagne');
        $this->revendication = RevendicationClient::getInstance()->createOrFindDoc($odg,$campagne,$path);     
        $this->revendication->save();
        return $this->redirect('revendication_view_erreurs', array('odg' => $odg, 'campagne' => $campagne));

    }
  
    public function executeViewErreurs(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $this->erreurs = $this->revendication->erreurs;
    }

    public function executeEdition(sfWebRequest $request) {
        set_time_limit(0);
        ini_set('memory_limit','1024M');
        $this->revendication = $this->getRoute()->getRevendication();  
       // $this->form = new EditionRevendicationForm($this->revendication);
    }
    
    public function executeEditionRow(sfWebRequest $request) {
        ini_set('memory_limit','1024M');
        $this->revendication = $this->getRoute()->getRevendication();
        $this->cvi = $request->getParameter('cvi');
        $this->nom = $this->revendication->getDatas()->get($this->cvi)->declarant_nom;
        $this->row = $request->getParameter('row');
        $this->retour = $request->getParameter('retour');
        $etablissement = EtablissementClient::getInstance()->findByCvi($this->cvi);
        $this->form = new EditionRevendicationForm($this->revendication,$this->cvi,$this->row);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdate();
                $this->revendication->save();
                
                if ($etablissement && $this->retour == 'etablissement') {
                    
                    return $this->redirect('revendication_etablissement', $etablissement);
                }
                
                return $this->redirect('revendication_edition', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
            }
        }
    }
    
    public function executeDeleteRow(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $cvi = $request->getParameter('cvi');
        $row = $request->getParameter('row');
        $this->revendication->deleteRow($cvi,$row);
        $this->revendication->save();
        return $this->redirect('revendication_edition', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
    }

    public function executeAddAliasToProduit(sfWebRequest $request) {
        $alias = $request->getParameter('alias');
        $this->revendication = $this->getRoute()->getRevendication();
        $this->form = new AddAliasToProduitForm($this->revendication, $alias);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdate();
                $this->revendication->updateErrors();
                $this->revendication->save();
                return $this->redirect('revendication_view_erreurs', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
            }
        }
        
    }
    
        public function executeChooseRowForDoublon(sfWebRequest $request) {
        $this->num_ligne = $request->getParameter('num_ligne');
        $this->revendication = $this->getRoute()->getRevendication();
        $this->form = new ChooseRowForm($this->revendication, $this->num_ligne);
//        if ($request->isMethod(sfWebRequest::POST)) {
//            $this->form->bind($request->getParameter($this->form->getName()));
//            if ($this->form->isValid()) {
//                $this->form->doUpdate();
//                $this->revendication->updateErrors();
//                $this->revendication->save();
//                return $this->redirect('revendication_view_erreurs', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
//            }
//        }
        
    }
    
    

}
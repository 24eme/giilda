<?php
class revendicationActions extends sfActions {
  
  public function executeUpload(sfWebRequest $request) {
	$this->form = new UploadCSVRevendicationForm();
        $this->errors = array();                
	if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $file = $this->form->getValue('file');
                $this->md5 = $file->getMd5();
                RevendicationCsvFile::convertTxtToCSV(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);
                $this->csv = new RevendicationCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);
                if(!$this->csv->check())
                {
                    $this->errors = $this->csv->getErrors();               
                    return sfView::SUCCESS;
                }
                    
                $odg = $this->form->getValue('odg');
                $campagne = $this->form->getValue('campagne'); 
                return $this->redirect('revendication_create', array('md5' => $file->getMD5(), 'odg' => $odg[0], 'campagne' => $campagne[0]));
           }
    	}
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
        $this->revendication = RevendicationClient::getInstance()->createDoc($odg,$campagne,$path);                        
        $this->revendication->save();
        return $this->redirect('revendication_view_erreurs', array('odg' => $odg, 'campagne' => $campagne));

    }
  
    public function executeViewErreurs(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $this->erreursByType = $this->revendication->sortByType();
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
        $this->row = $request->getParameter('row');
        $this->form = new EditionRevendicationForm($this->revendication,$this->cvi,$this->row);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdate();
                $this->revendication->save();
                return $this->redirect('revendication_edition', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
            }
        }
    }



}

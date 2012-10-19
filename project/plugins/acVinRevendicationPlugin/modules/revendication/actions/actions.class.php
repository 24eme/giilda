<?php
class revendicationActions extends sfActions {
    
  public function executeViewupload(sfWebRequest $request) {
    $this->md5 = $request->getParameter('md5');
    set_time_limit(600);

//  RevendicationCsvFile::convertTxtToCSV(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);

    $this->csv = new RevendicationCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);


  }
  
  public function executeUpload1(sfWebRequest $request) {
	$this->form = new UploadCSVForm();
	if ($request->isMethod('post')) {
		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
		if ($this->form->isValid()) {
			$md5 = $this->form->getValue('file')->getMd5();
			RevendicationCsvFile::convertTxtToCSV(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
			return $this->redirect('revendication/viewupload?md5=' . $md5);
      		}
    	}
  }

  public function executeUpload(sfWebRequest $request) {  
      
    	$this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id")));    
        $this->form = new UploadCSVRevendicationForm();
        set_time_limit(0);
        ini_set('memory_limit','1024M');
        if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
	        //$this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
	    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));              
            if ($this->form->isValid()) {
                        $values = $this->form->getValues();
                        $file = $values['file'];
                        $path = sfConfig::get('sf_data_dir') . '/upload/' .$file->getMd5();
                        RevendicationCsvFile::convertTxtToCSV($path); 
                        $this->revendication = RevendicationClient::getInstance()->createDoc($values['odg'][0],$values['campagne'][0],$path);                        
                        $this->revendication->save();
                        return $this->redirect('revendication/viewupload?md5=' . $file->getMd5());
                        
      		}
        }
    }
  
    public function executeViewErreurs(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $this->erreursByType = $this->revendication->sortByType();
       // var_dump($this->erreursByType); exit;
    }


    /*
  public function executeUpload(sfWebRequest $request) {
	$this->form = new UploadCSVForm();
	if ($request->isMethod('post')) {
		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
		if ($this->form->isValid()) {
			$md5 = $this->form->getValue('file')->getMd5();
			RevendicationCsvFile::convertTxtToCSV(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
			return $this->redirect('revendication/viewupload?md5=' . $md5);
      		}
    	}
  }
*/

  public function executeDownloadCSV(sfWebRequest $request) {
	header("Content-type: text/csv\n");
	$md5 = $request->getParameter('md5');
	echo "";
	readfile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);	
	exit;
  }

}

<?php
class revendicationActions extends sfActions {
    
  public function executeViewupload(sfWebRequest $request) {
    $this->md5 = $request->getParameter('md5');
    set_time_limit(600);

//    RevendicationCsvFile::convertTxtToCSV(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);

    $this->csv = new RevendicationCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $this->md5);


  }

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

  public function executeDownloadCSV(sfWebRequest $request) {
	header("Content-type: text/csv\n");
	$md5 = $request->getParameter('md5');
	echo "";
	readfile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);	
	exit;
  }

}

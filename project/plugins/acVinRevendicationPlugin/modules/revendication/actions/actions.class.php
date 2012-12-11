<?php
class revendicationActions extends sfActions {
  
    public function executeIndex(sfWebRequest $request) {
        $this->formEtablissement = new RevendicationEtablissementChoiceForm('INTERPRO-inter-loire');
        $this->form = new CreateRevendicationForm();
        $this->historiqueImport = RevendicationClient::getInstance()->getHistory();
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                    $revendication = RevendicationClient::getInstance()->createOrFind($this->form->getValue('odg'), $this->form->getValue('campagne'));
                    $revendication->save();

                    return $this->redirect('revendication_upload', $revendication);
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

    public function executeUpload(sfWebRequest $request) {
        $this->errors = array();
        $this->revendication = $this->getRoute()->getRevendication();
        $this->form = new UploadCSVRevendicationForm($this->revendication);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $file = $this->form->getValue('file');
                $this->md5 = $file->getMd5();

                $path = sfConfig::get('sf_data_dir') . '/upload/' . $this->md5;

                RevendicationCsvFile::convertTxtToCSV($path);
                $this->csv = new RevendicationCsvFile($path);
                if(!$this->csv->check())
                {
                    $this->errors = $this->csv->getErrors(); 
                    $this->revendication->etape = 1;
                    return sfView::SUCCESS;
                }
                    
                $this->revendication->updateCSV($path);
                $this->revendication->save();

                return $this->redirect('revendication_view_erreurs', $this->revendication);
           }
        }
    }

    public function executeDownloadCSV(sfWebRequest $request) {
        $this->md5 = $request->getParameter('md5');
        $odg = $request->getParameter('odg');
        $this->getResponse()->setHttpHeader('Content-type', 'text/csv');
        $this->getResponse()->setHttpHeader('Content-Disposition', sprintf('filename="odg-%s-%s.csv"', $odg, $this->md5));
        $this->setLayout(false);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $this->revendication->storeDatas();
        $this->revendication->save();

        return $this->redirect('revendication_view_erreurs', $this->revendication);
    }

    public function executeViewErreurs(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $this->erreurs = $this->revendication->erreurs;
    }

    public function executeEdition(sfWebRequest $request) {
        set_time_limit(0);
        $this->revendications = RevendicationStocksODGView::getInstance()->findByCampagneAndODG('20122013', 'tours');
        $this->revendication = $this->getRoute()->getRevendication();
        //$this->form = new EditionRevendicationForm($this->revendication);
    }
    
    public function executeEditionRow(sfWebRequest $request) {
        ini_set('memory_limit','1024M');
        $this->revendication = $this->getRoute()->getRevendication();
        $this->identifiant = $request->getParameter('identifiant');
        $this->row = $request->getParameter('row');
        $this->rev = $this->revendication->datas->{$this->identifiant};
        $this->retour = $request->getParameter('retour');
        $etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        $this->form = new EditionRevendicationForm($this->revendication, $this->identifiant, $this->rev, $this->row);
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
        $identifiant = $request->getParameter('identifiant');
        $row = $request->getParameter('row');
        $this->revendication->deleteRow($identifiant,$row);
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
<?php
class revendicationActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        if(!isset($this->formEtablissement)) {
            $this->formEtablissement = null;
        }
        $this->form = new CreateRevendicationForm();
        $this->historiqueImport = RevendicationClient::getInstance()->getHistory(null);
        if ($request->isMethod(sfWebRequest::POST) && is_null($this->formEtablissement)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                    $revendication = RevendicationClient::getInstance()->createOrFind($this->form->getValue('odg'), $this->form->getValue('campagne'));
                    $revendication->etape = 1;
                    $revendication->save();

                    return $this->redirect('revendication_upload', $revendication);
            }
        }

        $this->setTemplate('index');
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->revendication_etablissement = null;

        $this->etablissement = $this->getRoute()->getEtablissement();
    	if(!$this->etablissement) {
    	  throw new sfException("Cet établissement n'a pas de volume renvendiqué");
    	}

        $this->formCampagne($request, 'revendication_etablissement');

        $this->odg = RevendicationEtablissementView::getInstance()->getOdgByEtablissementAndCampagne($this->etablissement->identifiant, $this->campagne);

        if(!$this->odg) {
            $this->odg = $this->etablissement->region;
        }

        $this->revendication = RevendicationClient::getInstance()->findByOdgAndCampagne($this->odg, $this->campagne, acCouchdbClient::HYDRATE_JSON);

        $this->revendications = RevendicationEtablissementView::getInstance()->findByEtablissementAndCampagne($this->etablissement->identifiant, $this->campagne);
    }

    public function executeChooseEtablissement(sfWebRequest $request) {
        $this->formEtablissement = new RevendicationEtablissementChoiceForm('INTERPRO-inter-loire');
        if ($request->isMethod(sfWebRequest::POST)) {
	        $this->formEtablissement->bind($request->getParameter($this->formEtablissement->getName()));
	        if ($this->formEtablissement->isValid()) {

               return $this->redirect('revendication_etablissement', $this->formEtablissement->getEtablissement());
	        }
       }

       $this->executeIndex($request);
    }

    public function executeUpload(sfWebRequest $request) {
        ini_set('memory_limit','2048M');
        set_time_limit(0);
        $this->errors = array();
        $this->not_valid_file = false;
        $this->revendication = $this->getRoute()->getRevendication();
        $this->form = new UploadCSVRevendicationForm($this->revendication);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $file = $this->form->getValue('file');

                $data = file_get_contents($file);
                $data = iconv(mb_detect_encoding($file), 'UTF-8//IGNORE', $data);
                $this->md5 = $file->getMd5();
                $path = sfConfig::get('sf_data_dir') . '/upload/' . $this->md5;
                file_put_contents($path, $data);

                RevendicationCsvFile::convertTxtToCSV($path);
                $this->csv = new RevendicationCsvFile($path);
                $this->revendication->updateCSV($path);
                if(!$this->csv->check())
                {
                    $this->errors = $this->csv->getErrors();
                    $this->revendication->etape = 1;
                    $this->form = new UploadCSVRevendicationForm($this->revendication);
                    return sfView::SUCCESS;
                }

                $this->revendication->save();
                return $this->redirect('revendication_update', $this->revendication);
           }else{
               $this->not_valid_file = true;
           }
        }
    }

    public function executeDownloadCSV(sfWebRequest $request) {
        $revendication = $this->getRoute()->getRevendication();
        $this->getResponse()->setHttpHeader('Content-type', 'text/csv');
        $this->getResponse()->setHttpHeader('Content-Disposition', sprintf('filename="DREV-%s-%s-%s.csv"', $revendication->odg, $revendication->campagne, $revendication->_rev));
	$this->csv = $revendication->getAttachmentUri('revendication.csv');
        $this->setLayout(false);
    }

    public function executeDownloadImportedRowsCSV(sfWebRequest $request) {
        $this->setLayout(false);
        $this->revendication = $this->getRoute()->getRevendication();
        $attachement = 'attachment; filename='.sprintf('DREV-%s-%s-%s-importee.csv', $this->revendication->odg, $this->revendication->campagne, $this->revendication->_rev);
        header("content-type: text/csv\n");
        header("content-disposition: $attachement\n\n");
        echo RevendicationClient::getCsvImportedRows($this->revendication);
        exit;
    }

    public function executeUpdate(sfWebRequest $request) {
        ini_set('memory_limit','2048M');
        set_time_limit(0);
        $this->revendication = $this->getRoute()->getRevendication();
        $this->revendication->storeDatas();
        print_r(utf8_encode($this->revendication->toJson()));
        $this->revendication->save();
        return $this->redirect('revendication_view_erreurs', $this->revendication);
    }

    public function executeViewErreurs(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $this->erreurs = $this->revendication->erreurs;
    }

    public function executeEdition(sfWebRequest $request) {
        ini_set('memory_limit','2048M');
        set_time_limit(0);
        $this->revendication = $this->getRoute()->getRevendication();
        $this->revendications = RevendicationStocksODGView::getInstance()->findByCampagneAndODG($this->revendication->campagne, $this->revendication->odg);
        //$this->form = new EditionRevendicationForm($this->revendication);
    }

    public function executeEditionRow(sfWebRequest $request) {
        ini_set('memory_limit','2048M');
        set_time_limit(0);
        $this->odg = $request->getParameter('odg');
        $this->campagne = $request->getParameter('campagne');
        $this->revendication = RevendicationClient::getInstance()->find(RevendicationClient::getInstance()->getId($this->odg, $this->campagne), acCouchdbClient::HYDRATE_JSON);
        $this->identifiant = $request->getParameter('identifiant');
        $this->produit = $request->getParameter('produit');
        $this->row = $request->getParameter('row');
        $this->rev = $this->revendication->datas->{$this->identifiant};
        $this->retour = $request->getParameter('retour');

        $this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        $this->form = new EditionRevendicationForm($this->revendication, $this->identifiant, $this->produit, $this->row);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->revendication = $this->form->doUpdate();
                RevendicationClient::getInstance()->storeDoc($this->revendication);
                if ($this->etablissement && $this->retour == 'etablissement') {
                    return $this->redirect('revendication_etablissement', $this->etablissement);
                }
                return $this->redirect('revendication_edition', array('odg' => $this->odg, 'campagne' => $this->campagne));
            }
        }
    }

    public function executeDeleteRow(sfWebRequest $request) {
        $this->odg = $request->getParameter('odg');
        $this->campagne = $request->getParameter('campagne');
        $this->revendication = RevendicationClient::getInstance()->find(RevendicationClient::getInstance()->getId($this->odg, $this->campagne), acCouchdbClient::HYDRATE_JSON);
        RevendicationClient::getInstance()->deleteRow($this->revendication,$request->getParameter('identifiant'),$request->getParameter('produit'),$request->getParameter('row'));

        return $this->redirect('revendication_edition', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
    }

    public function executeAddAliasToProduit(sfWebRequest $request) {
        ini_set('memory_limit','2048M');
        set_time_limit(0);
        $alias = $request->getParameter('alias');
        $this->revendication = $this->getRoute()->getRevendication();
        $this->form = new AddAliasToProduitForm($this->revendication, $alias);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdate();
               // $this->revendication->updateErrors(RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS, $alias);
                $this->revendication->save();
                return $this->redirect('revendication_update', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
               }
        }

    }

    public function executeAddAliasToBailleur(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        $this->etablissement = EtablissementClient::getInstance()->find($request->getParameter('identifiant'));
        $this->alias = urldecode($request->getParameter('alias'));
        $this->form = new AddAliasToEtablissementForm($this->etablissement,$this->alias);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdate();
                return $this->redirect('revendication_update', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
            }
        }
    }


    public function executeDeleteLine(sfWebRequest $request) {
        $num_ligne = $request->getParameter('num_ligne');
        $num_ca = $request->getParameter('num_ca');
        $this->revendication = $this->getRoute()->getRevendication();
	$this->revendication->addIgnoredLine($num_ligne, $num_ca);
        $this->revendication->save();
        return $this->redirect('revendication_update', array('odg' => $this->revendication->odg, 'campagne' => $this->revendication->campagne));
    }

    public function executeAddRows(sfWebRequest $request) {
        $this->odg = $request->getParameter('odg');
        $this->campagne = $request->getParameter('campagne');
        $this->revendication = RevendicationClient::getInstance()->find(RevendicationClient::getInstance()->getId($this->odg, $this->campagne), acCouchdbClient::HYDRATE_JSON);
        $this->form = new AddRowRevendicationForm($this->revendication);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->revendication = $this->form->doUpdate();
                RevendicationClient::getInstance()->storeDoc($this->revendication);
                return $this->redirect('revendication_edition', array('odg' => $this->odg, 'campagne' => $this->campagne));
            }
        }
    }

    public function executeDelete(sfWebRequest $request) {
        $this->revendication = $this->getRoute()->getRevendication();
        RevendicationClient::getInstance()->deleteRevendication($this->revendication);
        return $this->redirect('revendication');
    }

    protected function formCampagne(sfWebRequest $request, $route) {
        $this->etablissement = $this->getRoute()->getEtablissement();

        $this->campagne = $request->getParameter('campagne');
        if (!$this->campagne) {
            $this->campagne = ConfigurationClient::getInstance()->getCurrentCampagne();
        }

        $this->formCampagne = new RevendicationEtablissementCampagneForm($this->etablissement->identifiant, $this->campagne);


        if ($request->isMethod(sfWebRequest::POST)) {
            $param = $request->getParameter($this->formCampagne->getName());
            if ($param) {
                $this->formCampagne->bind($param);
                return $this->redirect($route, array('identifiant' => $this->etablissement->getIdentifiant(), 'campagne' => $this->formCampagne->getValue('campagne')));
            }
        }
    }

    public function executeAccueil(sfWebRequest $request) {

        return $this->redirect('/odg/');
    }

    public function executeEtablissement(sfWebRequest $request) {

        return $this->redirect('/odg/declarations/'.$request->getParameter('identifiant'));
    }

    public function executeTeledeclarant(sfWebRequest $request) {
        $compte = CompteClient::getInstance()->find("COMPTE-".$request->getParameter('identifiant'));
        $societe = $compte->getSociete();

        if(!$societe->exist('legal_signature') || !$societe->legal_signature->exist('drev')) {

            return $this->redirect('drev_legal_signature', array('identifiant' => $request->getParameter('identifiant')));
        }

        return $this->redirect('/odg/declarations/'.$request->getParameter('identifiant'));
    }

    public function executeLegalSignature(sfWebRequest $request) {
        $this->compte = CompteClient::getInstance()->find("COMPTE-".$request->getParameter('identifiant'));
        $this->societe = $this->compte->getSociete();
        $this->legalSignatureForm = new DRevLegalSignatureForm($this->societe);

        if (!$request->isMethod(sfRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->legalSignatureForm->bind($request->getParameter($this->legalSignatureForm->getName()));

        if (!$this->legalSignatureForm->isValid()) {

            return sfView::SUCCESS;
        }

        $this->legalSignatureForm->save();

        return $this->redirect('drev_teledeclarant', array('identifiant' => $request->getParameter('identifiant')));
    }

}

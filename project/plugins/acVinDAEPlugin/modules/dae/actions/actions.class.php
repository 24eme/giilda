<?php
class daeActions extends sfActions {

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->periode = new DateTime($request->getParameter('periode', date('Y-m-d')));
        $this->formCampagne($request, 'dae_etablissement');
        $this->daes = DAEClient::getInstance()->findByIdentifiantPeriode($this->etablissement->identifiant, $this->periode->format('Ym'))->getDatas();
    }

    private function formCampagne(sfWebRequest $request, $route) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->periode = new DateTime($request->getParameter('periode', date('Y-m-d')));

        $this->formCampagne = new DAEEtablissementCampagneForm($this->etablissement->identifiant, $this->periode->format('Y-m'));

        if ($request->isMethod(sfWebRequest::POST)) {
            $param = $request->getParameter($this->formCampagne->getName());
            if ($param) {
                $this->formCampagne->bind($param);
                return $this->redirect($route, array('identifiant' => $this->etablissement->getIdentifiant(), 'campagne' => $this->formCampagne->getValue('campagne')));
            }
        }
    }

    public function executeNouveau(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->periode = new DateTime($request->getParameter('periode', date('Y-m-d')));

        $this->withlast = $request->getParameter('withlast', null);
        $this->last = null;

        if ($this->withlast) {
        	$this->last = DAEClient::getInstance()->findLastByIdentifiantDate($this->etablissement->getIdentifiant(), $this->periode->format('Ymd'));
        }

        $this->dae = ($id = $request->getParameter('id'))? DAEClient::getInstance()->find($id) : DAEClient::getInstance()->createSimpleDAE($this->etablissement->getIdentifiant(), $this->periode->format('Y-m-d'));
        if ($this->last && !$request->getParameter('id')) {
        	$this->dae->initByDae($this->last);
        }

        $this->form = new DAENouveauForm($this->dae);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->dae = $this->form->save();
                return ($this->withlast)?  $this->redirect('dae_nouveau', array('sf_subject' => $this->etablissement, 'periode' => $this->periode->format('Y-m-d'), 'withlast' => 1)) : $this->redirect('dae_etablissement', $this->dae);
            }
        }
    }

    public function executeExportEdi(sfWebRequest $request) {
    	$this->etablissement = $this->getRoute()->getEtablissement();
    	$daes = DAEClient::getInstance()->findByIdentifiant($this->etablissement->identifiant, acCouchdbClient::HYDRATE_JSON)->getDatas();
    	$csv = null;

        $export = new DAEExportCsvEdi($daes);

        $csv = $export->exportEDI();

    	$this->response->setContentType('text/csv');
    	$this->response->setHttpHeader('md5', md5($csv));
    	$this->response->setHttpHeader('Content-Disposition', "attachment; filename=DAE-".$this->etablissement->identifiant.".csv");
    	return $this->renderText($csv);
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeUploadEdi(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->md5 = $request->getParameter('md5',null);
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = str_ireplace('-','',$request->getParameter('periode'));
        $path =  null;
        if($this->md5){
            $fileName = 'import_daes_'.$this->identifiant . '_' . $this->periode.'_'.$this->md5.'.csv';
            $path = sfConfig::get('sf_data_dir') . '/upload/' . $fileName;
        }

        $this->daeCsvEdi = new DAEImportCsvEdi($path, $this->identifiant, $this->periode);
        $this->daeCsvEdi->checkCSV();

        $this->erreurs = $this->daeCsvEdi->getCsvDoc()->erreurs;
        $this->hasCsvAttachement = $this->daeCsvEdi->getCsvDoc()->hasCsvAttachement();

        $this->form = new DAESCSVUploadForm(array(), array('identifiant' => $this->identifiant,'periode' => $this->periode));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $this->md5 = $this->form->getValue('file')->getMd5();
                $fileName = 'import_daes_'.$this->identifiant . '_' . $this->periode.'_'.$this->md5.'.csv';
                rename(sfConfig::get('sf_data_dir') . '/upload/'.$this->md5,  sfConfig::get('sf_data_dir') . '/upload/'.$fileName);
                return $this->redirect('dae_upload_fichier_edi', array('md5' => $this->md5,'identifiant' => $this->identifiant,'periode' => $this->periode));
            }
        }

        if ($this->md5 && !count($this->erreurs) && $this->hasCsvAttachement) {
          return $this->redirect('dae_creation_fichier_edi', array('md5' => $this->md5,'identifiant' => $this->identifiant,'periode' => $this->periode));
        }
    }

        /**
     *
     * @param sfWebRequest $request
     */
    public function executeCreationEdi(sfWebRequest $request) {
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $this->md5 = $request->getParameter('md5',null);
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = str_ireplace('-','',$request->getParameter('periode'));

        $path =  null;
        if($this->md5){
            $fileName = 'import_daes_'.$this->identifiant . '_' . $this->periode.'_'.$this->md5.'.csv';
            $path = sfConfig::get('sf_data_dir') . '/upload/' . $fileName;
        }

        $this->daeCsvEdi = new DAEImportCsvEdi($path, $this->identifiant,$this->periode);
        $this->daeCsvEdi->importCSV();


        $this->redirect('dae_etablissement', array('identifiant' => $this->identifiant, "periode" => preg_replace("/([0-9]{4})([0-9]{2})/","$1-$2",$this->periode)));

    }

    protected function cleanCsvLine($line) {
    	return str_replace('/', '_',  $line);
    }

    protected function explodeProduct($hash) {
    	if (!preg_match('/^\/declaration\/certifications\/([a-zA-Z0-9]+)\/genres\/([a-zA-Z0-9]+)\/appellations\/([a-zA-Z0-9]+)\/mentions\/([a-zA-Z0-9]+)\/lieux\/([a-zA-Z0-9]+)\/couleurs\/([a-zA-Z0-9]+)\/cepages\/([a-zA-Z0-9]+)$/', $hash, $m)) {
    		$m = array(null,null,null,null,null,null,null,null);
    	}
    	$product = new stdClass();
    	$product->certification = ($m[1] != 'DEFAUT')? $m[1] : null;
    	$product->genre = ($m[2] != 'DEFAUT')? $m[2] : null;
    	$product->appellation = ($m[3] != 'DEFAUT')? $m[3] : null;
    	$product->mention = ($m[4] != 'DEFAUT')? $m[4] : null;
    	$product->lieu = ($m[5] != 'DEFAUT')? $m[5] : null;
    	$product->couleur = ($m[6] != 'DEFAUT')? $m[6] : null;
    	$product->cepage = ($m[7] != 'DEFAUT')? $m[7] : null;
    	return $product;
    }

}

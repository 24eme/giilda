<?php
class factureActions extends sfActions {
    
  public function executeIndex(sfWebRequest $request) {
      $this->form = new FactureEtablissementChoiceForm('INTERPRO-inter-loire');
      $this->generationForm = new FactureGenerationMasseForm();
      $this->generations = GenerationClient::getInstance()->findHistory();
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	   return $this->redirect('facture_etablissement', $this->form->getEtablissement());
	 }
       }
    }
        
   public function executeMasse(sfWebRequest $request) {
       $parameters = $request->getParameter('facture_generation');
       
       $parameters['date_facturation'] = (!isset($parameters['date_facturation']))? null : $parameters['date_facturation'];
       $regions = (!isset($parameters['region']))? null : $parameters['region'];
       $allMouvements = FactureClient::getInstance()->getMouvementsForMasse($regions,9); 
       $mouvementsByEtb = FactureClient::getInstance()->getMouvementsNonFacturesByEtb($allMouvements);       
       $mouvementsByEtb = FactureClient::getInstance()->filterWithParameters($mouvementsByEtb,$parameters);
       
       if($mouvementsByEtb)
       {
       $generation = FactureClient::getInstance()->createFacturesByEtb($mouvementsByEtb,$parameters['date_facturation']);
       $generation->save();
       }
        $this->generations = GenerationClient::getInstance()->findHistory();
       $this->setTemplate('index');
    }
    
    public function executeMonEspace(sfWebRequest $resquest) {
        
        $this->form = new FactureGenerationForm();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->factures = FactureEtablissementView::getInstance()->findByEtablissement($this->etablissement);
        $this->mouvements = MouvementFacturationView::getInstance()->getMouvementsNonFacturesByEtablissement($this->etablissement);
    }
    
    public function executeDefacturer(sfWebRequest $resquest) {
        $this->facture = $this->getRoute()->getFacture();
        $this->facture->defacturer();
        $this->facture->save();
        $this->etablissement = EtablissementClient::getInstance()->findByIdentifiant($this->facture->client_reference);
        $this->redirect('facture_etablissement', $this->etablissement);        
    }


    public function executeGenerer(sfWebRequest $request) {
        $parameters = $request->getParameter('facture_generation');
        $parameters['date_facturation'] = (!isset($parameters['date_facturation']))? null : $parameters['date_facturation'];
        
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->facturations = FactureClient::getInstance()->getFacturationForEtablissement($this->etablissement,9);        
        $this->facturations = FactureClient::getInstance()->filterWithParameters($this->facturations,$parameters);
        
        $facture = FactureClient::getInstance()->createDoc($this->facturations,$this->etablissement,$parameters['date_facturation']);
        $facture->save();
        $this->redirect('facture_etablissement', $this->etablissement);
    }



    public function executeLatex(sfWebRequest $request) {
        
        $this->setLayout(false);
        
        $this->facture = FactureClient::getInstance()->findByEtablissementAndId($this->getRoute()->getEtablissement()->identifiant, $request->getParameter('factureid'));
        $this->forward404Unless($this->facture);
        $templateFormat = FactureClient::getInstance()->getTemplate($this->facture->nb_page);
        
        $this->srcPdf = $this->getPartial('generateTex',array('facture' => $this->facture,
                                                              'template' => $templateFormat,
                                                              'total_rows' => FactureClient::MAX_LIGNE_TEMPLATE_ONEPAGE));
        
        $this->srcTexFilename = $this->facture->identifiant.'_'.$this->facture->client_reference.'-'.$templateFormat;
        $this->extTex = 'tex';
        $this->statut = $this->creerFichier($this->srcTexFilename, $this->extTex,  $this->srcPdf);
        
        $cmdCompileLatex = '/usr/bin/pdflatex -output-directory='.$this->getLatexTmpPath().' -synctex=1 -interaction=nonstopmode '.$this->getLatexPath().$this->srcTexFilename.'.'.$this->extTex.' 2> /dev/null';

        $output = exec($cmdCompileLatex, $output, $ret);
	if (!preg_match('/^Transcript written/', $output)) {
	  throw new sfException($output);
	}

	if ($ret) {
	  $log = file($this->getLatexTmpPath().$this->srcTexFilename.".log");
	  $grep = preg_grep('/^!/', $log);
	  array_unshift($grep, "/!\ Latex error\n");
	  throw new sfException(implode(' ', $grep));
	}

        $pdfFile = $this->getLatexTmpPath().$this->srcTexFilename.".pdf";
        $attachement = "attachment; filename=".$this->srcTexFilename.".pdf";
        header("content-type: application/pdf\n");
        header("content-length: ".filesize($pdfFile)."\n");
        header("content-disposition: $attachement\n\n");
        echo file_get_contents($pdfFile);
        unlink($this->getLatexTmpPath().$this->srcTexFilename.".aux");
	unlink($this->getLatexTmpPath().$this->srcTexFilename.".log");
        exit;
    }
    
    private function getLatexPath() {
        return sfConfig::get('sf_root_dir')."/data/latex/";
    }
    
    private function getLatexTmpPath() {
        return "/tmp/";
    }
    
    private function creerFichier($fichierNom, $fichierExtension, $fichierContenu, $droit=""){
        $fichierCheminComplet = $this->getLatexPath().$fichierNom;
        if($fichierExtension!=""){
        $fichierCheminComplet = $fichierCheminComplet.".".$fichierExtension;
        }

        // création du fichier sur le serveur
        $leFichier = fopen($fichierCheminComplet, "w");
	if (!$leFichier) {
	  throw new sfException("Cannot write on ".$fichierCheminComplet);
	}
        fwrite($leFichier, html_entity_decode(htmlspecialchars_decode($fichierContenu),HTML_ENTITIES));
        fclose($leFichier);

        // la permission
        if($droit==""){
        $droit="0600";
        }

        // on vérifie que le fichier a bien été créé
        $t_infoCreation['fichierCreer'] = false;
        if(file_exists($fichierCheminComplet)==true){
        $t_infoCreation['fichierCreer'] = true;
        }

        // on applique les permission au fichier créé
        $retour = chmod($fichierCheminComplet,intval($droit,8));
        $t_infoCreation['permissionAppliquer'] = $retour;

        return $t_infoCreation;
    }
}
<?php
class factureActions extends sfActions {
    
  public function executeIndex(sfWebRequest $request) {
      $this->form = new FactureEtablissementChoiceForm();
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
       
       //$request['region'],$request['seuil_facture'],$request['seuil_avoir'],$request['date_mouvement'];
       $allMouvements = FactureClient::getInstance()->getMouvementsNonFacturesMasse('','','','');       
       $mouvementsByEtb = FactureClient::getInstance()->getMouvementsNonFacturesByEtb($allMouvements);
       $generation = FactureClient::getInstance()->createFacturesByEtb($mouvementsByEtb);
       $generation->save();
       $this->setTemplate('index');
    }
    
    public function executeMonEspace(sfWebRequest $resquest) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->factures = FactureClient::getInstance()->findByEtablissement($this->etablissement);
        $this->mouvements = DRMMouvementsFactureView::getInstance()->getAFactureByEtablissement($this->etablissement);
    }
    
    public function executeGenerer(sfWebRequest $resquest) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->facturations = FactureClient::getInstance()->getMouvementsNonFacturesByEtablissement($this->etablissement);
        $facture = FactureClient::getInstance()->createDoc($this->facturations,$this->etablissement);
        $facture->save();
        $this->redirect('facture_etablissement', $this->etablissement);
    }



    public function executeLatex(sfWebRequest $request) {
        
        $this->setLayout(false);
        
        $this->facture = FactureClient::getInstance()->findByEtablissementAndId($this->getRoute()->getEtablissement()->identifiant, $request->getParameter('factureid'));
        $this->forward404Unless($this->facture);
        
        $this->srcPdf = $this->getPartial('generateTex',array('facture' => $this->facture));
                
        $this->srcTexFilename = $this->facture->identifiant.'-'.count($this->facture->lignes);
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
        $attachement = "attachment; filename=".$pdfFile;
        header("content-type: application/pdf\n");
        header("content-length: ".filesize($pdfFile)."\n");
        header("content-disposition: $attachement\n\n");
        echo file_get_contents($pdfFile);
        unlink($this->getLatexTmpPath().$this->srcTexFilename.".aux");
	unlink("/tmp/".$this->srcTexFilename.".log");
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
<?php
class factureActions extends sfActions {
    
  public function executeIndex(sfWebRequest $request) {

    
       $this->form = new EtablissementChoiceForm();
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	   return $this->redirect('facture_etablissement', $this->form->getEtablissement());
	 }
       }
    }
        
  public function executeMonEspace(sfWebRequest $resquest) {
    $this->etablissement = $this->getRoute()->getEtablissement();
    $this->factures = FactureClient::getInstance()->findByEtablissement($this->etablissement);
  }

    public function executeLatex(sfWebRequest $request) {
        
        $this->setLayout(false);
        
        $this->facture = FactureClient::getInstance()->findByEtablissementAndId($this->getRoute()->getEtablissement()->identifiant, $request->getParameter('factureid'));
        $this->forward404Unless($this->facture);
        
        $this->srcPdf = $this->getPartial('generateTex',array('facture' => $this->facture));

        $this->srcTexFilename = $this->facture->identifiant.'-'.count($this->facture->lignes);
        $this->extTex = 'tex';
        $this->statut = $this->creerFichier('/data',$this->srcTexFilename, $this->extTex,  $this->srcPdf);
        
        unlink("/tmp/".$this->srcTexFilename."*.pdf");
        
        $cmdCompileLatex = '/usr/bin/pdflatex -output-directory=/tmp/ -synctex=1 -interaction=nonstopmode data/'.$this->srcTexFilename.'.'.$this->extTex.' 2> /dev/null ; chown www-data '.$this->srcTexFilename.'.pdf';

        $output = exec($cmdCompileLatex);
        $pdfFile = $this->srcTexFilename.".pdf";
        //print $output;
        
      //  $this->forward404Unless($this->);
        $attachement = "attachment; filename=".$pdfFile;
        header("content-type: application/pdf\n");
        //header("content-length: ".filesize($pdfFile)."\n");
        header("content-disposition: $attachement\n\n");
        echo file_get_contents("/tmp/".$pdfFile);
        unlink("/tmp/".$this->srcTexFilename.".aux");
        unlink("/tmp/".$this->srcTexFilename.".log");
        exit;
    }
    
    private function creerFichier($fichierChemin, $fichierNom, $fichierExtension, $fichierContenu, $droit=""){
        $fichierCheminComplet = $_SERVER["DOCUMENT_ROOT"].$fichierChemin."/".$fichierNom;
        if($fichierExtension!=""){
        $fichierCheminComplet = $fichierCheminComplet.".".$fichierExtension;
        }

        // création du fichier sur le serveur
        $leFichier = fopen($fichierCheminComplet, "w");
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
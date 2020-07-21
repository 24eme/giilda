<?php

class subventionActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

    }

    public function executeEtablissement(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->subventions = SubventionClient::getInstance()->findByEtablissementSortedByDate($this->etablissement->identifiant);

    }

    public function executeCreation(sfWebRequest $request) {
        $etablissement = $this->getRoute()->getEtablissement();

        $subvention = SubventionClient::getInstance()->createDoc($etablissement->identifiant, $request->getParameter('operation'));
        $subvention->save();

        return $this->redirect('subvention_infos', $subvention);
    }

    public function executeInfos(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();

        $this->form = new SubventionsInfosForm($this->subvention);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }
        $this->form->bind($request->getParameter($this->form->getName()));

      	if (!$this->form->isValid()) {
      		return sfView::SUCCESS;
      	}
        $this->form->save();

        $this->redirect('subvention_dossier', $this->subvention);

    }

    public function executeEngagements(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();
        $this->form = new SubventionEngagementsForm($this->subvention);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {
            return sfView::SUCCESS;
        }

        $this->form->save();

        return $this->redirect($this->generateUrl('subvention_validation', $this->subvention));
    }

    public function executeValidation(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();
        $this->form = new SubventionValidationForm($this->subvention);

        if (!$request->isMethod(sfWebRequest::POST)) {
            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if (!$this->form->isValid()) {
            return sfView::SUCCESS;
        }

        $this->form->save();

        return $this->redirect($this->generateUrl('subvention_visualisation', $this->subvention));
    }

    public function executeValidationInterpro(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();
        $this->formValidationInterpro = new SubventionValidationInterproForm($this->subvention);
        $this->subvention->validateInterpro('VALIDE_INTERPRO');
        $this->subvention->save();

        $this->setTemplate('visualisation');

        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->formValidationInterpro->bind($request->getParameter($this->formValidationInterpro->getName()));

        if (!$this->formValidationInterpro->isValid()) {
            return sfView::SUCCESS;
        }

        $this->formValidationInterpro->save();

        return $this->redirect($this->generateUrl('subvention_visualisation', $this->subvention));
    }

    public function executeVisualisation(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();
    }

    public function executeDossier(sfWebRequest $request) {

        $this->subvention = $this->getRoute()->getSubvention();
        $this->form = new SubventionDossierForm($this->subvention);

        if (!$request->isMethod(sfWebRequest::POST)) {
      		return sfView::SUCCESS;
      	}
      	$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

      	if (!$this->form->isValid()) {
      		return sfView::SUCCESS;
      	}
        $this->form->save();

        $this->redirect('subvention_engagements', array('identifiant' => $this->subvention->identifiant,'operation' => $this->subvention->operation));

    }

    public function executeXls(sfWebRequest $request) {

      $this->subvention = $this->getRoute()->getSubvention();
      $this->setLayout(false);
      $path = $this->subvention->getXlsPath();
      $this->getResponse()->setHttpHeader('Content-disposition', 'attachment; filename="' . $this->subvention->getXlsPublicName() . '"');
      $this->getResponse()->setHttpHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //  $this->getResponse()->setHttpHeader('Content-Length', filesize($path));
      $this->getResponse()->setHttpHeader('Pragma', '');
      $this->getResponse()->setHttpHeader('Cache-Control', 'public');
      $this->getResponse()->setHttpHeader('Expires', '0');
      return $this->renderText(file_get_contents($path));
    }

    public function executeLatex(sfWebRequest $request) {
        $this->setLayout(false);
        $this->subvention = $this->getRoute()->getSubvention();
        $this->forward404Unless($this->subvention);
        $latex = new SubventionLatex($this->subvention);
        $latex->echoWithHTTPHeader($request->getParameter('type'));
        exit;
    }
    
    public function executeZip(sfWebRequest $request) {
        $this->setLayout(false);
        $this->subvention = $this->getRoute()->getSubvention();
        $this->forward404Unless($this->subvention);
        $name = $this->subvention->_id.'_'.$this->subvention->_rev;
        $target = '/tmp/'.$name;
        $zipname = $name.'.zip';
        exec('mkdir -p '.$target.'/');
        $latex = new SubventionLatex($this->subvention);
        $pdf = $latex->generatePDF();
        rename($pdf, $target.'/'.$name.'.pdf');
        file_put_contents($target.'/'.$this->subvention->getXlsPublicName(), file_get_contents($this->subvention->getXlsPath()));
        exec('zip -j -r '.$target.$zipname.' '.$target.'/');
        
        $this->getResponse()->clearHttpHeaders();
        $this->getResponse()->setContentType('application/force-download');
        $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename="' . $target.$zipname .'"');
        $this->getResponse()->setHttpHeader('Content-Type', 'application/zip');
        $this->getResponse()->setHttpHeader('Pragma', '');
        $this->getResponse()->setHttpHeader('Cache-Control', 'public');
        $this->getResponse()->setHttpHeader('Expires', '0');
        
        $this->getResponse()->setContent(file_get_contents($target.$zipname));
        $this->getResponse()->send();
    }
}

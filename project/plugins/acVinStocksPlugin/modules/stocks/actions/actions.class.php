<?php
class stocksActions extends sfActions {
    public function executeIndex(sfWebRequest $request) {    
        $this->form = new StocksEtablissementChoiceForm('INTERPRO-inter-loire');
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                
                return $this->redirect('stocks_etablissement', $this->form->getEtablissement());
            }
        }

        $this->setTemplate('index');
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->formCampagne($request, 'stocks_etablissement');
    }

    private function formCampagne(sfWebRequest $request, $route) {
        $this->etablissement = $this->getRoute()->getEtablissement();
      
        $this->campagne = $request->getParameter('campagne');
        if (!$this->campagne) {
            $this->campagne = ConfigurationClient::getInstance()->getCurrentCampagne();
        }
      
        if ($this->etablissement->isNegociant()) {
            $this->formCampagne = new VracEtablissementCampagneForm($this->etablissement->identifiant, $this->campagne);
        } elseif($this->etablissement->isViticulteur()) {
            $this->formCampagne = new DRMEtablissementCampagneForm($this->etablissement->identifiant, $this->campagne);
        }
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $param = $request->getParameter($this->formCampagne->getName());
            if ($param) {
                $this->formCampagne->bind($param);
                return $this->redirect($route, array('identifiant' => $this->etablissement->getIdentifiant(), 'campagne' => $this->formCampagne->getValue('campagne')));
            }
        }
    }
}
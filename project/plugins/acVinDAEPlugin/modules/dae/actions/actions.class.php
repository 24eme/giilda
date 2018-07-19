<?php
class daeActions extends sfActions {

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->periode = new DateTime($request->getParameter('periode', date('Y-m')).'-01');
        $this->formCampagne($request, 'dae_etablissement');
        $this->daes = DAEClient::getInstance()->findByIdentifiantPeriode($this->etablissement->identifiant, $this->periode->format('Ym'))->getDatas();
    }

    private function formCampagne(sfWebRequest $request, $route) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->periode = new DateTime($request->getParameter('periode', date('Y-m')).'-01');

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
        $this->periode = new DateTime($request->getParameter('periode', date('Y-m')).'-01');
        
        $this->dae = ($id = $request->getParameter('id'))? DAEClient::getInstance()->find($id) : DAEClient::getInstance()->createSimpleDAE($this->etablissement->getIdentifiant());
        $this->form = new DAENouveauForm($this->dae);
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->dae = $this->form->save();
                return $this->redirect('dae_etablissement', $this->dae);
            }
        }
    }

}

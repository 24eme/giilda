<?php
class relanceActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->form = new RelanceEtablissementChoiceForm('INTERPRO-inter-loire');
//        $this->alertesHistorique = AlerteHistoryView::getInstance()->getHistory();
//        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesHistorique);
        if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	  return $this->redirect('relance_etablissement', $this->form->getEtablissement());
          }
         }
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->relances = RelanceEtablissementView::getInstance()->findByEtablissement($this->etablissement); 
        $this->alertes = AlerteRelanceView::getInstance()->getRechercheByEtablissementAndStatut($this->etablissement->identifiant, AlerteClient::STATUT_A_RELANCER);
    }
    
    public function executeGenererEtablissement(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->alertes_relance = AlerteRelanceView::getInstance()->getRechercheByEtablissementAndStatutSorted($this->etablissement->identifiant, AlerteClient::STATUT_A_RELANCER);
        if(count($this->alertes_relance)){
            $generation = RelanceClient::getInstance()->createRelancesByEtb($this->alertes_relance, $this->etablissement);
            $generation->save();
        }
         $this->redirect('relance_etablissement', $this->etablissement);
    }
    
    
   public function executeLatex(sfWebRequest $request) {
        $this->relance = $this->getRoute()->getRelance();
        $this->forward404Unless($this->relance);
	$latex = new RelanceLatex($this->relance);
	$latex->echoWithHTTPHeader($request->getParameter('type'));
    }


}


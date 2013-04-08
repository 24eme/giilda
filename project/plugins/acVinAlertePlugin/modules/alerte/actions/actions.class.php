<?php

class alerteActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->alertesHistorique = AlerteHistoryView::getInstance()->getHistory();
        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesHistorique);
        $this->form = new AlertesConsultationForm();
        $this->dateAlerte = AlerteDateClient::getInstance()->find(AlerteDateClient::getInstance()->buildId());
        if(!$this->dateAlerte) $this->dateAlerte = new AlerteDate();
        $this->dateForm  = new AlertesDateForm($this->dateAlerte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $this->redirect('alerte_etablissement', array('identifiant' => $values['identifiant']));
            }
            $this->dateForm->bind($request->getParameter($this->dateForm->getName()));
                if ($this->dateForm->isValid()) {
                    $this->dateForm->save();
                    $this->redirect('alerte');
                    
                }
            }
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->alertesEtablissement = AlerteRechercheView::getInstance()->getRechercheByEtablissement($this->etablissement->identifiant);
        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesEtablissement);
    }

    public function executeModification(sfWebRequest $request) {
        $this->alertesHistorique = AlerteHistoryView::getInstance()->getHistory();
        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesHistorique);
        $this->alerte = $this->getRoute()->getAlerte();
        $this->form = new AlerteModificationForm($this->alerte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdate();
                $this->redirect('alerte_modification', $this->alerte);
            }
        }
    }

    public function executeStatutsModification(sfWebRequest $request) {
        $new_statut = $request['statut_all_alertes'];
        $new_commentaire = $request['commentaire_all_alertes'];
        foreach ($request->getParameterHolder()->getAll() as $key => $param) {
            if (!strncmp($key, 'ALERTE-', strlen('ALERTE-'))) {
                AlerteClient::getInstance()->updateStatutByAlerteId($new_statut, $new_commentaire, $key);
                $etbId = AlerteClient::getInstance()->find($key)->identifiant;
            }
        }
        if(isset($request['retour']) && $request['retour']=="etablissement"){
        $this->redirect('alerte_etablissement', array('identifiant' => $etbId));
        }else{
        $this->redirect('alerte');
        }
    }

}


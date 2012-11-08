<?php
class alerteActions extends sfActions {
  
    public function executeIndex(sfWebRequest $request) {
        $this->form = new AlertesConsultationForm();
        $this->alertesHistorique = AlerteHistoryView::getInstance()->getHistory();
    }
        
    public function executeMonEspace(sfWebRequest $request) {
         var_dump("here espace"); exit;
    }
    
    public function executeModification(sfWebRequest $request) {
         $this->alerte = $this->getRoute()->getAlerte();
         $this->form = new AlerteModificationForm($this->alerte);
         if($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if($this->form->isValid())
            {
                $this->form->doUpdate();
            }
        }
    }
}
     
    

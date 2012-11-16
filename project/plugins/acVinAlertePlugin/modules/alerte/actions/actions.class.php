<?php
class alerteActions extends sfActions {
  
    public function executeIndex(sfWebRequest $request) {
        $this->alertesHistorique = AlerteHistoryView::getInstance()->getHistory();
        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesHistorique);    
        $this->form = new AlertesConsultationForm();
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
    
    public function executeStatutsModification(sfWebRequest $request){
        $new_statut = $request['statut_all_alertes'];
         foreach ($request->getParameterHolder()->getAll() as $key => $param) {
           if (!strncmp($key, 'ALERTE-', strlen('ALERTE-'))) {
               AlerteClient::getInstance()->updateStatutByAlerteId($new_statut,$key);
              }
        }
        $this->redirect('alerte');
    }
}
     
    

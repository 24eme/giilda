<?php

class drm_cooperative_detailsActions extends sfActions
{
    
    public function executeProduit(sfWebRequest $request) {
        $this->detail = $this->getRoute()->getDRMDetail();
        $this->drm = $this->detail->getDocument();
        
        $this->form = new DRMDetailCooperativeForm($this->detail->sorties->cooperative_details);

        if ($request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            
            if($this->form->isValid()) {
                $this->form->update();
                $this->drm->update();
                $this->drm->save();
                
                $this->redirect('drm_edition_detail', $this->detail);
            }
        }
    }
}
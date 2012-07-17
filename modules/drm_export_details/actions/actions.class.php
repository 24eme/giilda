<?php

class drm_export_detailsActions extends sfActions
{
    
    public function executeProduit(sfWebRequest $request) {
        $this->detail = $this->getRoute()->getDRMDetail();
        $this->drm = $this->detail->getDocument();
        
        $this->form = new DRMDetailExportForm($this->detail->sorties->export_details);

        if ($request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            
            if($this->form->isValid()) {
                $this->form->update();
                $this->drm->save();
                
                $this->redirect('drm_export_details', $this->detail);
            }
        }
    }
}
<?php

class sv12Actions extends sfActions {

    public function executeChooseEtablissement(sfWebRequest $request) {
        $this->form = new SV12EtablissementChoiceForm();

        $this->historySv12 = SV12Client::getInstance()->retrieveLastDocs();
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                return $this->redirect('sv12_etablissement', $this->form->getEtablissement());
            }
        }
    }

    public function executeMonEspace(sfWebRequest $request) {
       // $this->historique = new SV12Historique($this->getRoute()->getEtablissement()->identifiant);
        $this->etablissement = $this->getRoute()->getEtablissement();
        
    }

    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeNouvelle(sfWebRequest $request) {

        $etbId = $request->getParameter('identifiant');
        $periode = $request->getParameter('periode');
        
        $sv12s = SV12Client::getInstance()->viewByIdentifiantAndCampagne($etbId,$periode);

        if($sv12s)
        {           
         $sv12s = array_values($sv12s);
         $sv12 = $sv12s[0];         
         return $this->renderPartial('popupWarning',array('sv12' => $sv12));
        // $this->redirect('sv12_etablissement',  EtablissementClient::getInstance()->findByIdentifiant($sv12[0]));
        }
        
        $sv12 = SV12Client::getInstance()->createDoc($etbId,$periode);
        $sv12->save();
        $this->redirect('sv12_update', $sv12);
    }

    public function executeUpdate(sfWebRequest $request) {

        $this->sv12 = $this->getRoute()->getSV12();
        $this->contrats = SV12Client::getInstance()->retrieveContratsByEtablissement($request->getParameter('negociant_identifiant'));

        $this->form = new SV12UpdateForm($this->sv12, $this->contrats);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('sv12_recapitulatif', $this->form->getObject());
            }
        }
    }

    public function executeRecapitulatif(sfWebRequest $request) {
        $this->sv12 = $this->getRoute()->getSV12();
        $this->sv12ByProduitsTypes = $this->sv12->getSV12ByProduitsType();
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->valideSV12();
            $this->sv12->save();
            $this->redirect('sv12_visualisation', $this->sv12);
        }
    }
    
    public function executeVisualisation(sfWebRequest $request) {
        $this->sv12 = $this->getRoute()->getSV12();
    }
    
    public function executeBrouillon(sfWebRequest $request) {
        $this->sv12 = $this->getRoute()->getSV12();
        $this->saveBrouillonSV12();
        $this->sv12->save();
        $this->redirect('sv12');
    }

    private function valideSV12() {
        $this->sv12->validate();
    }
    
    private function saveBrouillonSV12() {
        $this->sv12->saveBrouillon();
    }
    
    

}

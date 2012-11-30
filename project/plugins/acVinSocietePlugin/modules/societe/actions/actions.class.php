<?php

class societeActions extends sfActions {

    public function executeCreationSociete(sfWebRequest $request) {
        $this->form = new SocieteCreationForm();

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $societe = SocieteClient::getInstance()->createSociete($values['raison_sociale'], $values['type']);
                $this->redirect('societe_modification', array('identifiant' => $societe->identifiant));
            }
        }
    }

    public function executeModification(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->contactSociete = CompteClient::getInstance()->find($this->societe->id_compte_societe);
        $this->societeForm = new SocieteModificationForm($this->societe);
        $this->contactSocieteForm = new CompteSocieteModificationForm($this->contactSociete);
        
        $this->etablissementSocieteForm = null;
        if ($this->societe->hasChais()) {
            $idEtablissement = $this->societe->etablissements[0]->id_etablissement;
            $etablissement = EtablissementClient::getInstance()->find($idEtablissement);
            $this->etablissementSocieteForm = new EtablissementModificationForm($etablissement);
        }
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->societeForm->bind($request->getParameter($this->societeForm->getName()));
            $this->contactSocieteForm->bind($request->getParameter($this->contactSocieteForm->getName()));
            if($this->societe->hasChais()) $this->etablissementSocieteForm->bind($request->getParameter($this->etablissementSocieteForm->getName()));
            if ($this->societeForm->isValid() && $this->contactSocieteForm->isValid()) {
                $this->societeForm->save();
                $this->contactSocieteForm->save();
                if($this->societe->hasChais()) $this->etablissementSocieteForm->save();
                var_dump('KIFFE ENGER');
                exit;
            }
        }
    }

//    
//    public function executeChooseSociete(sfWebRequest $request) {       
//       $this->form = new SocieteChoiceForm('INTERPRO-inter-loire');
//       if ($request->isMethod(sfWebRequest::POST)) {
//            $this->form->bind($request->getParameter($this->form->getName()));
//            if ($this->form->isValid()) {
//                var_dump($this->form->getSociete()); exit;
//                return $this->redirect('societe_choose', $this->form->getSociete());
//            }
//        }
//    }
//    public function executeIndex(sfWebRequest $request) {    
//        $this->form = new SocieteChoiceForm('INTERPRO-inter-loire');
//        if ($request->isMethod(sfWebRequest::POST)) {
//        //    if ($this->form->isValid()) {
//            $societeParams = $request->getParameter('societe');
//            $identifiant = $societeParams['identifiant'];
//            $type = $societeParams['societeType'];
//            $societe = SocieteClient::getInstance()->findByIdentifiant($identifiant);
//            if(!$societe) {
//                $this->redirect('societe_creation',array('nom' => $identifiant,'type' => $type));
//            }
//                $this->redirect('societe_modification',array('societe' => $societe));
//            }
//      //  }
//    }
//
//    public function executeAll(sfWebRequest $request) {
//        $interpro = $request->getParameter('interpro_id');
//        $json = $this->matchSociete(SocieteAllView::getInstance()->findByInterpro($interpro)->rows, $request->getParameter('q'), $request->getParameter('limit', 100));
//    return $this->renderText(json_encode($json));
//    }
//
//    public function executeCreateSociete(sfWebRequest $request) {
//       // $this->form = new SocieteCreationForm();
//        $societe = SocieteClient::getInstance()->createSociete($request['nom'],$request['type']);
//        return $this->redirect('societe_modification',array('societe',$societe));
////        if ($request->isMethod(sfWebRequest::POST)) {
////            $this->form->bind($request->getParameter($this->form->getName()));
////            if ($this->form->isValid()) {
////                $values = $this->form->getValues();
////                $societe = new Societe();
////                $societe->interpro = 'INTERPRO-inter-loire';
////                $societe->identifiant = $values['identifiant'];
////                $societe->siret = $values['siret'];
////                $societe->raison_sociale = $values['raison_sociale'];
////                $societe->telephone = $values['telephone'];
////                $societe->save();
////            }
////        }
//    }
//    
//    
//    public function executeEspace(sfWebRequest $request) {
//        var_dump('MA SOCIETE'); exit;
//    }
//    
//    protected function matchSociete($societes, $term, $limit) {
//    	$json = array();
//
//	  	foreach($societes as $key => $societe) {
//	      $text = SocieteAllView::getInstance()->makeLibelle($societe->key);
//	     
//	      if (Search::matchTerm($term, $text)) {
//	        $json[SocieteClient::getInstance()->getIdentifiant($societe->id)] = $text;
//	      }
//
//	      if (count($json) >= $limit) {
//	        break;
//	      }
//	    }
//	    return $json;
//	}
}

<?php

class etablissementActions extends sfCredentialActions {

    public function executeAjout(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->applyRights();
        if (!$this->modification) {
            $this->forward('acVinCompte', 'forbidden');
        }
        $this->famille = $request->getParameter('famille');
        $this->etablissement = $this->societe->createEtablissement($this->famille);
        $this->processFormEtablissement($request);
        $this->setTemplate('modification');
    }

    public function executeModification(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();

        $this->applyRights();
        if (!$this->modification) {
            $this->forward('acVinCompte', 'forbidden');
        }
        $this->processFormEtablissement($request);
    }

    protected function processFormEtablissement(sfWebRequest $request) {
        $this->etablissementModificationForm = new EtablissementModificationForm($this->etablissement);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->etablissementModificationForm->bind($request->getParameter($this->etablissementModificationForm->getName()));
            if ($this->etablissementModificationForm->isValid()) {
                $this->etablissementModificationForm->save();
                $this->redirect('etablissement_visualisation', array('identifiant' => $this->etablissement->identifiant));
            }
        }
    }

    public function executeVisualisation(sfWebRequest $request) {
        if(!SocieteConfiguration::getInstance()->isVisualisationTeledeclaration() && !$this->getUser()->hasCredential(myUser::CREDENTIAL_CONTACT) && !$this->getUser()->isStalker()) {
            return $this->forwardSecure();
        }

        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();
        $this->applyRights();
        $this->compte = $this->etablissement->getMasterCompte();
        if((!$this->compte->lat && !$this->compte->lon) || !$this->compte->hasLatLonChais()){
            $this->needUpdateLatLon = true;
        }
        $this->modifiable = $this->getUser()->hasCredential('contacts');
    }

    public function executeUpdateCoordonneesLatLon(sfWebRequest $request)
    {
        if(!SocieteConfiguration::getInstance()->isVisualisationTeledeclaration() && !$this->getUser()->hasContact() && !$this->getUser()->isStalker()) {
            return $this->forwardSecure();
        }

        $this->etablissement = $this->getRoute()->getEtablissement(array('allow_admin_odg' => true));
        $this->societe = $this->etablissement->getSociete();
        $this->compte = $this->etablissement->getMasterCompte();
        if((!$this->compte->lat && !$this->compte->lon) || !$this->compte->hasLatLonChais()){
          $this->compte->updateCoordonneesLongLat(true);
          $this->compte->save();
        }

        return $this->redirect('etablissement_visualisation', ['identifiant' => $this->etablissement->identifiant]);
    }

     public function executeSwitchStatus(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $newStatus = "";
        if($this->etablissement->isActif() || !$this->etablissement->statut){
           $newStatus = SocieteClient::STATUT_SUSPENDU;
        }
        if($this->etablissement->isSuspendu()){
           $newStatus = SocieteClient::STATUT_ACTIF;
        }
        $compte = $this->etablissement->getMasterCompte();
        if($compte && !$this->etablissement->isSameCompteThanSociete()){
            $compte->setStatut($newStatus);
            $compte->save();
        }
        $this->etablissement->setStatut($newStatus);
        $this->etablissement->save();
        return $this->redirect('etablissement_visualisation', array('identifiant' => $this->etablissement->identifiant));
    }

    public function executeChaiModification(sfWebRequest $request) {
      $this->etablissement = $this->getRoute()->getEtablissement();
      $this->societe = $this->etablissement->getSociete();
      $this->num = $request->getParameter('num');
      $this->chai = $this->etablissement->getOrAdd('chais')->getOrAdd($this->num);
      $this->form = new EtablissementChaiModificationForm($this->chai);
      if ($request->isMethod(sfWebRequest::POST)) {
          $this->form->bind($request->getParameter($this->form->getName()));
          if ($this->form->isValid()) {
              $this->form->save();
              $this->redirect('etablissement_visualisation', array('identifiant' => $this->etablissement->identifiant));
          }
      }
    }

    public function executeChaiSuppression(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();

        foreach($this->etablissement->liaisons_operateurs as $liaison) {
            $etablissementDistant = EtablissementClient::getInstance()->find($liaison->id_etablissement, acCouchdbClient::HYDRATE_JSON);
            foreach($etablissementDistant->liaisons_operateurs as $liaisonDistante) {
                if($liaisonDistante->id_etablissement != $this->etablissement->_id || !$liaisonDistante->hash_chai) {
                    continue;
                }

                $this->getUser()->setFlash('error', "Il n'est pas possible de supprimer de chai pour cette établissement car ils sont utilisés dans des relations");
                return $this->redirect('etablissement_edition_chai', array('identifiant' => $this->etablissement->identifiant, 'num' => $request->getParameter('num')));
            }

        }

        $this->etablissement->chais->remove($request->getParameter('num'));
        $this->etablissement->save();
        $this->redirect('etablissement_visualisation', array('identifiant' => $this->etablissement->identifiant));
    }

    public function executeChaiAjout(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->chai = $this->etablissement->getOrAdd('chais')->add();
        $this->num = count($this->etablissement->chais) -1;
        $this->societe = $this->etablissement->getSociete();
        $this->form = new EtablissementChaiModificationForm($this->chai);
        $this->setTemplate('chaiModification');
    }

    public function executeRelationAjout(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();

        $this->form = new EtablissementRelationForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {

                return $this->redirect('etablissement_ajout_relation_chai', array('identifiant' => $this->etablissement->identifiant, 'id_etablissement' => $this->form->getValue('id_etablissement'), 'type_liaison' => $this->form->getValue('type_liaison')));
            }
        }
    }

    public function executeRelationAjoutChai(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();
        $this->typeLiaison = $request->getParameter('type_liaison');
        $this->etablissementRelation = EtablissementClient::getInstance()->find($request->getParameter('id_etablissement'));
        $this->etablissementChai = (EtablissementClient::isTypeLiaisonCanHaveChai($this->typeLiaison)) ? $this->etablissementRelation : $this->etablissement;

        if(!$this->etablissementRelation) {

            return $this->forward404();
        }
        $this->form = new EtablissementRelationChaiForm(
            $this->etablissement,
            $this->typeLiaison,
            $this->etablissementRelation,
            $this->etablissementChai
        );

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('etablissement_visualisation', array('identifiant' => $this->etablissement->identifiant));
            }
        }
    }

    public function executeRelationSuppression(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();

        $this->etablissement->removeLiaison($request->getParameter('key'), true);
        $this->etablissement->save();

        $this->redirect('etablissement_visualisation', array('identifiant' => $this->etablissement->identifiant));
    }

    protected function forwardSecure() {
        $this->context->getController()->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));

        $this->getResponse()->setStatusCode('403');

        throw new sfStopException();
    }

}

<?php

class annuaireActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->cleanSessions();
        $this->identifiant = $request->getParameter('identifiant');
        $this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        $this->annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($this->identifiant);
    }

    public function executeSelectionner(sfWebRequest $request) {
        $this->type = $request->getParameter('type');
        $this->identifiant = $request->getParameter('identifiant');
        $this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        $this->annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($this->identifiant);
        $this->form = new AnnuaireAjoutForm($this->annuaire);
        $this->form->setDefault('type', $this->type);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                return $this->redirect('annuaire_ajouter', array('type' => $values['type'], 'identifiant' => $this->identifiant, 'tiers' => $values['tiers']));
            }
        }
    }

    public function executeAjouter(sfWebRequest $request) {
        $this->type = $request->getParameter('type');
        $this->identifiant = $request->getParameter('identifiant');
        $this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        $this->societeId = $request->getParameter('tiers');
        $this->societeChoice = false;
        if ($this->type && $this->societeId) {
            $this->annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($request->getParameter('identifiant'));

            $this->societeObject = AnnuaireClient::getInstance()->findSocieteByTypeAndTiers($this->type, $this->societeId);
            $this->etablissements = $this->societeObject->getEtablissementsObj();

            $this->form = new AnnuaireAjoutForm($this->annuaire, $this->type, $this->etablissements);
            $this->form->setDefault('type', $this->type);
            $this->form->setDefault('tiers', $this->societeId);
            if (!$this->form->hasSocieteChoice()) {
                $this->etbObject = array_pop($this->etablissements)->etablissement;
            }
        }
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $this->etbToAdd = ($this->etbToAdd) ? $this->etbToAdd : AnnuaireClient::getInstance()->findTiersByTypeAndTiers($values['type'], $values['etablissementChoice']);
                $this->form->save();
                if ($vrac = $this->getUser()->getAttribute('vrac_object')) {
                    $vrac = unserialize($vrac);
                    $acteur = $this->getUser()->getAttribute('vrac_acteur');
                    $vrac->{$acteur . '_identifiant'} = $etablissement->_id;
                    $this->getUser()->setAttribute('vrac_object', serialize($vrac));
                    $this->getUser()->setAttribute('vrac_acteur', null);
                    if ($vrac->isNew()) {
                        return $this->redirect('vrac_nouveau', array('etablissement' => $this->identifiant));
                    } else {
                        return $this->redirect('vrac_soussigne', array('numero_contrat' => $vrac->numero_contrat));
                    }
                }
                return $this->redirect('annuaire', array('identifiant' => $this->identifiant));
            }
            return $this->redirect('annuaire_ajouter', array('type' => $values['type'], 'identifiant' => $this->identifiant, 'tiers' => $this->societeId));
        }
    }

    public function executeAjouterCommercial(sfWebRequest $request) {
        $this->identifiant = $request->getParameter('identifiant');
        $this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        $this->annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($request->getParameter('identifiant'));
        $this->form = new AnnuaireAjoutCommercialForm($this->annuaire);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $values = $this->form->getValues();
                if ($vrac = $this->getUser()->getAttribute('vrac_object')) {
                    $vrac = unserialize($vrac);
                    $vrac->storeInterlocuteurCommercialInformations($values['identite'], $value['contact']);
                    $this->getUser()->setAttribute('vrac_object', serialize($vrac));
                    if ($vrac->isNew()) {
                        return $this->redirect('vrac_nouveau', array('etablissement' => $this->etablissement->_id));
                    } else {
                        return $this->redirect('vrac_soussigne', array('numero_contrat' => $vrac->numero_contrat));
                    }
                }
                return $this->redirect('annuaire', array('identifiant' => $this->identifiant));
            }
        }
    }

    public function executeRetour(sfWebRequest $request) {
        $this->identifiant = $request->getParameter('identifiant');
        if ($vracId = $this->getUser()->getAttribute('vrac_id')) {
            return $this->redirect('vrac_etape', array('numero_contrat' => $vracId, 'etape' => $etapes->getFirst()));
        }
        return $this->redirect('annuaire', array('identifiant' => $this->identifiant));
    }

    public function executeSupprimer(sfWebRequest $request) {
        $this->cleanSessions();
        $type = $request->getParameter('type');
        $id = $request->getParameter('id');
        $identifiant = $request->getParameter('identifiant');
        $annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($identifiant);
        $etablissement = EtablissementClient::getInstance()->find($identifiant);
        $societe = SocieteClient::getInstance()->find($etablissement->id_societe);
        if ($type !== null && $id !== null) {
            if ($annuaire && $annuaire->exist($type)) {
                if ($annuaire->get($type)->exist($id)) {
                    $annuaire->get($type)->remove($id);
                    $annuaire->save();
                    return $this->redirect('annuaire', array('identifiant' => $identifiant));
                }
            }
        }
        throw new sfError404Exception('La paire "' . $type . '"/"' . $id . '" n\'existe pas dans l\'annuaire');
    }

    public function cleanSessions() {
        $this->getUser()->setAttribute('vrac_object', null);
        $this->getUser()->setAttribute('vrac_acteur', null);
    }

}

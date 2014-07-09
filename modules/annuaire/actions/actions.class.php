<?php
class annuaireActions extends sfActions {

	public function executeIndex(sfWebRequest $request) 
	{
		$this->identifiant = $request->getParameter('identifiant');
		$this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
		$this->annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($this->identifiant);
    }

	public function executeSelectionner(sfWebRequest $request) 
	{
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

	public function executeAjouter(sfWebRequest $request) 
	{
		$this->type = $request->getParameter('type');
		$this->identifiant = $request->getParameter('identifiant');
		$this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
		$this->tiers = $request->getParameter('tiers');
		if ($this->type && $this->tiers) {
			$this->annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($request->getParameter('identifiant'));
			$this->form = new AnnuaireAjoutForm($this->annuaire);
			$this->form->setDefault('type', $this->type);
			$this->form->setDefault('tiers', $this->tiers);
			$this->tiersObject = AnnuaireClient::getInstance()->findTiersByTypeAndTiers($this->type, $this->tiers);
		}
        if ($request->isMethod(sfWebRequest::POST)) {
        	$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
        		$values = $this->form->getValues();
        		$etablissement = AnnuaireClient::getInstance()->findTiersByTypeAndTiers($values['type'], $values['tiers']);
        		if ($this->tiersObject->_id == $etablissement->_id) {
       				$this->form->save();
       				/*if ($vrac = $this->getUser()->getAttribute('vrac_object')) {
       					$vrac = unserialize($vrac);
       					$acteur = $this->getUser()->getAttribute('vrac_acteur');
       					$vrac->addActeur($acteur, $this->tiers);
       					$vrac->addType($acteur, $values['type']);
       					$this->getUser()->setAttribute('vrac_object', serialize($vrac));
       					$this->getUser()->setAttribute('vrac_acteur', null);
       					$etapes = VracEtapes::getInstance();
       					return $this->redirect('vrac_etape', array('numero_contrat' => !$vrac->isNew() ? $vrac->numero_contrat : VracRoute::NOUVEAU, 'etape' => $etapes->getFirst()));
       				}*/
       				return $this->redirect('annuaire', array('identifiant' => $this->identifiant));
        		}
        		return $this->redirect('annuaire_ajouter', array('type' => $values['type'], 'identifiant' => $this->identifiant, 'tiers' => $values['tiers']));
        	}
        }
    }

	public function executeAjouterCommercial(sfWebRequest $request) 
	{
		$this->identifiant = $request->getParameter('identifiant');
		$this->etablissement = EtablissementClient::getInstance()->find($this->identifiant);
		$this->annuaire = AnnuaireClient::getInstance()->findOrCreateAnnuaire($request->getParameter('identifiant'));
		$this->form = new AnnuaireAjoutCommercialForm($this->annuaire);
        if ($request->isMethod(sfWebRequest::POST)) {
        	$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
       			$this->form->save();
       			/*if ($vrac = $this->getUser()->getAttribute('vrac_object')) {
       				$vrac = unserialize($vrac);
              		$vrac->storeInterlocuteurCommercialInformations($values['identite'], $value['contact']);
       				$this->getUser()->setAttribute('vrac_object', serialize($vrac));
       				$etapes = VracEtapes::getInstance();
       				return $this->redirect('vrac_etape', array('numero_contrat' => !$vrac->isNew() ? $vrac->numero_contrat : VracRoute::NOUVEAU, 'etape' => $etapes->getFirst()));
       			}*/
       			return $this->redirect('annuaire', array('identifiant' => $this->identifiant));
        	}
        }
    }
    
    public function executeRetour(sfWebRequest $request)
    {
    	$this->identifiant = $request->getParameter('identifiant');
    	if ($vracId = $this->getUser()->getAttribute('vrac_id')) {
    		return $this->redirect('vrac_etape', array('numero_contrat' => $vracId, 'etape' => $etapes->getFirst()));
    	}
    	return $this->redirect('annuaire', array('identifiant' => $this->identifiant));
    }

	public function executeSupprimer(sfWebRequest $request) 
	{
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
		throw new sfError404Exception('La paire "'.$type.'"/"'.$id.'" n\'existe pas dans l\'annuaire');
    }
}

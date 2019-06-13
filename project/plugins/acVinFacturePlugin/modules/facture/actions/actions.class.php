<?php
class factureActions extends factureGeneriqueActions {

  public function executeIndex(sfWebRequest $request) {
      $this->redirect403IfIsTeledeclaration();
      $this->form = new FactureSocieteChoiceForm('INTERPRO-inter-loire');
      $this->generationForm = new FactureGenerationMasseForm();
      $this->generations = GenerationClient::getInstance()->findHistoryWithType(GenerationClient::TYPE_DOCUMENT_FACTURES,10);
       if ($request->isMethod(sfWebRequest::POST)) {
	 $this->form->bind($request->getParameter($this->form->getName()));
	 if ($this->form->isValid()) {
	   return $this->redirect('facture_societe', $this->form->getSociete());
	 }
       }
    }

   public function executeGeneration(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $this->generationForm = new FactureGenerationMasseForm();
        if ($request->isMethod(sfWebRequest::POST)) {
	          $this->generationForm->bind($request->getParameter($this->generationForm->getName()));
            $values = $this->generationForm->getValues();
            if ($this->generationForm->isValid()) {
	              $generation = new Generation();

                $date_facturation = DATE::getIsoDateFromFrenchDate($values['date_facturation']);
                $date_mouvement = DATE::getIsoDateFromFrenchDate($values['date_mouvement']);
                $message_communication = $values['message_communication'];

                $generation->arguments->add('regions', implode(',', array_values($values['regions'])));
                if($values['type_document'] != FactureGenerationMasseForm::TYPE_DOCUMENT_TOUS) {
                    $generation->arguments->add('type_document', $values['type_document']);
                }
	              $generation->arguments->add('date_facturation', $date_facturation);
	              $generation->arguments->add('date_mouvement', $date_mouvement);
	              $generation->arguments->add('seuil', $values['seuil']);
                if($message_communication) {
                    $generation->arguments->add('message_communication', $message_communication);
                }
	              $generation->type_document = GenerationClient::TYPE_DOCUMENT_FACTURES;
                $generation->save();
            }
       }

       return $this->redirect('generation_view', array('type_document' => $generation->type_document,'date_emission' => $generation->date_emission));
    }

   public function executeEtablissement(sfWebRequest $request) {
     $this->redirect403IfIsTeledeclaration();
     return $this->redirect('facture_societe', $this->getRoute()->getEtablissement()->getSociete());
   }

    public function executeMonEspace(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $this->form = new FactureGenerationForm();
        $this->societe = $this->getRoute()->getSociete();
        $this->identifiant = $request->getParameter('identifiant');
        $this->isTeledeclarationMode = $this->isTeledeclarationFacture();

        $campagne = $this->getCampagne($request, 'facture_societe');

        $this->factures = FactureEtablissementView::getInstance()->findBySociete($this->societe, $campagne);
        $this->mouvements = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($this->societe);
    }

    public function executeDefacturer(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $this->facture = $this->getRoute()->getFacture();
        if(!$this->facture->hasAvoir()){
            $this->avoir = FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($this->facture);
        }
        $this->redirect('facture_societe', array('identifiant' => $this->facture->identifiant));
    }


    public function executeGenerer(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $parameters = $request->getParameter('facture_generation');
        $date_facturation = (!isset($parameters['date_facturation']))? null : DATE::getIsoDateFromFrenchDate($parameters['date_facturation']);
        $message_communication = (!isset($parameters['message_communication']))? null : $parameters['message_communication'];
        $parameters['date_mouvement'] = (isset($parameters['date_mouvement']) && $parameters['date_mouvement']!='')?  $parameters['date_mouvement'] : $date_facturation;
        if(!isset($parameters['type_document']) || !$parameters['type_document'] || $parameters['type_document'] == FactureGenerationMasseForm::TYPE_DOCUMENT_TOUS) {
          unset($parameters['type_document']);
        }

        $this->societe = $this->getRoute()->getSociete();

        $mouvementsBySoc = array($this->societe->identifiant => FactureClient::getInstance()->getFacturationForSociete($this->societe));
        $mouvementsBySoc = FactureClient::getInstance()->filterWithParameters($mouvementsBySoc,$parameters);
        if($mouvementsBySoc)
        {
            $generation = FactureClient::getInstance()->createFacturesBySoc($mouvementsBySoc,$date_facturation, $message_communication);
            $generation->save();
        }
        $this->redirect('facture_societe', $this->societe);
    }

    public function executeConnexion(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $societe = $this->getRoute()->getSociete();
        $this->getUser()->usurpationOn($societe->identifiant, $request->getReferer());

        $this->initSocieteAndEtablissementPrincipal();
        $this->redirect('facture_teledeclarant',array('identifiant' => $this->etablissementPrincipal->identifiant));
    }

    public function executeSociete(sfWebRequest $request) {
        $this->redirect403IfIsNotTeledeclaration();
        $this->identifiant = $request['identifiant'];
        $this->initSocieteAndEtablissementPrincipal();
        $this->redirect403IfIsNotTeledeclarationAndNotMe();
        $this->hasTeledeclarationPrevelement = $this->isTeledeclarationPrelevement();
        if(!$this->hasTeledeclarationPrevelement){
          $this->adhesionPrelevementForm = new FactureAdhesionPrelevementForm();
          if ($request->isMethod(sfRequest::POST) && $request->getParameter($this->adhesionPrelevementForm->getName(),null)) {
              $this->adhesionPrelevementForm->bind($request->getParameter($this->adhesionPrelevementForm->getName()));
              if ($this->adhesionPrelevementForm->isValid()) {
                  $this->redirect('facture_sepa',array('identifiant' => $this->etablissementPrincipal->identifiant));
              }
          }
        }

        $campagne = $this->getCampagne($request, 'facture_teledeclarant');

        $this->factures = FactureEtablissementView::getInstance()->findBySociete($this->societe, $campagne);
    }

    public function executeSepa(sfWebRequest $request) {
        $this->redirect403IfIsNotTeledeclaration();
        $this->identifiant = $request['identifiant'];
        $this->initSocieteAndEtablissementPrincipal();
        $this->redirect403IfIsNotTeledeclarationAndNotMe();
        $this->form = new FactureEditionSepaForm($this->societe);
        if ($request->isMethod(sfRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $diff = $this->form->getDiff();
                $this->form->save();
                $latex = new FactureSepaLatex($this->societe);
              //  echo $latex->getLatexFileContents(); exit;
                $latex->echoWithHTTPHeader($request->getParameter('type'));
                exit;
                $this->redirect('facture_teledeclarant',array('identifiant' => $this->etablissementPrincipal->identifiant));
            }
        }
    }




    public function executeRedirect(sfWebRequest $request) {
      $this->redirect403IfIsTeledeclaration();
      $iddoc = $request->getParameter('iddocument');
      if (preg_match('/^DRM/', $iddoc)) {
	$drm = DRMClient::getInstance()->find($iddoc);
	return $this->redirect('drm_visualisation', $drm);
      }else if (preg_match('/^SV12/', $iddoc)) {
	$sv12 = SV12Client::getInstance()->find($iddoc);
	return $this->redirect('sv12_visualisation', $sv12);
      }
      return $this->forward404();
    }

    public function executeLatex(sfWebRequest $request) {
        $this->isTeledeclarationMode = $this->isTeledeclarationFacture();
        $this->facture = FactureClient::getInstance()->find($request->getParameter('identifiant'));
        $this->identifiant = $this->facture->getSociete()->getEtablissementPrincipal()->identifiant;
        if($this->isTeledeclarationMode){
          $this->redirect403IfIsNotTeledeclarationAndNotMe();
        }
        $this->setLayout(false);
        $this->forward404Unless($this->facture);
      	$latex = new FactureLatex($this->facture);
      	$latex->echoWithHTTPHeader($request->getParameter('type'));
        exit;
    }

    private function getLatexTmpPath() {
        return "/tmp/";
    }

    /**
     * Récupère la campagne en URL
     * Si elle n'est pas renseignée, on prends la campagne courante
     *
     * On génère un formulaire de choix de campagne
     *
     * On filtre les factures sur la campagne choisie
     *
     * @param sfWebRequest $request La requête
     * @param string $url L'url de retour si on change de campagne
     * @return La campagne
     */
    private function getCampagne(sfWebRequest $request, $url)
    {
        $campagne = $request->getParameter('campagne');
        if ($campagne === '') {
            $campagne = date('Y');
        }

        $this->campagneForm = new FactureChoixCampagneForm($campagne);

        if ($request->isMethod(sfWebRequest::POST)) {
            $param = $request->getParameter($this->campagneForm->getName());
            if ($param) {
                $this->campagneForm->bind($param);
                return $this->redirect($url, ['identifiant' => $this->identifiant, 'campagne' => $this->campagneForm->getValue('campagne')]);
            }
        }

        return $campagne;
    }
}

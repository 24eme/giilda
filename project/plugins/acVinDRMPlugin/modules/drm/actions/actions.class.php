<?php

/**
 * drm actions.
 *
 * @package    vinsi
 * @subpackage drm
 * @author     Mathurin
 */
class drmActions extends drmGeneriqueActions {

    public function executeConnexion(sfWebRequest $request) {

        //  $this->redirect403IfIsTeledeclaration();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $societe = $this->etablissement->getSociete();

        $this->getUser()->usurpationOn($societe->getMasterCompte()->login, $request->getReferer());
        $this->redirect('drm_societe', array('identifiant' => $societe->identifiant));
    }

    public function executeRedirect(sfWebRequest $request) {
        $drm = DRMClient::getInstance()->find($request->getParameter('identifiant_drm'));
        $this->forward404Unless($drm);
        return $this->redirect('drm_visualisation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
    }

    public function executeIndex(sfWebRequest $request) {
      $this->redirect403IfIsTeledeclaration();
      
      $res_by_page = 10;        
      $this->page_num = $request->getParameter('page', 1);

      $this->current_page = $this->page_num;
      $from = $res_by_page * ($this->page_num  - 1);
      $this->drm_controles = [];
      $this->nb_results = 0;

      try {
          $index = acElasticaManager::getType('DRM');
      } catch(Exception $e) {
          return;
      }

      $query = new acElasticaQuery();
      $elasticaQueryString = new acElasticaQueryQueryString();
      $elasticaQueryString->setQuery("_exists_:doc.controles");
      $query->setQuery($elasticaQueryString);
      $query->setSort([["doc.date_modification" => ["order"=>"desc"]]]);
      $query->setFrom($from);
      $query->setLimit($res_by_page);
      $this->drm_controles = $index->search($query);

      $this->nb_results = $this->drm_controles->getTotalHits();       
      $this->last_page = ceil($this->nb_results / $res_by_page);        
      
      if($this->page_num > $this->last_page){
        return $this->forward404Unless(true);
      }
    }

    public function executeEtablissementSelection(sfWebRequest $request) {
        //$this->redirect403IfIsTeledeclaration();

        $form = new DRMEtablissementChoiceForm('INTERPRO-declaration');
        $form->bind($request->getParameter($form->getName()));
        if (!$form->isValid()) {

            return $this->redirect('drm');
        }

        return $this->redirect('drm_etablissement', $form->getEtablissement());
    }

    public function executeRedirectEtape(sfWebRequest $request) {
        $isTeledeclarationMode = $this->isTeledeclarationDrm();
        $drm = $this->getRoute()->getDRM();

        switch ($drm->etape) {
            case DRMClient::ETAPE_CHOIX_PRODUITS:
                if ($isTeledeclarationMode) {
                    return $this->redirect('drm_choix_produit', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
                }
                return $this->redirect('drm_edition', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));

            case DRMClient::ETAPE_SAISIE:
                return $this->redirect('drm_edition', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));

            case DRMClient::ETAPE_CRD:
                if ($isTeledeclarationMode) {
                    return $this->redirect('drm_crd', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
                }
                return $this->redirect('drm_validation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));

            case DRMClient::ETAPE_ADMINISTRATION:
                if ($isTeledeclarationMode) {
                    return $this->redirect('drm_annexes', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
                }
                return $this->redirect('drm_validation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));

            case DRMClient::ETAPE_VALIDATION:
            case DRMClient::ETAPE_VALIDATION_EDI:
                return $this->redirect('drm_validation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
        }

        if ((!$drm->etape) && !$drm->isValidee()) {
            return $this->redirect('drm_edition', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
        }

        return $this->redirect('drm_visualisation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeChoixCreation(sfWebRequest $request) {
        $isTeledeclarationMode = $this->isTeledeclarationDrm();
        if ($request->isMethod(sfWebRequest::POST)) {
            if (!$request->getParameter('drmChoixCreation')) {
                throw new sfException("Le formulaire n'est pas valide");
            }
            $drmChoixCreation = $request->getParameter('drmChoixCreation');
            $choixCreation = $drmChoixCreation['type_creation'];
            $identifiant = $request->getParameter('identifiant');
            $periode = $request->getParameter('periode');
            $this->creationDrmForm = new DRMChoixCreationForm(array(), array('identifiant' => $identifiant, 'periode' => $periode));
            $this->creationDrmForm->bind($request->getParameter($this->creationDrmForm->getName()), $request->getFiles($this->creationDrmForm->getName()));

            switch ($choixCreation) {
                case DRMClient::DRM_CREATION_DOCUMENTS :
                  if(!DRMConfiguration::getInstance()->getRepriseDonneesUrl()){
                    throw new sfException("Ce choix n'est pas possible : il n'y a aucune url spécifié pour la reprise");
                  }

                  // Récupère la dernière DRM de la campagne pour regarder si elle a des colonnes Total Alsace banc et Alsace Lieu, si c'est le cas on demande les données des contrats, ds, récolte aggrégées
                  $drmLast = DRMClient::getInstance()->findLastByIdentifiant($identifiant);
                  if ($drmLast !== null) {
                      $produitsTotaux = null;
                      $withDenomination = array();
                      foreach($drmLast->getProduitsDetails() as $detail) {
                          if(preg_match("/^Total/", $detail->getLibelle())) {
                              if($produitsTotaux) {
                                  $produitsTotaux .= '|';
                              }
                              $produitsTotaux .= $detail->getCouleur()->getLieu()->getConfig()->getHash();
                          }
                          if($detail->denomination_complementaire) {
                              $withDenomination[$detail->getCouleur()->getLieu()->getAppellation()->getHash()] = $detail->getCouleur()->getLieu()->getAppellation()->getHash();
                          }
                      }
                  }
                  $options = [];
                  if(isset($produitsTotaux) && $produitsTotaux) {
                      $options['aggregate'] = $produitsTotaux;
                  }

                  if(isset($withDenomination) && $withDenomination) {
                      $options['lieudit'] = implode("|", $withDenomination);
                  }

                  if(!DRMClient::getInstance()->findLastByIdentifiant($identifiant, acCouchdbClient::HYDRATE_JSON)) {
                      $options['firstdrm'] = 1;
                  }
                  $url_reprise_donnees_drm = DRMConfiguration::getInstance()->getFinalRepriseDonneesUrl($identifiant, $periode, $options);

                  $discr = date('YmdHis').'_'.uniqid();
                  $md5file = md5($discr);
                  $filename = 'import_'.$identifiant . '_' . $periode.'_'.$md5file.'.csv';
                  $path = sfConfig::get('sf_data_dir') . '/upload/'.$filename;
                  if ($stream = fopen($url_reprise_donnees_drm, 'r')) {
                          // affiche toute la page, en commençant à la position 10
                          $resultFile = file_put_contents($path, stream_get_contents($stream));
                          fclose($stream);
                      }
                  if(!$resultFile && !file_exists ($path)){
                    throw new sfException("Enregistrement du fichier EDI échoué : consulter l'url ".$url_reprise_donnees_drm);
                  }
                  if(!$resultFile && file_exists($path)){
                    return $this->redirect('drm_nouvelle', array('identifiant' => $identifiant, 'periode' => $periode));
                  }
                  return $this->redirect('drm_creation_fichier_edi',array('identifiant' => $identifiant,'periode' => $periode,'md5' => $md5file,'etape' => DRMClient::ETAPE_CHOIX_PRODUITS, 'type_creation' => DRMClient::DRM_CREATION_AUTO, 'complement' => true));
                break;
                case DRMClient::DRM_CREATION_EDI :
                    if ($this->creationDrmForm->isValid()) {
                      $drmfile = $this->creationDrmForm->getValue('file');
                      if (!$drmfile) {
                        return $this->redirect('drm_verification_fichier_edi', array('identifiant' => $identifiant, 'periode' => $periode, 'md5' => "error"));
                      }
                      $md5 = $drmfile->getMd5();
                      $fileName = 'import_'.$identifiant . '_' . $periode.'_'.$md5.'.csv';
                      rename(sfConfig::get('sf_data_dir') . '/upload/'.$md5,  sfConfig::get('sf_data_dir') . '/upload/'.$fileName);
                      return $this->redirect('drm_verification_fichier_edi', array('identifiant' => $identifiant, 'periode' => $periode, 'md5' => $md5));
                    }
                    return $this->redirect('drm_verification_fichier_edi', array('identifiant' => $identifiant, 'periode' => $periode, 'md5' => "error"));

                case DRMClient::DRM_CREATION_VIERGE :
                    return $this->redirect('drm_nouvelle', array('identifiant' => $identifiant, 'periode' => $periode));

                case DRMClient::DRM_CREATION_NEANT :
                    $drm = DRMClient::getInstance()->createDoc($identifiant, $periode, $isTeledeclarationMode);
                    $drm->etape = DRMClient::ETAPE_VALIDATION;
                    $drm->type_creation = DRMClient::DRM_CREATION_NEANT;
                    $drm->update();
                    $drm->save();
                    return $this->redirect('drm_validation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));

            }
        }
        return $this->redirect('drm_societe', array('identifiant' => $identifiant));
    }

    public function executeCsv(sfWebRequest $request) {
        $identifiant = $request->getParameter('identifiant');
        $periode = $request->getParameter('periode');

        $csv = CSVDRMClient::getInstance()->findFromIdentifiantPeriode($identifiant, $periode);

        $filename = 'import_edi_'.$identifiant.'_'.$periode.'.csv';

        $this->response->setContent(file_get_contents($csv->getAttachmentUri($filename)));
        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition', "attachment; filename=" . $filename);

        return sfView::NONE;
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeVerificationEdi(sfWebRequest $request) {
        ini_set('memory_limit', '600M');
        set_time_limit(0);
        $this->md5 = $request->getParameter('md5');
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = $request->getParameter('periode');

        $this->drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->periode);

        if(!$this->drm) {
          $this->drm = DRMClient::getInstance()->createDoc($this->identifiant, $this->periode, true);
          $this->drm->constructId();
        }

        if ($this->md5 == 'error') {
            $this->erreurs = array( (object) array("diagnostic" => "Mauvais format de fichier EDI", "num_ligne" => "", "csv_erreur" => ""));

            return sfView::SUCCESS;
        }

          $fileName = 'import_'.$this->drm->identifiant . '_' . $this->drm->periode.'_'.$this->md5.'.csv';

          try {
              if (!$request->getParameter('nocheck')) {
                $drmCsvEdi = new DRMImportCsvEdi(sfConfig::get('sf_data_dir') . '/upload/' . $fileName, $this->drm);
                $drmCsvEdi->checkCSV();
                $this->csvDoc = $drmCsvEdi->getCsvDoc();
              }else{
                $this->csvDoc = CSVDRMClient::getInstance()->findFromIdentifiantPeriode($this->identifiant, $this->periode);
              }

              $this->erreurs = $this->csvDoc->erreurs;
          }catch(sfException $e) {
              $this->erreurs = array( (object) array("diagnostic" => preg_replace('/;.*/', '', $e->getMessage()), "num_ligne" => "", "csv_erreur" => ""));
          }

          if(!$this->drm->isNew()) {
            return sfView::SUCCESS;
          }

          if ($request->getParameter('nocreate')) {
               return sfView::SUCCESS;
          }

        if (!count($this->erreurs)) {
          return $this->redirect('drm_creation_fichier_edi', array('periode' => $this->periode, 'md5' => $this->md5,'identifiant' => $this->identifiant));
        }
    }

        /**
     *
     * @param sfWebRequest $request
     */
    public function executeCreationEdi(sfWebRequest $request) {
        set_time_limit(0);
        ini_set('memory_limit', '600M');
        $this->md5 = $request->getParameter('md5');
        $this->identifiant = $request->getParameter('identifiant');
        $this->periode = $request->getParameter('periode');

        $this->drm = DRMClient::getInstance()->createDoc($this->identifiant, $this->periode, true);
		$this->drm->constructId();
        $fileName = 'import_'.$this->drm->identifiant . '_' . $this->drm->periode.'_'.$this->md5.'.csv';
        $path = sfConfig::get('sf_data_dir') . '/upload/' . $fileName;
        $this->drmCsvEdi = new DRMImportCsvEdi(sfConfig::get('sf_data_dir') . '/upload/' . $fileName, $this->drm, false, $request->getParameter('complement', false));
        $this->drmCsvEdi->importCSV();

        $this->drm->etape = $request->getParameter('etape', DRMClient::ETAPE_VALIDATION);
        $this->drm->type_creation = $request->getParameter('type_creation', DRMClient::DRM_CREATION_EDI);
        $this->drm->save();

        $this->redirect('drm_redirect_etape', $this->drm);

    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeExportEdi(sfWebRequest $request) {
        $this->setLayout(false);
        $drm = $this->getRoute()->getDRM();

        $this->drmCsvEdi = new DRMExportCsvEdi($drm);

        $filename = $drm->identifiant . '_' . $drm->periode.'_'.$drm->_rev.'.csv';


        $attachement = "attachment; filename=" . $filename . ".csv";

        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition', $attachement);

    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeNouvelle(sfWebRequest $request) {
        $isTeledeclarationMode = $this->isTeledeclarationDrm();
        $identifiant = $request->getParameter('identifiant');
        $periode = $request->getParameter('periode');

        $drm = DRMClient::getInstance()->createDoc($identifiant, $periode, $isTeledeclarationMode);
        //$drm->loadAllProduits();
        $drm->save();
        if ($isTeledeclarationMode) {
            $this->redirect('drm_choix_produit', $drm);
        } else {
            $this->redirect($this->generateUrl('drm_edition', $drm).'#col_saisies_cont');
        }
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeInProcess(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->campagne = $request->getParameter('campagne');
        $this->historique = new DRMHistorique($request->getParameter('identifiant'), $this->campagne);
        if (!$this->campagne) {
            $this->campagne = '2012-2013';
        }
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeDelete(sfWebRequest $request) {
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->drm = $this->getRoute()->getDRM();
        $identifiant = $this->drm->getidentifiant();
        $this->initDeleteForm();
        if ($request->isMethod(sfRequest::POST)) {
            $this->deleteForm->bind($request->getParameter($this->deleteForm->getName()));
            if ($this->deleteForm->isValid()) {
                $this->drm->delete();
                $url = $this->generateUrl('drm_etablissement', array('identifiant' => $identifiant, 'campagne' => -1));
                $this->redirect($url);
            }
        }
    }

    private function formCampagne(sfWebRequest $request, $route) {
        //$this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->societe = $this->etablissement->getSociete();

        $this->campagne = $request->getParameter('campagne');
        if (!$this->campagne) {
            $this->campagne = -1;
        }

        $this->formCampagne = new DRMEtablissementCampagneForm($this->etablissement->identifiant, $this->campagne, $this->isTeledeclarationMode);
        if ($request->isMethod(sfWebRequest::POST)) {
            $param = $request->getParameter($this->formCampagne->getName());
            if ($param) {
                $this->formCampagne->bind($param);
                $campagne = ($this->formCampagne->getValue('campagne'))? $this->formCampagne->getValue('campagne') : "-1";
                return $this->redirect($route, array('identifiant' => $this->etablissement->getIdentifiant(), 'campagne' => $campagne));
            }
        }
    }

    /**
     * Executes mon espace action
     *
     * @param sfRequest $request A request object
     */
    public function executeMonEspace(sfWebRequest $request) {
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $view = $this->formCampagne($request, 'drm_etablissement');
        $this->calendrier = new DRMCalendrier($this->etablissement, $this->campagne, $this->isTeledeclarationMode);
        return $view;
    }

    public function executeStocks(sfWebRequest $request) {
        $this->isTeledeclarationMode = $this->isTeledeclarationDrm();
        $this->campagne = ($request->getParameter('campagne'))? $request->getParameter('campagne') : ConfigurationClient::getInstance()->getCampagneVinicole()->getCurrent();
        $this->etablissement  = $this->getRoute()->getEtablissement();
        $this->calendrier = new DRMCalendrier($this->etablissement, $this->campagne, $this->isTeledeclarationMode);
        return $this->formCampagne($request, 'drm_etablissement_stocks');
    }

    /**
     * Executes historique action
     *
     * @param sfRequest $request A request object
     */
    public function executeHistorique(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->campagne = $request->getParameter('campagne');
    }

    /**
     * Executes informations action
     *
     * @param sfRequest $request A request object
     */
    public function executeInformations(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->tiers = $this->getUser()->getTiers();
        $isAdmin = $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN);
        $this->form = new DRMInformationsForm(array(), array('is_admin' => $isAdmin));

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                if ($values['confirmation'] == "modification") {
                    $this->redirect('drm_modif_infos', $this->drm);
                } elseif ($values['confirmation']) {
                    $this->drm->declarant->nom = $this->tiers->nom;
                    $this->drm->declarant->raison_sociale = $this->tiers->raison_sociale;
                    $this->drm->declarant->siret = $this->tiers->siret;
                    $this->drm->declarant->cni = $this->tiers->cni;
                    $this->drm->declarant->cvi = $this->tiers->cvi;
                    $this->drm->declarant->siege->adresse = $this->tiers->siege->adresse;
                    $this->drm->declarant->siege->code_postal = $this->tiers->siege->code_postal;
                    $this->drm->declarant->siege->commune = $this->tiers->siege->commune;
                    $this->drm->declarant->comptabilite->adresse = $this->tiers->comptabilite->adresse;
                    $this->drm->declarant->comptabilite->code_postal = $this->tiers->comptabilite->code_postal;
                    $this->drm->declarant->comptabilite->commune = $this->tiers->comptabilite->commune;
                    $this->drm->declarant->no_accises = $this->tiers->no_accises;
                    $this->drm->declarant->no_tva_intracommunautaire = $this->tiers->no_tva_intracommunautaire;
                    $this->drm->declarant->service_douane = $this->tiers->service_douane;
                    $this->drm->save();
                }
                $this->drm->setCurrentEtapeRouting('ajouts_liquidations');
                $this->redirect('drm_mouvements_generaux', $this->drm);
            }
        }
    }

    public function executeModificationInfos(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
    }

    public function executeDeclaratif(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->drm->setCurrentEtapeRouting('declaratif');
        $this->form = new DRMDeclaratifForm($this->drm);
        $this->hasFrequencePaiement = ($this->drm->declaratif->paiement->douane->frequence) ? true : false;
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->drm->setCurrentEtapeRouting('validation');
                $this->redirect('drm_validation', $this->drm);
            }
        }
    }

    public function executePaiementFrequenceFormAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $drm = $this->getRoute()->getDRM();
        return $this->renderText($this->getPartial('popupFrequence', array('drm' => $drm)));
    }

    public function executeReouvrir(sfWebRequest $request) {
        if(sfConfig::get('app_force_usurpation_mode')) {
            $this->redirect403Unless($this->getUser()->isUsurpationCompte());
        } else {
            $this->redirect403IfIsTeledeclaration();
        }
        $drm = $this->getRoute()->getDRM();
        $this->redirect403Unless($drm->isReouvrable());
        $drm->cleanControles();

        $drm = $this->getRoute()->getDRM();
        $drm->devalidate();
        $drm->save();

        return $this->redirect('drm_redirect_etape', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion()));
    }

    public function executeShowError(sfWebRequest $request) {
        $drm = $this->getRoute()->getDRM();
        $drmValidation = new DRMValidation($drm);
        $controle = $drmValidation->find($request->getParameter('type'), $request->getParameter('identifiant'));
        $this->forward404Unless($controle);
        $this->getUser()->setFlash('control_message', $controle->getMessage());
        $this->getUser()->setFlash('control_css', "flash_" . $controle->getType());
        $this->redirect($controle->getLien());
    }

    public function executeRectificative(sfWebRequest $request) {
        $drm = $this->getRoute()->getDRM();

        $drm_rectificative = $drm->generateRectificative();
        $drm_rectificative->save();

        return $this->redirect('drm_redirect_etape', array('identifiant' => $drm_rectificative->identifiant, 'periode_version' => $drm_rectificative->getPeriodeAndVersion()));
    }

    public function executeModificative(sfWebRequest $request) {
        $drm = $this->getRoute()->getDRM();

        $drm_rectificative = $drm->generateModificative();
        $drm_rectificative->save();

        return $this->redirect('drm_redirect_etape', array('identifiant' => $drm_rectificative->identifiant, 'periode_version' => $drm_rectificative->getPeriodeAndVersion()));
    }

    /**
     * Executes mouvements generaux action
     *
     * @param sfRequest $request A request object
     */
    public function executePdf(sfWebRequest $request) {

        ini_set('memory_limit', '512M');
        $this->drm = $this->getRoute()->getDRM();
        $pdf = new ExportDRMPdf($this->drm);

        return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
    }

    public function executeSociete(sfWebRequest $request) {

        $this->identifiant = $request['identifiant'];

        $this->initSocieteAndEtablissementPrincipal();

        $this->redirect403IfIsNotTeledeclarationAndNotMe();

        $this->redirect('drm_etablissement', $this->etablissementPrincipal);
    }

    public function executeConventionCielPdf(sfWebRequest $request) {

		$conventionCielPdf = $this->generateConventionCielPdf($this->getRoute()->getEtablissement());

    	$response = $this->getResponse();
    	$response->setHttpHeader('Content-Type', 'application/pdf');
    	$response->setHttpHeader('Content-disposition', 'attachment; filename="' . basename($conventionCielPdf) . '"');
    	$response->setHttpHeader('Content-Length', filesize($conventionCielPdf));
    	$response->setHttpHeader('Pragma', '');
    	$response->setHttpHeader('Cache-Control', 'public');
    	$response->setHttpHeader('Expires', '0');

    	return $this->renderText(file_get_contents($conventionCielPdf));
    }

    protected function generateConventionCielPdf($etablissement) {

    	$path = sfConfig::get('sf_data_dir').'/convention';
    	$filename = 'convention_ciel_'.$etablissement->identifiant.'_'.$etablissement->_rev.'.pdf';

    	if (!file_exists($path.'/pdf/'.$filename)) {
    		$template = 'template_convention_'.sfConfig::get('sf_app').'.pdf';
    		if (!file_exists($path.'/'.$template)) {
    			throw new sfException("Le template de convention ciel ".$path."/".$template." n'existe pas.");
    		}
    		$fdf = tempnam(sys_get_temp_dir(), 'CONVENTIONCIEL');
    		file_put_contents($fdf, sfOutputEscaper::unescape(utf8_decode($this->getPartial('common/fdfConvention', array('etablissement' => $etablissement)))));
    		exec('pdftk '.$path.'/'.$template.' fill_form '.$fdf.' output  /dev/stdout flatten |  gs -o '.$path.'/pdf/'.$filename.' -sDEVICE=pdfwrite -dEmbedAllFonts=true -sFONTPATH=/usr/share/fonts/truetype/freefont - ');
    		unlink($fdf);
    		if (!file_exists($path.'/pdf/'.$filename)) {
    			throw new sfException("Le pdf ".$filename." n'a pas pu être généré.");
    		}
    	}

    	return $path.'/pdf/'.$filename;
    }

}

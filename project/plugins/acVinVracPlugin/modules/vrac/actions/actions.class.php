<?php

/**
 * vrac actions.
 *
 * @package    vinsdeloire
 * @subpackage vrac
 * @author     Mathurin Petit
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vracActions extends sfActions {

    public function executeRedirect(sfWebRequest $request) {
        $vrac = VracClient::getInstance()->find($request->getParameter('identifiant_vrac'));
        $this->forward404Unless($vrac);
        return $this->redirect('vrac_visualisation', array('numero_contrat' => $vrac->numero_contrat));
    }

    public function executeIndex(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $this->vracs = VracClient::getInstance()->retrieveLastDocs(10);
        $this->etiquettesForm = new VracEtiquettesForm();
        $this->postFormEtablissement($request);
    }

    public function executeCreation(sfWebRequest $request) {
        $this->redirect403IfICanNotCreate();
        $this->identifiant = str_replace('ETABLISSEMENT-', '', $request->getParameter('identifiant'));
        $etablissement = EtablissementClient::getInstance()->find($this->identifiant);
        $this->vrac = new Vrac();

        $this->vrac->setEtablissementCreateur($etablissement);
        $this->vrac->update();
        $this->vrac->setInformations();
        $this->vrac->numero_contrat = VracClient::getInstance()->getNextNoContrat();
        $this->vrac->save();
        $this->redirect('vrac_soussigne', array('numero_contrat' => $this->vrac->numero_contrat));
    }

    protected function postFormEtablissement(sfWebRequest $request) {
        if ($request->isMethod(sfWebRequest::POST)) {
            $form = new VracEtablissementChoiceForm('INTERPRO-inter-loire');
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                $etablissement = $form->getEtablissement();
                return $this->redirect(array('sf_route' => 'vrac_recherche', 'identifiant' => $etablissement->identifiant));
            }

            return $this->redirect('vrac');
        }
    }

    public function executeRecherche(sfWebRequest $request) {

        $this->redirect403IfIsTeledeclaration();
        $this->recherche = $this->getVracsFromRecherche($request, true);
        $this->form = new VracRechercheForm();
    }

    public function executeConnexion(sfWebRequest $request) {

        $this->redirect403IfIsTeledeclaration();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $societeIdentifiant = $this->etablissement->getSociete()->identifiant;

        $this->getUser()->usurpationOn($societeIdentifiant, $request->getReferer());
        $this->redirect('homepage');
    }

    public function executeDeconnexion(sfWebRequest $request) {

        $url_back = $this->getUser()->usurpationOff();

        if ($url_back) {

            return $this->redirect($url_back);
        }

        $this->redirect('homepage');
    }

    public function executeExportHistoriqueCsv(sfWebRequest $request) {
        $this->setLayout(false);

        $file = $this->getCsvFromHistory($request, false);
        $filename = $this->createCsvFromHistoryFilename($request);

        $this->redirect403IfIsNotTeledeclarationAndNotMe();
//        $this->forward404Unless($this->vracs);

        $attachement = "attachment; filename=" . $filename . ".csv";

        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition', $attachement);
    }

    private function getCsvFromHistory($request, $limited = true) {

        $this->identifiant = $request['identifiant'];

        $this->initSocieteAndEtablissementPrincipal();

        $this->campagne = $request['campagne'];
        if (!$this->campagne || !preg_match('/[0-9]{4}-[0-9]{4}/', $this->campagne)) {
            throw new sfException("wrong campagne format ($this->campagne)");
        }

        $this->isOnlyOneEtb = !(count($this->societe->getEtablissementsObj()) - 1);

        $this->etablissement = (!isset($request['etablissement']) || $this->isOnlyOneEtb ) ? 'tous' : $request['etablissement'];
        $this->statut = (!isset($request['statut']) || $request['statut'] === 'tous' ) ? 'tous' : strtoupper($request['statut']);


        $this->vracs = VracClient::getInstance()->retrieveByCampagneEtablissementAndStatut($this->societe, $this->campagne, $this->etablissement, $this->statut);

        return true;
    }

    private function createCsvFromHistoryFilename($request) {
        $filename = str_replace(' ', '_', $this->societe->raison_sociale);

        $filename .= '_' . $request['campagne'];
        if ($this->etablissement != 'tous') {
            if (!$this->isOnlyOneEtb && ($this->etablissement != $this->etablissementPrincipal->identifiant)) {
                $filename .= '_' . EtablissementClient::getInstance()->retrieveById($this->etablissement)->nom;
            }
        }
        if ($this->statut != "tous") {
            if ($this->statut == "SOLDENONSLODE") {
                $filename .= '_VALIDE';
            } else {
                $filename .= '_' . $this->statut;
            }
        }
        return $filename;
    }

    private function getVracsFromRecherche($request, $limited = true) {
        $this->isType = isset($request['type']);
        $this->isStatut = isset($request['statut']);
        $this->identifiant = str_replace('ETABLISSEMENT-', '', $request->getParameter('identifiant'));
        $soussigneObj = EtablissementClient::getInstance()->find($this->identifiant);
        $soussigneId = 'ETABLISSEMENT-' . $this->identifiant;
        $this->type = null;
        $this->statut = null;
        $this->multiCritereType = null;
        $this->multiCritereStatut = null;
        $this->actifs = array();
        $this->actifs['type'] = '';
        $this->actifs['statut'] = '';

        $this->campagne = $request->getParameter('campagne', ConfigurationClient::getInstance()->getCurrentCampagne());
        if ($this->isType && $this->isStatut) {
            $this->statut = $request['statut'];
            $this->type = $request['type'];
            $this->vracs = ($limited) ?
                    VracClient::getInstance()->retrieveBySoussigneStatutAndType($soussigneId, $this->campagne, $this->statut, $this->type) : VracClient::getInstance()->retrieveBySoussigneStatutAndType($soussigneId, $this->campagne, $this->statut, $this->type, false);
            $this->actifs['statut'] = $request['statut'];
            $this->actifs['type'] = $request['type'];
            $this->multiCritereStatut = true;
            $this->multiCritereType = true;
        } elseif ($this->isStatut) {
            $this->statut = $request['statut'];
            $this->vracs = ($limited) ?
                    VracClient::getInstance()->retrieveBySoussigneAndStatut($soussigneId, $this->campagne, $request['statut']) : VracClient::getInstance()->retrieveBySoussigneAndStatut($soussigneId, $this->campagne, $request['statut'], false);
            $this->actifs['statut'] = $request['statut'];
            $this->multiCritereType = true;
        } elseif ($this->isType) {
            $this->type = $request['type'];
            $this->vracs = ($limited) ?
                    VracClient::getInstance()->retrieveBySoussigneAndType($soussigneId, $this->campagne, $request['type']) : VracClient::getInstance()->retrieveBySoussigneAndType($soussigneId, $this->campagne, $request['type'], false);
            $this->actifs['type'] = $request['type'];
            $this->multiCritereStatut = true;
        } else {
            $this->vracs = ($limited) ?
                    VracClient::getInstance()->retrieveBySoussigne($soussigneId, $this->campagne) : VracClient::getInstance()->retrieveBySoussigne($soussigneId, $this->campagne, false);
        }

        usort($this->vracs->rows, array("vracActions", "rechercheTriListOnID"));

        $this->etablissements = array('' => '');
        $soussignelabel = array($soussigneObj->nom, $soussigneObj->cvi, $soussigneObj->siege->commune);
        $this->etablissements[$this->identifiant] = trim(implode(',', array_filter($soussignelabel)));

        $datas = EtablissementClient::getInstance()->findAll()->rows;

        foreach ($datas as $data) {
            $labels = array($data->key[4], $data->key[3], $data->key[1]);
            $this->etablissements[$data->id] = trim(implode(',', array_filter($labels)));
        }
        return true;
    }

    static function rechercheTriListOnID($etb0, $etb1) {
        if ($etb0->id == $etb1->id) {

            return 0;
        }
        return ($etb0->id > $etb1->id) ? -1 : +1;
    }

    public function executeNouveau(sfWebRequest $request) {

        $this->redirect403IfICanNotCreate();
        $isMethodPost = $request->isMethod(sfWebRequest::POST);

        $this->getResponse()->setTitle('Contrat - Nouveau');
        $this->vrac = ($this->getUser()->getAttribute('vrac_object')) ? unserialize($this->getUser()->getAttribute('vrac_object')) : new Vrac();
        $this->vrac = $this->populateVracTiers($this->vrac);
        $this->compte = null;
        $this->etablissementPrincipal = null;
        $this->compteVendeurActif = true;
        $this->compteAcheteurActif = true;

        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();
        if ($this->isTeledeclarationMode) {
            $this->isAcheteurResponsable = $this->isAcheteurResponsable();
            $this->isCourtierResponsable = $this->isCourtierResponsable();
            if ($this->etablissement = $request->getParameter("etablissement")) {
                $this->vrac->initCreateur($this->etablissement);
                $this->initSocieteAndEtablissementPrincipal();
            }

            if ($this->choixEtablissement = $request->getParameter("choix-etablissement")) {
                $this->vrac->initCreateur($this->choixEtablissement);
                $this->etablissement = $this->choixEtablissement;
                $this->initSocieteAndEtablissementPrincipal();
            }
            if (!$isMethodPost && $this->societe->isNegociant() && count($this->societe->getEtablissementsObj()) > 1 && !$this->choixEtablissement) {
                return $this->redirect('vrac_societe_choix_etablissement', array('identifiant' => $this->societe->identifiant));
            }
        }

        $this->form = new VracSoussigneForm($this->vrac, $this->isTeledeclarationMode, $this->isAcheteurResponsable, $this->isCourtierResponsable);

        $this->init_soussigne($request, $this->form);
        $this->nouveau = true;
        $this->contratNonSolde = false;
        if ($isMethodPost) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->maj_etape(1);
                $this->vrac->numero_contrat = VracClient::getInstance()->getNextNoContrat();
                $this->vrac->constructId();
                $this->form->save();
                return $this->redirect('vrac_marche', $this->vrac);
            }
        }
        $this->setTemplate('soussigne');
    }

    public function executeChoixEtablissement(sfWebRequest $request) {
        $this->redirect403IfICanNotCreate();
        $this->initSocieteAndEtablissementPrincipal();
        $societeId = $request->getParameter('identifiant');
        if ($societeId != $this->societe->identifiant) {
            throw new sfException("Vous n'avez pas le droit d'acceder à ce choix pour cette société");
        }

        $this->form = new SocieteEtablissementChoiceForm($this->societe);

        if ($request->isMethod(sfWebRequest::POST)) {
            $parameters = $request->getParameter($this->form->getName());
            $this->form->bind($parameters);
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $etablissementId = $values['etablissementChoice'];
                if (!$etablissementId) {
                    throw new sfException("L'établissement n'a pas été choisi");
                }
                $etablissement = EtablissementClient::getInstance()->findByIdentifiant($etablissementId);
                if (!$etablissement) {
                    throw new sfException("L'établissement n'existe plus dans la base de donné");
                }
                $this->redirect('vrac_nouveau', array('choix-etablissement' => $etablissementId));
            }
        }
    }

    public function executeSociete(sfWebRequest $request) {
        $this->getUser()->setAttribute('vrac_object', null);
        $this->getUser()->setAttribute('vrac_acteur', null);
        $this->identifiant = $request['identifiant'];

        $this->initSocieteAndEtablissementPrincipal();

        $this->redirect403IfIsNotTeledeclarationAndNotMe();

        $this->contratsSocietesWithInfos = VracClient::getInstance()->retrieveBySocieteWithInfosLimit($this->societe, $this->etablissementPrincipal, 10);
    }

    public function executeHistory(sfWebRequest $request) {
        $this->getUser()->setAttribute('vrac_object', null);
        $this->getUser()->setAttribute('vrac_acteur', null);
        $this->identifiant = $request['identifiant'];

        $this->initSocieteAndEtablissementPrincipal();

        $this->redirect403IfIsNotTeledeclarationAndNotMe();

        $this->campagne = $request['campagne'];
        if (!$this->campagne || !preg_match('/[0-9]{4}-[0-9]{4}/', $this->campagne)) {
            throw new sfException("wrong campagne format ($this->campagne)");
        }

        $this->isOnlyOneEtb = !(count($this->societe->getEtablissementsObj()) - 1);

        $this->etablissement = (!isset($request['etablissement']) || $this->isOnlyOneEtb ) ? 'tous' : $request['etablissement'];
        $this->statut = (!isset($request['statut']) || $request['statut'] === 'tous' ) ? 'tous' : strtoupper($request['statut']);

        $this->form = new VracHistoryRechercheForm($this->societe, $this->etablissement, $this->campagne, $this->statut);

        $this->contratsByCampagneEtablissementAndStatut = VracClient::getInstance()->retrieveByCampagneEtablissementAndStatut($this->societe, $this->campagne, $this->etablissement, $this->statut);
    }

    public function executeSignature(sfWebRequest $request) {
        $this->setLayout(false);
        $this->vrac = $this->getRoute()->getVrac();
        if ($this->isTeledeclarationVrac()) {
            $this->initSocieteAndEtablissementPrincipal();
        }

        $this->redirect403IsNotTeledeclarationAndNotInVrac();

        $this->signatureRequired = !$this->vrac->isSocieteHasSigned($this->societe);
        if (!$this->signatureRequired) {
            $societeId = $this->societe->_id;
            throw new sfException("La societe $societeId a déja signé ce contrat.");
        }
        $this->etablissement_concerned = $this->vrac->getEtbConcerned($this->societe);
        $this->vrac->signatureByEtb($this->etablissement_concerned);
        $this->vrac->save();
        $this->redirect('vrac_visualisation', $this->vrac);
    }

    public function executeAnnuaire(sfWebRequest $request) {
        $this->identifiant = str_replace('ETABLISSEMENT-', '', $request->getParameter('identifiant'));
        $this->createur_identifiant = str_replace('ETABLISSEMENT-', '', $request->getParameter('createur'));
        $this->type = $request->getParameter('type');
        $this->acteur = $request->getParameter('acteur');

        $this->initSocieteAndEtablissementPrincipal();
        $this->redirect403IfICanNotCreate();

        $types = array_keys(AnnuaireClient::getAnnuaireTypes());
        if (!in_array($this->type, $types)) {
            throw new sfError404Exception('Le type "' . $this->type . '" n\'est pas pris en charge.');
        }

        $this->vrac = ($request->getParameter('numero_contrat')) ? VracClient::getInstance()->find($request->getParameter('numero_contrat')) : new Vrac();
        $this->vrac = $this->populateVracTiers($this->vrac);
        if ($this->vrac->isNew()) {
            $this->vrac->initCreateur($this->createur_identifiant);
        }

        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();
        if ($this->isTeledeclarationMode) {
            $this->isAcheteurResponsable = $this->isAcheteurResponsable();
            $this->isCourtierResponsable = $this->isCourtierResponsable();
        }

        $this->form = new VracSoussigneAnnuaireForm($this->vrac, $this->isTeledeclarationMode, $this->isAcheteurResponsable, $this->isCourtierResponsable);
        if ($request->isMethod(sfWebRequest::POST)) {
            $parameters = $request->getParameter($this->form->getName());
            unset($parameters['_csrf_token']);
            $this->form->bind($parameters);
            if ($this->form->isValid()) {
                $this->vrac = $this->form->getUpdatedVrac();
            } else {
                throw new sfException($this->form->renderGlobalErrors());
            }
        }
        $this->getUser()->setAttribute('vrac_object', serialize($this->vrac));
        $this->getUser()->setAttribute('vrac_acteur', $this->acteur);
        return $this->redirect('annuaire_selectionner', array('identifiant' => $this->identifiant, 'type' => $this->type));
    }

    public function executeAnnuaireCommercial(sfWebRequest $request) {
        $this->identifiant = str_replace('ETABLISSEMENT-', '', $request->getParameter('identifiant'));
        $this->createur_identifiant = str_replace('ETABLISSEMENT-', '', $request->getParameter('createur'));
        $this->vrac = ($request->getParameter('numero_contrat')) ? VracClient::getInstance()->find($request->getParameter('numero_contrat')) : new Vrac();
        $this->vrac = $this->populateVracTiers($this->vrac);
        if ($this->vrac->isNew()) {
            $this->vrac->initCreateur($this->createur_identifiant);
        }

        $this->initSocieteAndEtablissementPrincipal();
        $this->redirect403IfICanNotCreate();

        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();
        if ($this->isTeledeclarationMode) {
            $this->isAcheteurResponsable = $this->isAcheteurResponsable();
            $this->isCourtierResponsable = $this->isCourtierResponsable();
        }

        $this->form = new VracSoussigneAnnuaireForm($this->vrac, $this->isTeledeclarationMode, $this->isAcheteurResponsable, $this->isCourtierResponsable);
        if ($request->isMethod(sfWebRequest::POST)) {
            $parameters = $request->getParameter($this->form->getName());
            unset($parameters['_csrf_token']);
            $this->form->bind($parameters);
            if ($this->form->isValid()) {
                $this->vrac = $this->form->getUpdatedVrac();
            } else {
                throw new sfException($this->form->renderGlobalErrors());
            }
        }
        $this->getUser()->setAttribute('vrac_object', serialize($this->vrac));
        $this->getUser()->setAttribute('vrac_acteur', $this->acteur);
        return $this->redirect('annuaire_commercial_ajouter', array('identifiant' => $this->identifiant));
    }

    private function init_soussigne($request, $form) {
        $form->vendeur = null;
        $form->acheteur = null;
        $form->mandataire = null;

        if (!is_null($request->getParameter('vrac')) && !$request->getParameter('vrac') == '') {
            $vracParam = $request->getParameter('vrac');

            if (isset($vracParam['vendeur_identifiant']) && $vracParam['vendeur_identifiant']) {
                $form->vendeur = EtablissementClient::getInstance()->find($vracParam['vendeur_identifiant']);
            }
            if (isset($vracParam['acheteur_identifiant']) && $vracParam['acheteur_identifiant']) {
                $form->acheteur = EtablissementClient::getInstance()->find($vracParam['acheteur_identifiant']);
            }
            if (isset($vracParam['mandataire_identifiant']) && $vracParam['mandataire_identifiant']) {
                $form->mandataire = EtablissementClient::getInstance()->find($vracParam['mandataire_identifiant']);
            }
        }
    }

    public function executeSoussigne(sfWebRequest $request) {
        $this->getResponse()->setTitle(sprintf('Contrat N° %d - Soussignés', $request["numero_contrat"]));
        $this->vrac = ($this->getUser()->getAttribute('vrac_object')) ? unserialize($this->getUser()->getAttribute('vrac_object')) : $this->getRoute()->getVrac();
        $this->compte = null;

        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();

        $this->compteVendeurActif = null;
        $this->compteAcheteurActif = null;

        if ($this->isTeledeclarationMode) {
            $this->isAcheteurResponsable = $this->isAcheteurResponsable();
            $this->isCourtierResponsable = $this->isCourtierResponsable();
            $this->initSocieteAndEtablissementPrincipal();

            $this->compteVendeurActif = (!$this->vrac->getVendeurObject()) || $this->vrac->getVendeurObject()->hasCompteTeledeclarationActivate();
            $this->compteAcheteurActif = (!$this->vrac->getAcheteurObject()) || $this->vrac->getAcheteurObject()->hasCompteTeledeclarationActivate();
        }

        $this->redirect403IfIsNotTeledeclarationAndNotResponsable();

        $this->form = new VracSoussigneForm($this->vrac, $this->isTeledeclarationMode, $this->isAcheteurResponsable, $this->isCourtierResponsable);

        $this->init_soussigne($request, $this->form);
        $this->nouveau = false;
        $this->hasmandataire = !is_null($this->vrac->mandataire_identifiant);
        $this->contratNonSolde = ((!is_null($this->vrac->valide->statut)) && ($this->vrac->valide->statut != VracClient::STATUS_CONTRAT_SOLDE));

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->maj_etape(1);
                $this->form->save();
                $this->redirect('vrac_marche', $this->vrac);
            }
        }
    }

    public function executeMarche(sfWebRequest $request) {
        $this->getUser()->setAttribute('vrac_object', null);
        $this->getUser()->setAttribute('vrac_acteur', null);
        $this->getResponse()->setTitle(sprintf('Contrat N° %d - Marché', $request["numero_contrat"]));
        $this->vrac = $this->getRoute()->getVrac();
        $this->compte = null;
        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();
        $this->defaultDomaine = $this->vrac->domaine;
        if ($this->isTeledeclarationMode) {
            $this->initSocieteAndEtablissementPrincipal();
        }

        $this->redirect403IfIsNotTeledeclarationAndNotResponsable();

        if ($this->isTeledeclarationMode && !is_null($request->getParameter('vrac')) && !$request->getParameter('vrac') == '') {
            $vracParam = $request->getParameter('vrac');
            if (isset($vracParam['domaine']) && $vracParam['domaine']) {
                $this->defaultDomaine = $vracParam['domaine'];
            }
        }

        $this->form = new VracMarcheForm($this->vrac, $this->isTeledeclarationMode, $this->defaultDomaine);
        $vracParam = $request->getParameter('vrac');

        if ($request->isMethod(sfWebRequest::POST)) {
            if ($vracParam['millesime'] == VracMarcheForm::NONMILLESIMELABEL) {
                $vracParam['millesime'] = 0;
                $request->setParameter('vrac', $vracParam);
            }
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->maj_etape(2);
                $this->form->save();
                $this->redirect('vrac_condition', $this->vrac);
            } else {
                
            }
        }
    }

    public function executeCondition(sfWebRequest $request) {
        $this->getUser()->setAttribute('vrac_object', null);
        $this->getUser()->setAttribute('vrac_acteur', null);
        $this->getResponse()->setTitle(sprintf('Contrat N° %d - Conditions', $request["numero_contrat"]));
        $this->vrac = $this->getRoute()->getVrac();

        $this->compte = null;
        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();

        if ($this->isTeledeclarationMode) {
            $this->initSocieteAndEtablissementPrincipal();
        }

        $this->redirect403IfIsNotTeledeclarationAndNotResponsable();

        $this->form = new VracConditionForm($this->vrac, $this->isTeledeclarationMode);
        $this->displayPartiePrixVariable = !($this->vrac->isTeledeclare()) && !(is_null($this->type_contrat) || ($this->type_contrat == 'spot'));
        $this->displayPrixVariable = ($this->displayPartiePrixVariable && !is_null($vrac->prix_variable) && $vrac->prix_variable);
        $this->contratNonSolde = ((!is_null($this->vrac->valide->statut)) && ($this->vrac->valide->statut != VracClient::STATUS_CONTRAT_SOLDE));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->maj_etape(3);
                $this->form->save();
                $this->redirect('vrac_validation', $this->vrac);
            }
        }
    }

    public function executeValidation(sfWebRequest $request) {
        $this->getUser()->setAttribute('vrac_object', null);
        $this->getUser()->setAttribute('vrac_acteur', null);


        $this->getResponse()->setTitle(sprintf('Contrat N° %d - Validation', $request["numero_contrat"]));
        $this->vrac = $this->getRoute()->getVrac();
        $this->compte = null;
        $this->societe = null;
        $this->signatureDemande = false;
        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();
        if ($this->isTeledeclarationMode) {
            $this->initSocieteAndEtablissementPrincipal();
            $this->signatureDemande = !$this->vrac->isSocieteHasSigned($this->societe);
        } else {

            $this->etablissementPrincipal = EtablissementClient::getInstance()->retrieveById($this->vrac->createur_identifiant);
        }


        $this->redirect403IfIsNotTeledeclarationAndNotResponsable();

        $this->contratNonSolde = ((!is_null($this->vrac->valide->statut)) && ($this->vrac->valide->statut != VracClient::STATUS_CONTRAT_SOLDE));
        $this->vracs = VracClient::getInstance()->retrieveSimilaryContracts($this->vrac);
        $this->contratsSimilairesExist = (isset($this->vracs) && !$this->vracs && count($this->vracs) > 0);
        $this->validation = new VracValidation($this->vrac, $this->isTeledeclarationMode);

        if ($request->isMethod(sfWebRequest::POST)) {
            if ($this->validation->isValide()) {
                $this->maj_etape(4);
                $this->vrac->validate(array('isTeledeclarationMode' => $this->isTeledeclarationMode));
                $this->vrac->save();
                $this->postValidateActions();
                $this->getUser()->setFlash('postValidation', true);
                $this->redirect('vrac_visualisation', $this->vrac);
            }
        }
    }

    public function executeVisualisation(sfWebRequest $request) {
        $this->getUser()->setAttribute('vrac_object', null);
        $this->getUser()->setAttribute('vrac_acteur', null);
        $this->getResponse()->setTitle(sprintf('Contrat N° %d - Visualisation', $request["numero_contrat"]));
        $this->vrac = $this->getRoute()->getVrac();
        $this->signatureDemande = false;
        $this->compte = null;
        $this->societe = null;
        if ($this->isTeledeclarationVrac()) {
            $this->initSocieteAndEtablissementPrincipal();
            $this->signatureDemande = !$this->vrac->isSocieteHasSigned($this->societe);
        }

        $this->redirect403IsNotTeledeclarationAndNotInVrac();
        $this->redirect403IsInVracAndNotAllowedToSee();

        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();

        if ($this->isTeledeclarationMode) {
            $this->isProprietaire = $this->isTeledeclarationMode && $this->vrac->exist('createur_identifiant') && $this->vrac->createur_identifiant && ($this->societe->identifiant == substr($this->vrac->createur_identifiant, 0, 6));
        }
        $this->isTeledeclare = $this->vrac->isTeledeclare();
        $this->isAnnulable = $this->isTeledeclarationVrac() && $this->vrac->isTeledeclarationAnnulable() && ($this->vrac->getCreateurObject()->getSociete()->identifiant === $this->societe->identifiant);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->majStatut(VracClient::STATUS_CONTRAT_ANNULE);
            $this->vrac->save();
        }
    }

    public function executeChangeStatut(sfWebRequest $request) {

        $this->redirect403IfIsTeledeclaration();
        $this->vrac = $this->getRoute()->getVrac();
        switch ($statut = $this->vrac->valide->statut) {
            case VracClient::STATUS_CONTRAT_NONSOLDE: {
                    $this->vrac->valide->statut = VracClient::STATUS_CONTRAT_SOLDE;
                    $this->vrac->save();
                    break;
                }
            case VracClient::STATUS_CONTRAT_SOLDE: {
                    $this->vrac->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
                    $this->vrac->save();
                    break;
                }
            default:
                break;
        }
        $this->redirect('vrac_visualisation', $this->vrac);
    }

    public function executeGetInformations(sfWebRequest $request) {
        $etablissement = EtablissementClient::getInstance()->find($request->getParameter('id'));
        $nouveau = is_null($request->getParameter('numero_contrat'));
        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();
        $this->compteVendeurActif = null;
        $this->compteAcheteurActif = null;
        if ($this->isTeledeclarationMode) {
            $this->compteVendeurActif = $etablissement->hasCompteTeledeclarationActivate();
            $this->compteAcheteurActif = $etablissement->hasCompteTeledeclarationActivate();
        }
        return $this->renderPartialInformations($etablissement, $nouveau);
    }

    public function executeGetModifications(sfWebRequest $request) {
        $nouveau = is_null($request->getParameter('numero_contrat'));
        $etablissementId = ($request->getParameter('id') == null) ? $request->getParameter('vrac_' . $request->getParameter('type') . '_identifiant') : $request->getParameter('id');
        $etablissement = EtablissementClient::getInstance()->find($etablissementId);

        $this->forward404Unless($etablissement);
        $this->redirect403IfIsTeledeclaration();

        $this->form = new VracSoussigneModificationForm($etablissement);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                return $this->renderPartialInformations($etablissement, $nouveau);
            }
        }

        $familleType = $etablissement->getFamilleType();
        if ($familleType == 'vendeur' || $familleType == 'acheteur')
            $familleType = 'vendeurAcheteur';
        return $this->renderPartial($familleType . 'Modification', array('form' => $this->form));
    }

    public function executeGetContratsSimilaires(sfWebRequest $params) {
        $this->redirect403IfIsTeledeclaration();
        $vrac = VracClient::getInstance()->findByNumContrat($params['numero_contrat']);
        if (isset($params['type']) && $params['type'] != "")
            $vrac->type_transaction = $params['type'];
        if (isset($params['produit']) && $params['produit'] != "")
            $vrac->produit = $params['produit'];
        if (isset($params['volume']) && $params['volume'] != "")
            $vrac->volume_propose = $params['volume'] + 0;
        return $this->renderPartial('contratsSimilaires', array('vrac' => $vrac));
    }

    public function executeVolumeEnleve(sfWebRequest $request) {
        $this->vrac = VracClient::getInstance()->findByNumContrat($request['numero_contrat']);
        $this->redirect403IfIsTeledeclaration();
        $this->form = new VracVolumeEnleveForm($this->vrac);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('vrac_visualisation', $this->vrac);
            }
        }
    }

    private function createCsvFilename($request) {

        $etablissement = EtablissementClient::getInstance()->find($request['identifiant']);
        $nom = $etablissement['nom'];
        $nom = str_replace('M. ', '', $nom);
        $nom = str_replace('Mme ', '', $nom);
        $nom = str_replace(' ', '_', $nom);
        $statut = (isset($request['statut']) && !empty($request['statut'])) ? '_' . $request['statut'] : '';
        $type = (isset($request['type']) && !empty($request['type'])) ? '_' . $request['type'] : '';
        $date = date('Ymd');
        return 'exportCSV_' . $date . '_' . $nom . $statut . $type;
    }

    public function executeExportCsv(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $this->setLayout(false);
        $filename = $this->createCsvFilename($request);

        $file = $this->getVracsFromRecherche($request, false);

        $this->forward404Unless($this->vracs);

        $attachement = "attachment; filename=" . $filename . ".csv";

        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition', $attachement);
        //  $this->response->setHttpHeader('Content-Length', filesize($file));    
    }

    public function executeExportEtiquette(sfWebRequest $request) {
        $this->redirect403IfIsTeledeclaration();
        $this->date_debut = $request['vrac_vignettes']['date_debut'];
        $this->date_fin = $request['vrac_vignettes']['date_fin'];
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $this->setLayout(false);
        $filename = 'exportCSV_etiquette_' . date('Ymd');

        $attachement = "attachment; filename=" . $filename . ".csv";

        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition', $attachement);
    }

    public function executeLatex(sfWebRequest $request) {

        if ($this->isTeledeclarationVrac()) {
            $this->initSocieteAndEtablissementPrincipal();
        }

        $this->setLayout(false);
        $this->vrac = $this->getRoute()->getVrac();
        $this->forward404Unless($this->vrac);

        $this->redirect403IsNotTeledeclarationAndNotInVrac();

        $latex = new VracLatex($this->vrac);
        $latex->echoWithHTTPHeader($request->getParameter('type'));
        exit;
    }

    public function executeRedirectSaisie(sfWebRequest $request) {
        $this->setLayout(false);
        $this->vrac = $this->getRoute()->getVrac();
        $this->forward404Unless($this->vrac);
        if ($this->isTeledeclarationVrac() && $this->vrac->isBrouillon()) {
            $this->initSocieteAndEtablissementPrincipal();
        } else {
            throw new sfException("Le contrat n'est pas un brouillon, ou n'est pas un contrat télédéclaré");
        }
        return $this->redirectWithStep();
    }

    public function executeSuppressBrouillon(sfWebRequest $request) {
        $this->setLayout(false);
        $this->vrac = $this->getRoute()->getVrac();
        $this->forward404Unless($this->vrac);
        $this->isTeledeclarationMode = $this->isTeledeclarationVrac();
        if ($this->isTeledeclarationMode) {
            $this->initSocieteAndEtablissementPrincipal();
        }
        $this->redirect403IfIsNotTeledeclarationAndNotResponsable();
        $this->vrac->delete();
        $this->redirect('vrac_societe', array('identifiant' => str_replace('COMPTE-', '', $this->compte->_id)));
    }

    public function executeNotice(sfWebRequest $request) {
        switch ($request->getParameter('type')) {
            case SocieteClient::SUB_TYPE_VITICULTEUR:

                return $this->renderPdf(sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . "data/Guide_viticulteur.pdf", "Guide_viticulteur.pdf");

            case SocieteClient::SUB_TYPE_NEGOCIANT:

                return $this->renderPdf(sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . "data/Guide_negociant.pdf", "Guide_négociant.pdf");

            case SocieteClient::SUB_TYPE_COURTIER:

                return $this->renderPdf(sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . "data/Guide_courtier.pdf", "Guide_courtier.pdf");
        }
        throw new sfException("Notice non trouvée");
    }

    private function redirectWithStep() {
        switch ($this->vrac->etape) {
            case 1:
                return $this->redirect('vrac_soussigne', array('numero_contrat' => $this->vrac->numero_contrat));
                break;
            case 2:
                $this->redirect('vrac_marche', $this->vrac);
                break;
            case 3:
                $this->redirect('vrac_condition', $this->vrac);
                break;
            case 4:
                $this->redirect('vrac_validation', $this->vrac);
                break;
            default :
                $this->redirect('vrac_visualisation', $this->vrac);
                break;
        }
    }

    private function renderPartialInformations($etablissement, $nouveau) {
        $familleType = $etablissement->getFamilleType();
        return $this->renderPartial($familleType . 'Informations', array($familleType => $etablissement, 'nouveau' => $nouveau,
                    'isTeledeclarationMode' => $this->isTeledeclarationMode, 'compteVendeurActif' => $this->compteVendeurActif, 'compteAcheteurActif' => $this->compteAcheteurActif));
    }

    private function maj_etape($etapeNum) {
        if ($this->vrac->etape < $etapeNum)
            $this->vrac->etape = $etapeNum;
    }

    private function majStatut($statut) {
        $previous_statut = $this->vrac->valide->statut;
        $this->vrac->valide->statut = $statut;
        if ($this->vrac->isTeledeclare() && $statut == VracClient::STATUS_CONTRAT_ANNULE && $previous_statut != VracClient::STATUS_CONTRAT_BROUILLON) {
            $mailManager = new VracEmailManager($this->getMailer());
            $mailManager->setVrac($this->vrac);
            if (!$this->isUsurpationMode() && $this->isTeledeclarationVrac()) {
                $mailManager->sendMailAnnulation(!$this->isTeledeclarationVrac());
            }
        }
    }

    private function postValidateActions() {
        if ($this->vrac->isTeledeclare() &&
                ($this->vrac->valide->statut == VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE)) {
            if (!$this->vrac->exist('createur_identifiant') || !$this->vrac->createur_identifiant) {
                throw new sfException("Le créateur du contrat $this->vrac->_id ne peut pas être null.");
            }
            $mailManager = new VracEmailManager($this->getMailer());
            $mailManager->setVrac($this->vrac);
            if (!$this->isUsurpationMode() && $this->isTeledeclarationVrac()) {
                $mailManager->sendMailAttenteSignature();
            }
        }
    }

    protected function forwardSecure() {
        $this->context->getController()->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));

        throw new sfStopException();
    }

    /*
     * Fonctions pour la population de l'annuaire
     * 
     */

    protected function populateVracTiers($vrac) {
        $vrac->setInformations();
        return $vrac;
    }

    /*
     * Fonctions de service liées aux droits Users
     * 
     */

    private function isTeledeclarationVrac() {
        return $this->getUser()->hasTeledeclarationVrac();
    }

    private function isUsurpationMode() {
        return $this->getUser()->isUsurpationCompte();
    }

    private function hasTeledeclarationVracCreation() {
        return $this->getUser()->hasTeledeclarationVracCreation();
    }

    private function isAcheteurResponsable() {
        return $this->getUser()->getCompte()->getSociete()->isNegociant();
    }

    private function isCourtierResponsable() {
        return $this->getUser()->getCompte()->getSociete()->isCourtier();
    }

    private function initSocieteAndEtablissementPrincipal() {
        $this->compte = $this->getUser()->getCompte();
        if (!$this->compte) {
            new sfException("Le compte $compte n'existe pas");
        }
        $this->societe = $this->compte->getSociete();
        $this->etablissementPrincipal = $this->societe->getEtablissementPrincipal();
    }

    private function redirect403IfIsTeledeclaration() {
        if ($this->isTeledeclarationVrac()) {
            $this->redirect403();
        }
    }

    private function redirect403IfIsNotTeledeclaration() {
        if (!$this->isTeledeclarationVrac()) {
            $this->redirect403();
        }
    }

    private function redirect403IfIsNotTeledeclarationAndNotResponsable() {
        $this->redirect403IfICanNotCreate();
        if ($this->isTeledeclarationVrac()) {
            if (!$this->vrac->exist('createur_identifiant')) {
                $this->redirect403();
            }
            if (substr($this->vrac->createur_identifiant, 0, 6) != substr($this->compte->identifiant, 0, 6)) {
                $this->redirect403();
            }
        }
    }

    private function redirect403IfIsNotTeledeclarationAndNotMe() {
        $this->redirect403IfIsNotTeledeclaration();
        if ($this->getUser()->getCompte()->identifiant != $this->identifiant) {
            $this->redirect403();
        }
    }

    private function redirect403IsNotTeledeclarationAndNotInVrac() {

        if (!$this->vrac) {
            $this->redirect403();
        }
        if (!$this->isTeledeclarationVrac()) {
            if (!$this->getUser()->hasCredential(Roles::CONTRAT)) {
                $this->redirect403();
            }
        } else {
            $array_soussigne = array();
            $array_soussigne[] = $this->vrac->vendeur_identifiant;
            $array_soussigne[] = $this->vrac->acheteur_identifiant;
            if ($this->vrac->mandataire_exist) {
                $array_soussigne[] = $this->vrac->mandataire_identifiant;
            }
            $isInVrac = false;
            foreach ($array_soussigne as $soussigneID) {
                if (substr($this->compte->identifiant, 0, 6) == substr($soussigneID, 0, 6)) {
                    $isInVrac = true;
                    break;
                }
            }
            if (!$isInVrac) {
                $this->redirect403();
            }
        }
    }

    private function redirect403IsInVracAndNotAllowedToSee() {
        $this->redirect403IsNotTeledeclarationAndNotInVrac();
        if ($this->isTeledeclarationVrac()) {
            if (!$this->vrac->valide->exist('statut')) {
                $this->redirect403();
            }
            $createur = $this->vrac->getCreateurObject();
            if (($this->vrac->valide->statut == VracClient::STATUS_CONTRAT_BROUILLON) &&
                    (substr($createur->identifiant, 0, 6) != $this->societe->identifiant)) {
                $this->redirect403();
            }

            if ($this->vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE) {
                $this->redirect403();
            }
        }
    }

    private function redirect403IfICanNotCreate() {
        if ($this->isTeledeclarationVrac()) {
            if ($this->hasTeledeclarationVracCreation()) {
                return;
            }
        }
        if ($this->getUser()->hasCredential(Roles::CONTRAT)) {
            return;
        }
        $this->redirect403();
    }

    private function redirect403() {
        $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    /*
     * Fonctions pour le téléchargement de la reglementation_generale_des_transactions
     * 
     */

    protected function renderPdf($path, $filename) {
        $this->getResponse()->setHttpHeader('Content-Type', 'application/pdf');
        $this->getResponse()->setHttpHeader('Content-disposition', 'attachment; filename="' . $filename . '"');
        $this->getResponse()->setHttpHeader('Content-Transfer-Encoding', 'binary');
        $this->getResponse()->setHttpHeader('Content-Length', filesize($path));
        $this->getResponse()->setHttpHeader('Pragma', '');
        $this->getResponse()->setHttpHeader('Cache-Control', 'public');
        $this->getResponse()->setHttpHeader('Expires', '0');
        return $this->renderText(file_get_contents($path));
    }

}

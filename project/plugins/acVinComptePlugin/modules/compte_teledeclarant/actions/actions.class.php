<?php

/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acVinCompte plugin.
 *
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class compte_teledeclarantActions extends sfActions {

    const SESSION_COMPTE_DOC_ID_CREATION = '';
    const SESSION_COMPTE_DOC_ID_OUBLIE = '';
    const SESSION_REDIRECTION_APRES_CREATION = 'redirect_to';
    const PARAM_REDIRECTION = 'service';

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeFirst(sfWebRequest $request) {
        $this->form = new CompteLoginFirstForm();

        $this->getUser()->getAttributeHolder()->remove(self::SESSION_REDIRECTION_APRES_CREATION);
        if ($request->hasParameter(self::PARAM_REDIRECTION)) {
            $this->getUser()->setAttribute(self::SESSION_REDIRECTION_APRES_CREATION, $request->getParameter(self::PARAM_REDIRECTION));
        }

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->getUser()->setAttribute(self::SESSION_COMPTE_DOC_ID_CREATION, $this->form->getValue('compte')->_id);

                return $this->redirect('compte_teledeclarant_cgu');
            }
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeCgu(sfWebRequest $request) {
        if(!is_file(sfConfig::get('sf_app_dir').'/modules/compte_teledeclarant/templates/cguSuccess.php')) {

            return $this->redirect("compte_teledeclarant_creation");
        }

        if($request->isMethod(sfWebRequest::POST)) {

            return $this->redirect("compte_teledeclarant_creation");
        }
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeCreation(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getAttribute(self::SESSION_COMPTE_DOC_ID_CREATION, null));
        $this->compte = CompteClient::getInstance()->find($this->getUser()->getAttribute(self::SESSION_COMPTE_DOC_ID_CREATION, null));
        $this->forward404Unless($this->compte);
        $this->forward404Unless($this->compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU);

        $this->form = new CompteTeledeclarantCreationForm($this->compte, array(), array('noSaveChangement' => true));

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $emailCible = $this->form->getValue('email');

                $this->getUser()->getAttributeHolder()->remove(self::SESSION_COMPTE_DOC_ID_CREATION);
                $this->getUser()->signInOrigin($this->compte);

                $message = $this->getMailer()->composeAndSend(array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name')), $emailCible, "Confirmation de création de votre compte", $this->getPartial('compte_teledeclarant/creationEmail', array('compte' => $this->compte)));

                if ($this->form->hasUpdatedValues()) {
                    Email::getInstance()->sendNotificationModificationsExploitation($this->compte->getSociete()->getEtablissementPrincipal(), $this->form->getUpdatedValues());
                }

                $urlback = $this->getUser()->getAttribute(self::SESSION_REDIRECTION_APRES_CREATION, null);
                $this->getUser()->getAttributeHolder()->remove(self::SESSION_REDIRECTION_APRES_CREATION);

                return ($urlback !== null) ? $this->redirect($urlback) : $this->redirect('common_homepage');
            }
        }
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeModification(sfWebRequest $request) {
        $this->compte = $this->getUser()->getCompte();
        $this->etablissementPrincipal = null;
        $societe = $this->compte->getSociete();
        $this->mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($societe);
        if($societe->isTransaction()){
            $this->etablissementPrincipal = $societe->getEtablissementPrincipal();
        }

        $this->form = new CompteTeledeclarantForm($this->compte);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();

                if ($this->form->hasUpdatedValues()) {
                    Email::getInstance()->sendNotificationModificationsExploitation($this->etablissementPrincipal ? $this->etablissementPrincipal : $societe, $this->form->getUpdatedValues());
                }

                $this->getUser()->setFlash('maj', 'Vos identifiants ont bien été mis à jour.');
                $this->redirect('compte_teledeclarant_modification');
            }
        }
    }

    public function executeCoordonneesBancaires(sfWebRequest $request) {
          $this->compte = $this->getUser()->getCompte();
          $this->societe = $this->compte->getSociete();
          $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($this->societe);
          if (!$mandatSepa) {
            $mandatSepa = MandatSepaClient::getInstance()->createDoc($this->societe);
          }
          $this->form = new MandatSepaDebiteurForm($mandatSepa->debiteur);

          if ($request->isMethod(sfWebRequest::POST)) {
              $this->form->bind($request->getParameter($this->form->getName()));
              if ($this->form->isValid()) {
                  $this->form->save();
                  $this->getUser()->setFlash('maj', 'Vos coordonnées bancaires ont bien été mises à jour.');
                  $this->redirect('compte_teledeclarant_modification');
              }
          }
    }

    public function executeMotDePasseOublie(sfWebRequest $request) {
        $this->form = new CompteMotDePasseOublieForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compte = $this->form->save();

                $societe = $compte->getSociete();
                $lien = $this->generateUrl("compte_teledeclarant_mot_de_passe_oublie_login", array("login" => $societe->identifiant, "mdp" => str_replace("{OUBLIE}", "", $compte->mot_de_passe)), true);
                $emailCible = $compte->getTeledeclarationEmail();
                if (!$emailCible) {
                    $emailCible = $compte->getEmail();
                }

                $message = $this->getMailer()->composeAndSend(array(sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name')), $emailCible, "Demande de mot de passe oublié", $this->getPartial('motDePasseOublieEmail', array('compte' => $compte, 'lien' => $lien)));
                $this->redirect('compte_teledeclarant_mot_de_passe_oublie_confirm');
            }
        }
    }

    public function executeMotDePasseOublieLogin(sfWebRequest $request) {
        $this->forward404Unless($compte = CompteClient::getInstance()->findByLogin($request->getParameter('login', null)));
        $this->forward404Unless($compte->mot_de_passe == '{OUBLIE}' . $request->getParameter('mdp', null));
        $this->getUser()->setAttribute(self::SESSION_COMPTE_DOC_ID_OUBLIE, $compte->_id);

        $this->redirect('compte_teledeclarant_modification_oublie');
    }

    public function executeMotDePasseOublieConfirm(sfWebRequest $request) {

    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeModificationOublie(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getAttribute(self::SESSION_COMPTE_DOC_ID_OUBLIE, null));
        $this->compte = CompteClient::getInstance()->find($this->getUser()->getAttribute(self::SESSION_COMPTE_DOC_ID_OUBLIE, null));
        $this->forward404Unless($this->compte);
        $this->forward404Unless($this->compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_OUBLIE);

        $this->form = new CompteTeledeclarantOublieForm($this->compte);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->getUser()->getAttributeHolder()->remove(self::SESSION_COMPTE_DOC_ID_OUBLIE);
                $this->getUser()->signInOrigin($this->compte);

                return $this->redirect('common_homepage');
            }
        }
    }

    public function executeReglementationGenerale() {
        return $this->renderPdf(sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . "data/reglementation_generale_des_transactions.pdf", "reglementation_generale_des_transactions.pdf");
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

    private function checkApiAccess(sfWebRequest $request) {
        $secret = sfConfig::get('app_viticonnect_secret');
        $login = $request->getParameter('login');

        $epoch = $request->getParameter('epoch');
        if(empty($secret)) {
            http_response_code(403);
            die('Forbidden');
        }
        if(abs(time() - $epoch) > 30) {
            http_response_code(403);
            die('Forbidden');
        }

        $md5 = $request->getParameter('md5');

        if ($md5 != md5($secret."/".$login."/".$epoch)) {
            http_response_code(401);
            die("Unauthorized");
        }
    }

    public function executeViticonnectApi(sfWebRequest $request)
    {
        $this->checkApiAccess($request);
        $login = $request->getParameter('login');
        $compte = acCouchdbManager::getClient('Compte')->retrieveByLogin($login);
        if (!$compte) {
            $compte = acCouchdbManager::getClient('Compte')->retrieveByLogin(strtolower($login));
        }
        if (!$compte) {
            http_response_code(401);
            die("Unauthorized $login");
        }
        $this->entities = array('raison_sociale' => [], 'cvi' => [], 'siret' => [], 'ppm' => [], 'accise' => [], 'tva' => []);
        $this->entities_number = 0;
        $entities = array();
        foreach($compte->getSociete()->getEtablissementsObj() as $e) {
            $k = $e->etablissement->cvi.$e->etablissement->no_accises;
            if ($k) {
                $entities[$k] = $e;
            }
        }
        foreach($entities as $k => $e) {
            $this->entities['raison_sociale'][] = htmlspecialchars($e->etablissement->raison_sociale, ENT_XML1, 'UTF-8');
            $this->entities['cvi'][] = str_replace(' ', '', $e->etablissement->cvi);
            $this->entities['siret'][] = str_replace(' ', '', $compte->societe_informations->siret);
            $this->entities['ppm'][] = str_replace(' ', '', $e->etablissement->ppm);
            $this->entities['accises'][] = str_replace(' ', '', $e->etablissement->no_accises);
            $this->entities['tva'][] = str_replace(' ', '', $compte->getSociete()->no_tva_intracommunautaire);
            $this->entities_number++;
        }

        $this->setLayout(false);
        $this->getResponse()->setHttpHeader('Content-Type', 'text/plain');

    }

    public function executeViticonnectCheck(sfWebRequest $request)
    {
        $this->checkApiAccess($request);
        $login = $request->getParameter('login');
        $comptes = EtablissementAllView::getInstance()->findByInterproAndStatut('INTERPRO-declaration', EtablissementClient::STATUT_ACTIF, $login);
        if(count($comptes) == 1) {
            echo "Found";
            exit;
        }
        http_response_code(404);
        die('Not found');

    }

}

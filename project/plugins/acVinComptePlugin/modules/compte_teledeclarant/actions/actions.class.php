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
        $this->service = $request->getParameter('service');
        $this->form = new CompteLoginFirstForm();

        $this->getUser()->getAttributeHolder()->remove(self::SESSION_REDIRECTION_APRES_CREATION);
        if ($request->hasParameter(self::PARAM_REDIRECTION)) {
            $this->getUser()->setAttribute(self::SESSION_REDIRECTION_APRES_CREATION, $request->getParameter(self::PARAM_REDIRECTION));
        }

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->getUser()->setAttribute(self::SESSION_COMPTE_DOC_ID_CREATION, $this->form->getValue('compte')->_id);

                if ($this->service) {
                    return $this->redirect('compte_teledeclarant_cgu', ['service' => $this->service]);
                }

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
        $this->service = $request->getParameter('service');

        if(!is_file(sfConfig::get('sf_app_dir').'/modules/compte_teledeclarant/templates/cguSuccess.php')) {

            return $this->service ? $this->redirect("compte_teledeclarant_creation") : $this->redirect("compte_teledeclarant_creation", ['service' => $this->service]);
        }

        if($request->isMethod(sfWebRequest::POST)) {

            return $this->service ? $this->redirect("compte_teledeclarant_creation") : $this->redirect("compte_teledeclarant_creation", ['service' => $this->service]);
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

        $this->service = $request->getParameter('service');
        $this->form = new CompteTeledeclarantCreationForm($this->compte, array(), array('noSaveChangement' => true));

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $emailCible = $this->form->getValue('email');

                $this->getUser()->getAttributeHolder()->remove(self::SESSION_COMPTE_DOC_ID_CREATION);
                $this->getUser()->signInOrigin($this->compte);

                $message = $this->getMailer()->composeAndSend(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $emailCible, "Confirmation de création de votre compte", $this->getPartial('compte_teledeclarant/creationEmail', array('compte' => $this->compte)));

                if ($this->service) {
                    return $this->redirect($this->service);
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
        $this->service = $request->getParameter('service');
        if($request->getParameter('identifiant')) {
            $this->compte = CompteClient::getInstance()->findByLogin($request->getParameter('identifiant'));
        } else {
            $this->compte = $this->getUser()->getCompte();
        }

        if(!$this->compte) {

            throw new sfError404Exception("Compte ".$request->getParameter('identifiant')." not found");
        }

        if(!$this->getUser()->hasCredential(AppUser::CREDENTIAL_ADMIN) && $this->compte->getSociete()->_id != $this->getUser()->getCompte()->getSociete()->_id) {

            throw new sfError403Exception();
        }

        $societe = $this->compte->getSociete();
        if($societe->isTransaction()){
            $this->etablissementPrincipal = $societe->getEtablissementPrincipal();
        }

        if($this->compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU) {

            return sfView::SUCCESS;
        }

        $this->societe = $this->compte->getSociete();

        $this->form = new CompteTeledeclarantForm($this->compte);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();

                if ($this->form->hasUpdatedValues()) {
                    Email::getInstance()->sendNotificationModificationsExploitation($this->etablissementPrincipal ? $this->etablissementPrincipal : $societe, $this->form->getUpdatedValues());
                }

                $this->getUser()->setFlash('maj', 'Les informations ont été mises à jour.');

                if($this->service) {

                    return $this->redirect('compte_teledeclarant_modification_id', ['identifiant' => $this->compte->login, 'service' => $this->service]);
                }

                return $this->redirect('compte_teledeclarant_modification_id', ['identifiant' => $this->compte->login]);
            }
        }
    }

    public function executeMotDePasseOublie(sfWebRequest $request) {
        $this->service = $request->getParameter('service');
        $this->form = new CompteMotDePasseOublieForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compte = $this->form->save();

                $societe = $compte->getSociete();
                $lien = $this->generateUrl("compte_teledeclarant_mot_de_passe_oublie_login", array("login" => $compte->login, "mdp" => str_replace("{OUBLIE}", "", $compte->mot_de_passe)), true);
                if ($this->service) {
                    $lien .= '?service='.$this->service;
                }
                $emailCible = $compte->getTeledeclarationEmail();
                if (!$emailCible) {
                    $emailCible = $compte->getEmail();
                }

                $message = $this->getMailer()->composeAndSend(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $emailCible, "Demande de mot de passe oublié", $this->getPartial('motDePasseOublieEmail', array('compte' => $compte, 'lien' => $lien)));

                if ($this->service) {
                    return $this->redirect('compte_teledeclarant_mot_de_passe_oublie_confirm', ['service' => $this->service]);
                }

                return $this->redirect('compte_teledeclarant_mot_de_passe_oublie_confirm');
            }
        }
    }

    public function executeMotDePasseOublieLogin(sfWebRequest $request) {
        $this->forward404Unless($compte = CompteClient::getInstance()->findByLogin($request->getParameter('login', null)));
        $this->forward404Unless($compte->mot_de_passe == '{OUBLIE}' . $request->getParameter('mdp', null));
        $this->getUser()->setAttribute(self::SESSION_COMPTE_DOC_ID_OUBLIE, $compte->_id);
        $this->service = $request->getParameter('service');

        if($this->service) {

            return $this->redirect('compte_teledeclarant_modification_oublie', ['service' => $this->service]);
        }

        return $this->redirect('compte_teledeclarant_modification_oublie');
    }

    public function executeMotDePasseOublieConfirm(sfWebRequest $request) {
        $this->service = $request->getParameter('service');
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

        $this->service = $request->getParameter('service');
        $this->form = new CompteTeledeclarantOublieForm($this->compte);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->getUser()->getAttributeHolder()->remove(self::SESSION_COMPTE_DOC_ID_OUBLIE);
                $this->getUser()->signInOrigin($this->compte);

                if ($this->service) {
                    return $this->redirect($this->service);
                }

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
            $this->entities['siret'][] = str_replace(' ', '', $compte->getSociete()->siret);
            $this->entities['accises'][] = str_replace(' ', '', $e->etablissement->no_accises);
            $this->entities['tva'][] = str_replace(' ', '', $compte->getSociete()->no_tva_intracommunautaire);
            if($request->getParameter('extra')) {
                $this->entities['numero_interne'][] = str_replace(' ', '', $e->etablissement->getNumInterne());
                $this->entities['code_comptable_client'][] = str_replace(' ', '', $compte->getSociete()->getCodeComptableClient());
                $this->entities['adresse'][] = htmlspecialchars($e->etablissement->getAdresse(), ENT_XML1, 'UTF-8');
                $this->entities['adresse_complementaire'][] = htmlspecialchars($e->etablissement->getAdresseComplementaire(), ENT_XML1, 'UTF-8');
                $this->entities['code_postal'][] = $e->etablissement->getCodePostal();
                $this->entities['commmune'][] = htmlspecialchars($e->etablissement->getCommune(), ENT_XML1, 'UTF-8');
                $this->entities['famille'][] = $e->etablissement->getFamille();
                $this->entities['email'][] = $e->etablissement->getEmailTeledeclaration();
                $this->entities['telephone_bureau'][] = str_replace(' ', '', $e->etablissement->getTelephoneBureau());
                $this->entities['telephone_mobile'][] = str_replace(' ', '', $e->etablissement->getTelephoneMobile());
                $this->entities['telephone_perso'][] = str_replace(' ', '', $e->etablissement->getTelephonePerso());
                $this->entities['droits'][] = implode("|", ($compte->exist('droits')) ? $compte->getDroits()->toArray() : []);
            }
            $this->entities_number++;
        }
        if(!$compte->getSociete()->canHaveChais()) {
            $this->entities['raison_sociale'][] = htmlspecialchars($compte->getSociete()->raison_sociale, ENT_XML1, 'UTF-8');
            $this->entities['siret'][] = str_replace(' ', '', $compte->getSociete()->siret);
            $this->entities['tva'][] = str_replace(' ', '', $compte->getSociete()->no_tva_intracommunautaire);
            $this->entities['adresse'][] = htmlspecialchars($compte->getSociete()->getAdresse(), ENT_XML1, 'UTF-8');
            $this->entities['adresse_complementaire'][] = htmlspecialchars($compte->getSociete()->getAdresseComplementaire(), ENT_XML1, 'UTF-8');
            $this->entities['code_postal'][] = $compte->getSociete()->getCodePostal();
            $this->entities['commmune'][] = htmlspecialchars($compte->getSociete()->getCommune(), ENT_XML1, 'UTF-8');
            $this->entities['email'][] = $compte->getSociete()->getEmailTeledeclaration();
            $this->entities['telephone_bureau'][] = str_replace(' ', '', $compte->getSociete()->getTelephoneBureau());
            $this->entities['telephone_mobile'][] = str_replace(' ', '', $compte->getSociete()->getTelephoneMobile());
            $this->entities['telephone_perso'][] = str_replace(' ', '', $compte->getSociete()->getTelephonePerso());
            $this->entities['droits'][] = implode("|", ($compte->exist('droits')) ? $compte->getDroits()->toArray() : []);
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

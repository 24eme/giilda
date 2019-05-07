<?php

/**
 * Description of class drmGeneriqueActions
 * @author mathurin
 */
class factureGeneriqueActions extends sfActions {

  protected function initSocieteAndEtablissementPrincipal() {
      $this->compte = $this->getUser()->getCompte();
      if ($this->isTeledeclarationFacture()) {

          if (!$this->compte) {
              new sfException("Le compte $compte n'existe pas");
          }
          $this->societe = $this->compte->getSociete();
          $this->etablissementPrincipal = $this->societe->getEtablissementPrincipal();
      }
  }

    protected function redirect403IfIsNotTeledeclaration() {
        if (!$this->isTeledeclarationFacture()) {
            $this->redirect403();
        }
    }

     protected function redirect403IfIsTeledeclaration() {
        if ($this->isTeledeclarationFacture()) {
            $this->redirect403();
        }
    }

    protected function redirect403IfIsNotTeledeclarationAndNotMe() {
        $this->redirect403IfIsNotTeledeclaration();
        if ($this->getUser()->getCompte()->identifiant != $this->identifiant) {
            $this->redirect403();
        }
    }

    private function redirect403Unless($bool) {
        if (!$bool) {
          $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
    }


    private function redirect403() {
        $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    protected function isTeledeclarationFacture() {
    	return $this->getUser()->hasTeledeclarationFacture();
    }

    protected function initDeleteForm() {
        $this->deleteForm = new DRMDeleteForm($this->drm);
    }

    protected function isUsurpationMode() {
        return $this->getUser()->isUsurpationCompte();
    }


}

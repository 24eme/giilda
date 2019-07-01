<?php

/**
 * Description of class drmGeneriqueActions
 * @author mathurin
 */
class drmGeneriqueActions extends sfActions {

    protected function initSocieteAndEtablissementPrincipal() {
        $this->compte = $this->getUser()->getCompte();
        if ($this->isTeledeclaration()) {

            if (!$this->compte) {
                new sfException("Le compte $compte n'existe pas");
            }
        $this->societe = $this->compte->getSociete();
        $this->etablissementPrincipal = $this->societe->getEtablissementPrincipal();
        $this->etablissement = $this->etablissementPrincipal;
        }
    }

    protected function redirect403IfIsNotTeledeclaration($type = null) {
        if (!$this->isTeledeclaration()) {
            $this->redirect403();
        }

        if ($type == Roles::TELEDECLARATION_DRM && !$this->isTeledeclarationDrm()) {
            $this->redirect403();
        }

        if ($type == Roles::TELEDECLARATION_VRAC && !$this->isTeledeclarationVrac()) {
            $this->redirect403();
        }

        if ($type == Roles::TELEDECLARATION_VRAC_CREATION && !$this->isTeledeclarationVracCreation()) {
          $this->redirect403();
        }

        if ($type == Roles::TELEDECLARATION_FACTURE && !$this->isTeledeclarationFacture()) {
            $this->redirect403();
        }

    }

     protected function redirect403IfIsTeledeclaration() {
        if ($this->isTeledeclarationDrm()) {
            $this->redirect403();
        }
    }

    protected function redirect403IfIsNotTeledeclarationAndNotMe($type = null) {
        $this->redirect403IfIsNotTeledeclaration($type);
        if ($this->getUser()->getCompte()->getSociete()->identifiant != substr($this->identifiant,0,6)) {
            $this->redirect403();
        }
    }

    private function redirect403() {
        $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    protected function isTeledeclaration() {
      if($this->isUsurpationMode()){
        return $this->getUser()->getCompte()->hasDroit(Roles::TELEDECLARATION);
      }
      return $this->getUser()->hasTeledeclaration();
    }

    protected function isTeledeclarationDrm() {
      if($this->isUsurpationMode()){
        return $this->getUser()->getCompte()->hasDroit(Roles::TELEDECLARATION_DRM);
      }
      return $this->getUser()->hasTeledeclarationDrm();
    }

    protected function isTeledeclarationVrac() {
      if($this->isUsurpationMode()){
        return $this->getUser()->getCompte()->hasDroit(Roles::TELEDECLARATION_VRAC);
      }
      return $this->getUser()->hasTeledeclarationVrac();
    }

    protected function isTeledeclarationVracCreation() {
      if($this->isUsurpationMode()){
        return $this->getUser()->getCompte()->hasDroit(Roles::TELEDECLARATION_VRAC_CREATION);
      }
      return $this->getUser()->hasTelededeclarationVracCreation();
    }

    protected function isTeledeclarationFacture() {
      if($this->isUsurpationMode()){
        return $this->getUser()->getCompte()->hasDroit(Roles::TELEDECLARATION_FACTURE);
      }
      return $this->getUser()->hasTeledeclarationFacture();
    }

    protected function isTeledeclarationPrelevement() {
      if($this->isUsurpationMode()){
        return $this->getUser()->getCompte()->hasDroit(Roles::TELEDECLARATION_PRELEVEMENT);
      }
      return $this->getUser()->hasTeledeclarationPrevelement();
    }

    protected function isAcheteurResponsable() {
        return $this->getUser()->getCompte()->getSociete()->isNegociant();
    }

    protected function isCourtierResponsable() {
        return $this->getUser()->getCompte()->getSociete()->isCourtier();
    }



    protected function initDeleteForm() {
        $this->deleteForm = new DRMDeleteForm($this->drm);
    }

    protected function isUsurpationMode() {
        return $this->getUser()->isUsurpationCompte();
    }

}

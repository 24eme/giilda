<?php

class drm_administrationComponents extends sfComponents {

  public function executeAdministration() {
      $this->drm->initReleveNonApurement();
      $this->administrationForm = new  DRMAdministrationForm($this->drm);
      if ($this->requestAdministration->isMethod(sfRequest::POST)) {
            $this->administrationForm->bind($this->requestAdministration->getParameter($this->administrationForm->getName()));
            if ($this->administrationForm->isValid()) {
                $this->administrationForm->save();
                $this->generateUrl('drm_validation', $this->administrationForm->getObject());
            }
        }
  }

}

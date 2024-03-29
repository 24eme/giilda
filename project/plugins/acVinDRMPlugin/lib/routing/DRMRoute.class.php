<?php

class DRMRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

    protected $drm = null;

    protected function getObjectForParameters($parameters) {

        sfContext::getInstance()->getResponse()->setTitle('DRM');
        $this->drm = DRMClient::getInstance()->find('DRM-' . $parameters['identifiant'] . '-' . $parameters['periode_version']);

        if (!$this->drm) {
            throw new sfError404Exception(sprintf("La DRM n'a pas été trouvée"));
        }

        $myUser = sfContext::getInstance()->getUser();
        if ($myUser->hasCredential('teledeclaration_drm') &&
                !in_array($this->drm->getEtablissement()->id_societe, $myUser->getCompte()->getSociete()->getSocietesLieesIds())) {
            throw new sfError404Exception("Vous n'avez pas le droit d'accéder à cette DRM");
        }
        $control = isset($this->options['control']) ? $this->options['control'] : array();

        if (in_array('valid', $control) && !$this->drm->isValidee()) {
            $myUser->setFlash('drm_warning', 'La DRM de '.$this->drm->getMois().'/'.$this->drm->getAnnee().' doit être validée');
            return $this->redirectHome($this->drm);
        }

        if (in_array('edition', $control) && $this->drm->isValidee()) {
            $myUser->setFlash('drm_warning', 'La DRM de '.$this->drm->getMois().'/'.$this->drm->getAnnee().' ne peut pas être éditée car elle est validé');
            return $this->redirectHome($this->drm);
        }
        sfContext::getInstance()->getResponse()->setTitle('DRM - '.$this->drm->getEtablissement()->getNom().' ('.$this->drm->getPeriode().')');

        return $this->drm;
    }
    protected function redirectHome($drm) {
        sfContext::getInstance()->getController()->redirect('@drm_etablissement?identifiant='.$drm->identifiant."&campagne=".$drm->campagne);
        exit;
    }

    protected function doConvertObjectToArray($object) {
        $parameters = array("identifiant" => $object->getIdentifiant(), "periode_version" => $object->getPeriodeAndVersion());
        return $parameters;
    }

    public function getDRMConfiguration() {

        return $this->getDRM()->getConfig();
    }

    public function getDRM() {
        if (!$this->drm) {
            $this->getObject();
        }

        return $this->drm;
    }

    public function getEtablissement() {

        return $this->getDRM()->getEtablissement();
    }

}

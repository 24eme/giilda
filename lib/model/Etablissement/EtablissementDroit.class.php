<?php

class EtablissementDroit
{
    const DROIT_DRM_PAPIER = 'drm_papier';
    const DROIT_DRM_DTI = 'drm_dti';
    const DROIT_VRAC = 'vrac';


    protected $etablissement;

    public function __construct(Etablissement $etablissement)
    {
        $this->etablissement = $etablissement;
    }

    public function has($droit) {

        return in_array($droit, $this->get());
    }

    public function get() {

        return $this->etablissement->getDroits();
    }
}
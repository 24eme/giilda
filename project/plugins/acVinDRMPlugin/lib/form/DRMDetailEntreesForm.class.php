<?php

class DRMDetailEntreesForm extends acCouchdbObjectForm {

    public function configure() {
        $configurationDetail = $this->getObject()->getParent()->getConfig();
        $certif = $this->getObject()->getParent()->getCertification()->getkey();
        $drm = $this->getObject()->getDocument();
        foreach ($configurationDetail->getEntreesSorted() as $key => $value) {
            if ($value->readable) {
                if (!$value->writable
                   || (preg_match('/AOC|IGP/', $certif) && ($key == 'declassement'))
		               ||  (preg_match('/VINSSIG/', $certif) && ($key == 'repli'))
                   ||  ($key == 'vci' && $drm->isNegoce())
                   ||  (($certif == 'AUTRES') && ($key != 'revendique'))) {
                    $this->setWidget($key, new sfWidgetFormInputFloat(array(), array('readonly' => 'readonly')));
                } else {
                    $this->setWidget($key, new sfWidgetFormInputFloat());
                }
                $this->setValidator($key, new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
            }
        }
        $this->widgetSchema->setNameFormat('drm_detail_entrees[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}

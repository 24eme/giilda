<?php

class DRMDetailSortiesForm extends acCouchdbObjectForm {

    public function configure() {
        $configurationDetail = $this->getObject()->getParent()->getConfig();
        $certif = $this->getObject()->getParent()->getCertification()->getKey();
        $drm = $this->getObject()->getDocument();
        foreach ($configurationDetail->getSortiesSorted() as $key => $value) {
            $disabled = (!preg_match('/AOC|IGP/', $certif) && ($key == 'repli'));
            if ($key == 'contrathorsinterpro' && preg_match('/(_INTERLOIRE|_VALDELOIRE)/', $certif)) {
              if($drm->isTeledeclare()){
                  if(!$drm->getEtablissement()->isNegociant()){
                      $disabled = true;
                  }
              }
            }
            if ($key == 'vrac' && !preg_match('/_INTERLOIRE/', $certif) && !preg_match('/_VALDELOIRE/', $certif)){
                $disabled = true;
            }
            if (preg_match('/VINSSIG/', $certif) && ($key == 'declassement')) {
                $disabled = true;
            }
            if (preg_match('/AUTRES/', $certif) && ($key != 'usageindustriel') && ($key != 'destructionperte') && ($key != 'manquant') && ($key != 'vracsanscontrat')) {
                $disabled = true;
            }

            if ($value->readable) {
                if (!$value->writable || $disabled) {
                    $this->setWidget($key, new sfWidgetFormInputFloat(array(), array('readonly' => 'readonly')));
                } else {
                    $this->setWidget($key, new sfWidgetFormInputFloat());
                }
                $this->setValidator($key, new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
            }
        }
        $this->widgetSchema->setNameFormat('drm_detail_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}

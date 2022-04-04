<?php

class DRMDetailSortiesForm  extends acCouchdbObjectForm {

    public function configure() {
    	$configurationDetail = $this->getObject()->getParent()->getConfig();
        $certif = $this->getObject()->getParent()->getCertification()->getKey();
        $genre = $this->getObject()->getParent()->getGenre()->getKey();
        $appellation = $this->getObject()->getParent()->getAppellation()->getkey();
        $drm = $this->getObject()->getDocument();
    	foreach ($configurationDetail->getSortiesSorted() as $key => $value) {
            $disabled = ((!preg_match('/AOC/', $certif) && ($key == 'repli'  && !preg_match("/déclassement/i", $value->getLibelle())))
                        || (preg_match('/USAGESINDUSTRIELS/', $appellation) && (!$value->restriction_lies)));
            if ($key == 'contrathorsinterpro' && preg_match('/AOC/', $certif)) {
                $disabled = true;
            }
            if ($key == 'contrat' && !preg_match('/AOC/', $certif)) {
                $disabled = true;
            }
            if ((strpos($certif, 'AUTRE') === 0 || strpos($genre, 'VCI') === 0) && strpos($certif, 'AUTRESVINS') === false && !preg_match("#/(TRANQ|EFF)/#", $this->getObject()->getHash()) && $this->getObject()->getParent()->code_douane != "BOISSONS_FERMENTEES_AUTRES" && !in_array($key, array('distillationusageindustriel', 'destructionperte', 'manquant', 'vracsanscontratsuspendu', 'lies', 'usageindustriel', 'rebeches', 'consommationfamilialedegustation', 'autre', 'repli', 'exoversutilisateurauto', 'exoversutilisateurauto', 'vci'))) {
                $disabled = true;
            }
            if ($certif == 'AUTRE' && !preg_match("#/(TRANQ|EFF)/#", $this->getObject()->getHash()) && in_array($key, array('ventefrancecrd', 'exporttaxe'))) {
                $disabled = true;
            }
            if(preg_match('/MATIERES_PREMIERES/', $this->getObject()->getParent()->code_douane) && $value->details == "ALCOOLPUR") {
                $disabled = true;
            }
            if ($key == 'vci' && $drm->isNegoce()) {
                $disabled = true;
            }
    		if ($value->readable) {
	    		if (!$value->writable || $disabled) {
	    			$this->setWidget($key, new bsWidgetFormInputFloat(array(), array('readonly' => 'readonly')));
	    		} else {
	    			$this->setWidget($key, new bsWidgetFormInputFloat());
	    		}
	    		$this->setValidator($key, new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
    		}
    	}
        $this->widgetSchema->setNameFormat('drm_detail_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}

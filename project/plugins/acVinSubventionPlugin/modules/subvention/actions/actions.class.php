<?php

class subventionActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        var_dump("ici les subventions"); exit;
    }

    public function executeCreation(sfWebRequest $request) {
        $etablissement = $this->getRoute()->getEtablissement();

        $subvention = SubventionClient::getInstance()->createOrFind($etablissement->identifiant, $request->getParameter('operation'));
        $subvention->save();
        return $this->redirect('subvention_infos', $subvention);
    }

    public function executeInfos(sfWebRequest $request) {
        $this->subvention = $this->getRoute()->getSubvention();
    }
}

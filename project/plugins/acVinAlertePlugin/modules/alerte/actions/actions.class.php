<?php

class alerteActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $search = new AlerteConsultationSearch();
        $this->page = $request->getParameter('p', 1);
        $this->consultationFilter = $this->makeParameterQuery(array('consultation' => $request->getParameter('consultation', null)));
        $this->alertesHistorique = (is_null($this->page)) ? $search->getElasticSearchDefaultResult() : $search->getElasticSearchDefaultResult(($this->page - 1) * 20, 20);
        //usort($this->alertesHistorique, array("alerteActions", "triResultElasticaAlertesDates"));

        $this->form = new AlertesConsultationForm();
        $this->dateAlerte = AlerteDateClient::getInstance()->find(AlerteDateClient::getInstance()->buildId());
        if (!$this->dateAlerte) {
            $this->dateAlerte = new AlerteDate();
        }
        $this->dateForm = new AlertesDateForm($this->dateAlerte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->dateForm->bind($request->getParameter($this->dateForm->getName()));
            if ($this->dateForm->isValid()) {
                $this->dateForm->save();
                $this->redirect('alerte');
            }
        }
        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid() && $this->form->hasFilters()) {
            $values = $this->createSearchValues($this->form);
            $search->setValues($values);
            $this->alertesHistorique = $search->getElasticSearchResult(($this->page - 1) * 20, 20);
            //     usort($this->alertesHistorique, array("alerteActions", "triResultElasticaAlertesDates"));
        }
        $this->nbResult = $search->getNbResult();
        $this->nbPage = ceil($this->nbResult / $search->getLimit());
        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesHistorique);
        $this->page = (is_null($this->page)) ? 1 : $this->page;
    }

    private function makeParameterQuery($values) {
        return urldecode(http_build_query($values));
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->alertesEtablissement = AlerteRechercheView::getInstance()->getRechercheByEtablissement($this->etablissement->identifiant);
        usort($this->alertesEtablissement, array('alerteActions', 'triResultAlertesDates'));
        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesEtablissement);
    }

    public function executeModification(sfWebRequest $request) {
        $this->alertesHistorique = AlerteHistoryView::getInstance()->getHistory();
        $this->modificationStatutForm = new AlertesStatutsModificationForm($this->alertesHistorique);
        $this->alerte = $this->getRoute()->getAlerte();
        $this->form = new AlerteModificationForm($this->alerte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdate();
                $this->redirect('alerte_modification', $this->alerte);
            }
        }
    }

    public function executeStatutsModification(sfWebRequest $request) {
        $new_statut = $request['statut_all_alertes'];
        $new_commentaire = $request['commentaire_all_alertes'];
        foreach ($request->getParameterHolder()->getAll() as $key => $param) {
            if (!strncmp($key, 'ALERTE-', strlen('ALERTE-'))) {
                AlerteClient::getInstance()->updateStatutByAlerteId($new_statut, $new_commentaire, $key);
                $etbId = AlerteClient::getInstance()->find($key)->identifiant;
            }
        }
        if (isset($request['retour']) && $request['retour'] == "etablissement") {
            $this->redirect('alerte_etablissement', array('identifiant' => $etbId));
        } else {
            $this->redirect('alerte');
        }
    }

    public function executeGenerationTotale(sfWebRequest $request) {
        $cmd_alertes = 'bash ' . sfConfig::get('sf_root_dir') . '/bin/generation_alertes.sh';
        $output = shell_exec($cmd_alertes);
        if ($output) {
            throw new sfException("La génération d'alerte est déjà en cours d'execution.");
        }
        $this->redirect('alerte');
    }

    static function triResultAlertesDates($a0, $a1) {
        $date0 = str_replace('-', '', $a0->value[AlerteRechercheView::VALUE_DATE_MODIFICATION]);
        $date1 = str_replace('-', '', $a1->value[AlerteRechercheView::VALUE_DATE_MODIFICATION]);
        if ($date0 == $date1) {
            return 0;
        }
        return ($date0 > $date1) ? -1 : +1;
    }

    private function createSearchValues($form) {
        $values = array();
        foreach ($form->getValues() as $key => $value) {
            if (!is_null($value)) {
                $values[$key] = $value;
                if ($key == 'identifiant') {
                    $values[$key] = str_replace('ETABLISSEMENT-', '', $value);
                }
            }
        }
        return $values;
    }

}

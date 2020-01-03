<?php

class sv12Actions extends sfActions {


    public function executeRedirect(sfWebRequest $request) {
        $sv12 = SV12Client::getInstance()->find($request->getParameter('identifiant_sv12'));
        $this->forward404Unless($sv12);
        return $this->redirect('sv12_visualisation', array('identifiant' => $sv12->identifiant, 'periode_version' => $sv12->getPeriodeAndVersion()));
    }


    public function executeChooseEtablissement(sfWebRequest $request) {
        $this->form = new SV12EtablissementChoiceForm('INTERPRO-inter-loire');

        $this->historySv12 = array();

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                return $this->redirect('sv12_etablissement', $this->form->getEtablissement());
            }
        }
    }

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->campagne = $request->getParameter('campagne');
        if (!$this->campagne) {
            $this->campagne = ConfigurationClient::getInstance()->getCurrentCampagne();
        }
        $this->formCampagne = new VracEtablissementCampagneForm($this->etablissement->identifiant, $this->campagne);
	if (!$this->etablissement->isNegociant())
	  throw new sfException('Seuls les négociants peuvent faire des SV12');
       // $this->periode = SV12Client::getInstance()->buildPeriode(date('Y-m-d'));
        $this->list = SV12AllView::getInstance()->getMasterByEtablissement($this->etablissement->identifiant);
        if ($request->isMethod(sfWebRequest::POST)) {
            $param = $request->getParameter($this->formCampagne->getName());
            if ($param) {
                $this->formCampagne->bind($param);
                return $this->redirect('sv12_nouvelle', array('identifiant' => $this->etablissement->getIdentifiant(), 'periode' => $this->formCampagne->getValue('campagne')));
            }
        }

    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeNouvelle(sfWebRequest $request) {

        $etbId = $request->getParameter('identifiant');
        $periode = $request->getParameter('periode');

        $sv12 = SV12Client::getInstance()->findMaster($etbId,$periode);

        if($sv12)
        {
	    throw new sfException("Une SV12 existe déjà pour cette campagne");
        }

        $sv12 = SV12Client::getInstance()->createOrFind($etbId, $periode);
        $sv12->storeContrats();
        $sv12->save();
        $this->redirect('sv12_update', $sv12);
    }

    public function executeUpdate(sfWebRequest $request) {

        $this->sv12 = $this->getRoute()->getSV12();
        $this->sv12->storeContrats();
        $this->form = new SV12UpdateForm($this->sv12);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->doUpdateObject();
                $this->sv12->save();
		if ($request->getParameter('addproduit'))
		  return $this->redirect('sv12_update_addProduit', $this->sv12);
                return $this->redirect('sv12_recapitulatif', $this->sv12);
            }
        }
    }

    public function executeImport(sfWebRequest $request) {
        $this->sv12 = $this->getRoute()->getSV12();
        $campagne = preg_replace('/-.*/', '', $this->sv12->campagne);
        $id = "SV12-".$this->sv12->identifiant."-".$campagne;
        $client = acCouchdbManager::getClient();
        $douane = $client->find($id, acCouchdbClient::HYDRATE_JSON);
        foreach ($douane->_attachments as $key => $value) {
                if (preg_match('/xls/', $key)) {
                    $valid = $key;
                    continue;
                }
        }
        if (!$valid) {
            return;
        }
        $url = $client->dsn().$client->getAttachmentUri($douane, $valid);

        $tempfname = tempnam("/tmp", "sv12xls_").".xls";
        $temp = fopen($tempfname, "w");
        fwrite($temp, file_get_contents($url));
        fclose($temp);
        exec("bash ".sfConfig::get('app_sv12_path2odgproject')."/bin/get_csv_dr_from_douane.sh $tempfname", $output);
        $sv12 = array();
        foreach($output as $line) {
            $csv = str_getcsv($line, ';');
            if ($csv[19] == '11' || $csv[19] == '10') {
                if (!isset($sv12[$csv[22]])) {
                    $sv12[$csv[22]] = array();
                }
                $sv12_hash = '/declaration/certifications/'.$csv[9].'/genres/'.$csv[10].'/appellations/'.$csv[11].'/mentions/'.$csv[12].'/lieux/'.$csv[13].'/couleurs/'.$csv[14].'/cepages/'.$csv[15];
                if (!isset($sv12[$csv[22]])) {
                    $sv12[$csv[22]] = array();
                }
                if (!isset($sv12[$csv[22]][$sv12_hash.$csv[19]])) {
                    $sv12[$csv[22]][$sv12_hash.$csv[19]] = array();
                }
                $sv12[$csv[22]][$sv12_hash.$csv[19]][] = $csv;
            }
        }
        $delete_cvi = array();
        foreach($this->sv12->contrats as $numcontrat => $contrat) {
            $cvi = $contrat->vendeur->cvi;
            $idhashtype = $contrat->produit_hash;
            if ($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS) {
                $idhashtype .= '11';
            } elseif ($contrat->type_transaction == VracClient::TYPE_TRANSACTION_RAISINS) {
                $idhashtype .= '10';
            }
            echo "$numcontrat ($cvi) - [";
            if (count($sv12[$cvi][$idhashtype]) != 1) {
                echo "] : Pas de contrat trouvé\n" ;
                continue;
            }
            $c = $sv12[$cvi][$idhashtype][0];
            echo $c[21]." - ".$contrat->volume_prop;
            echo "] ";
            echo abs(preg_replace('/,/', '.', $c[21]) - $contrat->volume_prop) / $contrat->volume_prop;
            if (abs(preg_replace('/,/', '.', $c[21]) - $contrat->volume_prop) / $contrat->volume_prop < 0.10) {
                $this->sv12->updateVolume($numcontrat, $c[21]);
                echo ": OK\n";
                unset($sv12[$cvi][$idhashtype]);
                if (!count($sv12[$cvi][$idhashtype])) {
                    unset($sv12[$cvi][$idhashtype]);
                    if (!count($sv12[$cvi])) {
                        unset($sv12[$cvi]);
                    }
                }
            }else{
                echo ": NOP\n";
            }
        }
        print_r($sv12);

        exit;
    }

    public function executeRecapitulatif(sfWebRequest $request) {
        set_time_limit(0);
        $this->sv12 = $this->getRoute()->getSV12();
	$this->validation = new SV12Validation($this->sv12);
        $this->mouvements = $this->sv12->getMouvementsCalculeByIdentifiant($this->sv12->identifiant);
        $this->sv12->updateTotaux();

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->sv12->validate();
            $this->sv12->save();
            $this->sv12->updateVracs();
            $this->redirect('sv12_visualisation', $this->sv12);
        }
    }

    public function executeVisualisation(sfWebRequest $request) {
        $this->sv12 = $this->getRoute()->getSV12();
        $this->contrats_non_saisis = $this->sv12->getContratsNonSaisis();
        $this->mouvements = SV12MouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndPeriode($this->sv12->identifiant, $this->sv12->periode);
    }

    public function executeModificative(sfWebRequest $request)
    {
        $sv12 = $this->getRoute()->getSV12();

        $sv12_rectificative = $sv12->generateModificative();
        $sv12_rectificative->save();

        return $this->redirect('sv12_update', array('identifiant' => $sv12_rectificative->identifiant, 'periode_version' => $sv12_rectificative->getPeriodeAndVersion()));
    }

    private function saveBrouillonSV12() {
        $this->sv12->saveBrouillon();
    }

    public function executeUpdateAddProduit(sfWebRequest $request)
    {
        $this->sv12 = $this->getRoute()->getSV12();
        $this->form = new SV12UpdateAddProduitForm($this->sv12);
         if ($request->isMethod(sfWebRequest::POST)) {
             $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $produit = $this->form->addProduit();
                $this->sv12->save();
                return $this->redirect('sv12_update', $this->sv12);
            }
       }
    }
}

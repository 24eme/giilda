<?php

/**
 * Model for Vrac
 *
 */
class SV12 extends BaseSV12  {

    public function constructId() {
        $this->identifiant = $this->negociant_identifiant.'-'.$this->periode;
        $this->valide->statut = SV12Client::SV12_STATUT_BROUILLON;
        $this->campagne = '2012-2013';
        $this->set('_id', 'SV12-' . $this->identifiant);
    }
    
    public function storeNegociant() {
        $nego = $this->getEtablissementObject();
        if(!$nego) return null;
        $this->negociant->nom = $nego->nom;
        $this->negociant->cvi = $nego->cvi;
        $this->negociant->num_accise = $nego->no_accises;
        $this->negociant->num_tva_intracomm = $nego->no_tva_intracommunautaire;
        $this->negociant->adresse = $nego->siege->adresse;        
        $this->negociant->commune = $nego->siege->commune;
        $this->negociant->code_postal = $nego->siege->code_postal;
    }
    
    
    public function storeContrats() {
        $contratsView = SV12Client::getInstance()->retrieveContratsByEtablissement($this->negociant_identifiant);
        foreach ($contratsView as $contratView)
        {
            $idContrat = preg_replace('/VRAC-/', '', $contratView->value[VracClient::VRAC_VIEW_NUMCONTRAT]);
            $this->updateContrat($idContrat,$contratView->value);
        }
    }
    
    public function getEtablissementObject() {
       
        return EtablissementClient::getInstance()->findByIdentifiant($this->negociant_identifiant);
    }
    
    public function update($params = array()) {
        
    }

    public function isValidee() {

        return ($this->valide->date_saisie) && ($this->valide->statut==SV12Client::SV12_STATUT_VALIDE);
    }

    public function isBrouillon() {

        return ($this->valide->date_saisie) && ($this->valide->statut==SV12Client::SV12_STATUT_BROUILLON);
    }
    
    public function updateContrat($num_contrat, $contrat) {
        $founded = false;
        foreach ($this->contrats as $c) {
            if ($c->contrat_numero == $num_contrat) {
                break;
            }
        }
        if (!$founded) {
            if (!$contrat) {
                throw new acCouchdbException(sprintf("Le Contrat \"%s\" n'existe pas!", $num_contrat));
            }
            $contratObj = new stdClass();
            $contratObj->contrat_numero = $num_contrat;
            $contratObj->contrat_type = $contrat[VracClient::VRAC_VIEW_TYPEPRODUIT];
            $contratObj->produit_libelle = ConfigurationClient::getCurrent()->get($contrat[VracClient::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce% %la%");
            $contratObj->produit_hash = $contrat[VracClient::VRAC_VIEW_PRODUIT_ID];
            $contratObj->vendeur_identifiant = $contrat[VracClient::VRAC_VIEW_VENDEUR_ID];
            $contratObj->vendeur_nom = $contrat[VracClient::VRAC_VIEW_VENDEUR_NOM];
            $contratObj->volume_prop = $contrat[VracClient::VRAC_VIEW_VOLPROP];
            $this->contrats->add($num_contrat, $contratObj);
        }
    }
    
    public function updateVolume($num_contrat,$volume) {
        $this->contrats[$num_contrat]->volume = $volume;
    }

    public function validate() {
        $this->valide->date_saisie = date('d-m-y');
        $this->valide->statut = SV12Client::SV12_STATUT_VALIDE;

        $this->generateMouvements();
    }
    
    public function saveBrouillon() {
        $this->valide->date_saisie = date('d-m-y');
        $this->valide->statut = SV12Client::SV12_STATUT_BROUILLON;
    }
    
    public function getSV12ByProduitsType() {
        $sv12ByProduitsTypes = new stdClass();
        
        $sv12ByProduitsTypes->rows = array();

        $sv12ByProduitsTypes->volume_raisins = 0;
        $sv12ByProduitsTypes->volume_mouts = 0;
        $sv12ByProduitsTypes->volume_total = 0;
        
        foreach ($this->contrats as $contrat) {
            if(isset($sv12ByProduitsTypes->rows[$contrat->produit_hash]))
            {
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_raisins += $contrat->volume;
                    $sv12ByProduitsTypes->volume_raisins+=$contrat->volume;
                }                
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->mouts += $contrat->volume;
                    $sv12ByProduitsTypes->volume_mouts+=$contrat->volume;
                }
                $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_total += $contrat->volume;
                $sv12ByProduitsTypes->volume_total+=$contrat->volume;
            }
            else
            {
                $sv12ByProduitsTypes->rows[$contrat->produit_hash] = new stdClass();
                $sv12ByProduitsTypes->rows[$contrat->produit_hash]->appellation = $contrat->produit_libelle;
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_raisins = $contrat->volume;
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_mouts = 0;
                    $sv12ByProduitsTypes->volume_raisins+=$contrat->volume;
                }
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_mouts = $contrat->volume;
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_raisins = 0;
                    $sv12ByProduitsTypes->volume_mouts+=$contrat->volume;
                }
                $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_total = $contrat->volume;
                $sv12ByProduitsTypes->volume_total+=$contrat->volume;
            }
        }
        return $sv12ByProduitsTypes;
    }

    public function getDate() {

        return date('Y-m-d');
    }

    public function clearMouvements() {
        $this->remove('mouvements');
        $this->add('mouvements');
    }
    
    public function generateMouvements() {
        $this->clearMouvements();
        $this->mouvements = $this->getMouvementsCalcule();
    }

    public function getMouvementsCalcule() {
        $mouvements = array();
        foreach($this->contrats as $contrat) {
            $mouvement = $contrat->getMouvement();
            $mouvements[$this->getDocument()->negociant_identifiant][$mouvement->getMD5Key()] = $mouvement;
        }

        return $mouvements;
    }
    
    public function getMouvementsCalculeByIdentifiant($identifiant) {
        $mouvements = $this->getMouvementsCalcule();

        return isset($mouvements[$identifiant]) ? $mouvements[$identifiant] : array();
    }

    public function findMouvement($cle){

        return $this->mouvements[$cle];
    }

    public function getMouvements() {

        return $this->_get('mouvements');
    }

}
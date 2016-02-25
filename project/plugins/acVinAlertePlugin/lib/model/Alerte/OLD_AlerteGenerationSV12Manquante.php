<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationSV12Manquante
 * @author mathurin
 */
class AlerteGenerationSV12Manquante extends AlerteGenerationSV12 {

    public function getTypeAlerte() {
        return AlerteClient::SV12_MANQUANTE;
    }

    public function creations() {
        $etablissement_rows = EtablissementAllView::getInstance()->findByInterproStatutAndFamilles('INTERPRO-declaration', EtablissementClient::STATUT_ACTIF, array(EtablissementFamilles::FAMILLE_NEGOCIANT));
        $campagnes = $this->getCampagnes();

        foreach($etablissement_rows as $etablissement_row) {
            if($etablissement_row->key[EtablissementAllView::KEY_REGION] == EtablissementClient::REGION_HORS_CVO){
                continue;
            }
            $etablissement = EtablissementClient::getInstance()->find($etablissement_row->key[EtablissementAllView::KEY_ETABLISSEMENT_ID], acCouchdbClient::HYDRATE_JSON);

            foreach($campagnes as $campagne) {
                $sv12 = SV12Client::getInstance()->findMaster(SV12Client::getInstance()->buildId($etablissement->identifiant, $campagne));
                if($sv12) {

                    continue;
                }

                $alerte = $this->createOrFindBySV12($this->buildSV12Manquante($etablissement, $campagne));
                $alerte->type_relance = $this->getTypeRelance();
                if(!($alerte->isNew() || $alerte->isClosed())) {
                
                    continue;
                }
                $alerte->open($this->getDate());
                $alerte->save();
            }
        }
    }

    public function updates() {
        foreach ($this->getAlertesOpen() as $alerteView) {
            $alerte = AlerteClient::getInstance()->find($alerteView->id);
            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
            $sv12 = SV12Client::getInstance()->findMaster($id_document);
            if(!$sv12)  {
                $relance = Date::supEqual($this->getDate(), $alerte->date_relance);
                if ($relance) {
                    $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, null, $this->getDate());
                    $alerte->save();
                }
                continue;
            } 

            $alerte->updateStatut(AlerteClient::STATUT_FERME, AlerteClient::MESSAGE_AUTO_FERME, $this->getDate());
            $alerte->save();
        }
    }



    protected function getCampagnes() {
        $nb_campagne = $this->getConfig()->getOption('nb_campagne');

        $campagne = ConfigurationClient::getInstance()->getCurrentCampagne();
        $campagnes = array();

        for($i=$nb_campagne-1;$i>=0;$i--) {
            preg_match('/([0-9]{4})-([0-9]{4})/', $campagne, $annees);
            $campagnes[] = sprintf("%s-%s", $annees[1]-$i, $annees[2]-$i);
        }

        return $campagnes;
    }

    protected function buildSV12Manquante($etablissement, $campagne) {
        $id = SV12Client::getInstance()->buildId($etablissement->identifiant, $campagne);
        $sv12_manquante = new stdClass();

        $sv12_manquante->identifiant = $etablissement->identifiant;
        $sv12_manquante->periode = $campagne;
        $sv12_manquante->campagne = $campagne;
        $sv12_manquante->version = null;
        $sv12_manquante->declarant = new stdClass();
        $sv12_manquante->declarant->region = $etablissement->region;
        $sv12_manquante->declarant->nom = $etablissement->nom;
        $sv12_manquante->_id = $id;

        return $sv12_manquante;
    }

    public function creationsByDocumentsIds(array $documents_id,$document_type) {
        
    }

    public function execute() {
        $this->updates();
        $this->creations();
    }

    public function isInAlerte($document) {
        
    }

    public function updatesByDocumentsIds(array $documents_id,$document_type) {
        
    }

      public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_DECLARATIVE;
    }
}
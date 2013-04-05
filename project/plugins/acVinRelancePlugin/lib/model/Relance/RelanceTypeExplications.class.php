<?php
/**
 * Model for RelanceTypeExplications
 *
 */

class RelanceTypeExplications extends BaseRelanceTypeExplications {
    
    
     public function storeVerificationForAlerte($alerte){
        sfApplicationConfiguration::getActive()->loadHelpers('Float');
        sfApplicationConfiguration::getActive()->loadHelpers('Date');
        $this->origine_identifiant = $alerte->value[AlerteRechercheView::VALUE_ID_DOC];
        $this->origine_libelle = $alerte->value[AlerteRechercheView::VALUE_LIBELLE_DOCUMENT];        
        $this->origine_date = date('Y-m-d');
        $this->alerte_identifiant = $alerte->id;
        $type_alerte = $alerte->key[AlerteRelanceView::KEY_TYPE_ALERTE];
        switch ($type_alerte) {
            case AlerteClient::VRAC_NON_SOLDES:
                $doc = VracClient::getInstance()->find($alerte->value[AlerteRelanceView::VALUE_ID_DOC]);
                $acheteur = $doc->getAcheteurObject();
                $coordonneesAcheteur = $acheteur->raison_sociale.' '.$acheteur->getSiegeAdresses();
                $volume_enleve = ($doc->volume_enleve)? sprintFloatFr($doc->volume_enleve) : sprintFloatFr(0);
                $this->explications = $doc->numero_contrat.'|'.$doc->date_signature.'|'.$coordonneesAcheteur.'|'.sprintFloatFr($doc->volume_propose).'|'.$volume_enleve.'|'.$doc->commentaire;
                break;
            case AlerteClient::VRAC_ATTENTE_ORIGINAL:   
            case AlerteClient::VRAC_PRIX_DEFINITIFS:  
                $doc = VracClient::getInstance()->find($alerte->value[AlerteRelanceView::VALUE_ID_DOC]);
                $vendeur = $doc->getVendeurObject();
                $coordonneesVendeur = $vendeur->raison_sociale.' '.$vendeur->getSiegeAdresses();
                $this->explications = $doc->numero_contrat.'|'.$doc->date_signature.'|'.$coordonneesVendeur.'|'.sprintFloatFr($doc->volume_propose);
                break;
            case AlerteClient::DS_NON_VALIDEE:
                $doc = DSClient::getInstance()->find($alerte->value[AlerteRelanceView::VALUE_ID_DOC]);
                $this->explications = format_date($doc->date_stock,'dd/MM/yyyy');
                break;
            case AlerteClient::DRM_MANQUANTE:
            case AlerteClient::DRA_MANQUANTE:
                $id = $alerte->value[AlerteRelanceView::VALUE_ID_DOC];
                if(preg_match('/^DRM-([0-9]{8})-([0-9]{6})*/', $id, $matches)){
                        $periode = $matches[2];
                    }
                if(!$periode) throw new sfException("La periode de l'alerte $alerte->id ne peut être identifiée.");
                $doc = DRMClient::getInstance()->find($alerte->value[AlerteRelanceView::VALUE_ID_DOC]);
                $this->explications = ConfigurationClient::getInstance()->getPeriodeLibelle($periode);
                break;
           case AlerteClient::SV12_MANQUANTE:               
                break;            
           case AlerteClient::VRAC_SANS_SV12:
               $etbId = $alerte->key[AlerteRelanceView::KEY_IDENTIFIANT_ETB];
               $campagne = $alerte->key[AlerteRelanceView::KEY_CAMPAGNE];
               $contats_mouts = VracClient::getInstance()->retrieveBySoussigneStatutAndType($etbId,$campagne,VracClient::STATUS_CONTRAT_NONSOLDE,VracClient::TYPE_TRANSACTION_MOUTS,null)->rows;
               $contats_raisins = VracClient::getInstance()->retrieveBySoussigneStatutAndType($etbId,$campagne,VracClient::STATUS_CONTRAT_NONSOLDE,VracClient::TYPE_TRANSACTION_RAISINS,null)->rows;
               $vracs = array_merge($contats_mouts, $contats_raisins);
               $this->explications = "";
               foreach ($vracs as $key => $vrac) {
                  $this->explications .= $vrac->value[VracClient::VRAC_VIEW_NUMCONTRAT];
                  if($key < count($vracs) -1) $this->explications .= ", ";
               }
               break;
            default:
                throw new sfException("L'alerte $alerte->id de type $type_alerte n'a pas été trouvée.");
                break;
        }
    }
    }
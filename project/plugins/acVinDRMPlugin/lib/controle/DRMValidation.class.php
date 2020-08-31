<?php

class DRMValidation extends DocumentValidation {

    protected $isTeledeclarationDrm = false;

    public function __construct($document, $isTeledeclarationDrm = false, $options = null) {
        $this->isTeledeclarationDrm = $isTeledeclarationDrm;
        parent::__construct($document, $options);
    }

    public function configure($isTeledeclarationDrm = false) {
        $this->addControle('erreur', 'repli', "La somme des replis en entrée et en sortie n'est pas la même");
        $this->addControle('erreur', 'declassement', "La somme des déclassements en entrée et en sortie n'est pas la même");
        $this->addControle('erreur', 'regime_crd', "Le régime CRD n'a pas été rempli");
        $this->addControle('erreur', 'vrac_detail_exist', "Le contrat n'existe plus");
        if (!$this->isTeledeclarationDrm) {
            $this->addControle('erreur', 'vrac_detail_nonsolde', "Le contrat est soldé (ou annulé)");
        }else{
           $this->addControle('erreur', 'replacement_date_manquante', "Les dates de sorties des produits en replacement sont obligatoires");
           $this->addControle('erreur', 'vrac_vendeur_correct', "Le contrat identifié n'est pas le bon contrat");
           $this->addControle('erreur', 'vrac_produit_correct', "Le contrat identifié ne possède pas le même produit");
           $this->addControle('erreur', 'vrac_type_correct', "Le contrat identifié n'est pas un contrat de raisin ou de moût");
           $this->addControle('erreur', 'no_accises_absent', "Le numéro d'accise n'a pas été renseigné");
        }
        $this->addControle('erreur', 'total_negatif', "Le stock revendiqué théorique fin de mois est négatif");
        $this->addControle('vigilance', 'vrac_detail_negatif', "Le volume qui sera enlevé sur le contrat est supérieur au volume restant");
        $this->addControle('vigilance', 'crd_negatif', "Le nombre de CRD ne dois pas être négatif");
        $this->addControle('vigilance', 'documents_annexes_absents', "Les numéros de document sont mal renseignés.");
        $this->addControle('vigilance', 'siret_absent', "Le numéro de siret n'a pas été renseigné");
        $this->addControle('vigilance', 'produits_absent', "Il n'y a pas de produit dans la DRM");

        $this->addControle('vigilance', 'observations', "Les observations n'ont pas été toutes renseignées");

        $this->addControle('vigilance', 'reintegration', 'La date de réintégration ne peut pas être supérieur à la période de la DRM');
    }

    public function controle() {
        $total_entrees_replis = 0;
        $total_sorties_replis = 0;
        $total_entrees_declassement = 0;
        $total_sorties_declassement = 0;

        $total_mouvement_absolu = 0;
        $drmTeledeclaree = $this->document->exist('teledeclare') && $this->document->teledeclare;

        foreach ($this->document->getProduitsDetails() as $detail) {

            $total_mouvement_absolu += $detail->total_entrees + $detail->total_sorties;

            $total_entrees_replis += (!$detail->getConfig()->entrees->exist('repli'))? 0.0 : $detail->entrees->repli;
            $total_sorties_replis += (!$detail->getConfig()->sorties->exist('repli'))? 0.0 : $detail->sorties->repli;

            $total_entrees_declassement += (!$detail->getConfig()->entrees->exist('declassement'))? 0.0 : $detail->entrees->declassement;
            $total_sorties_declassement += (!$detail->getConfig()->sorties->exist('declassement'))? 0.0 : $detail->sorties->declassement;

            $entrees_excedents = ($detail->entrees->exist('excedents'))? $detail->entrees->excedents : 0.0;
            $entrees_manipulation = ($detail->entrees->exist('manipulation'))? $detail->entrees->manipulation : 0.0;
            $sorties_destructionperte = ($detail->sorties->exist('destructionperte'))? $detail->sorties->destructionperte : 0.0;
            $total_observations_obligatoires = $entrees_excedents + $entrees_manipulation + $sorties_destructionperte;
            if($total_observations_obligatoires && (!$detail->exist('observations') || !$detail->observations))
            {
              $this->addPoint('vigilance', 'observations', "Entrée excédents (".sprintf("%.2f",$entrees_excedents)." hl), manipulation (".sprintf("%.2f",$entrees_manipulation)." hl), sortie manquant (".sprintf("%.2f",$sorties_destructionperte).") pour le produit ".$detail->getLibelle(), $this->generateUrl('drm_annexes', $this->document));
            } elseif($detail->exist('observations') && !$detail->observations) {
                $this->addPoint('vigilance', 'observations', "Les mouvements du produit ".$detail->getLibelle()." nécessitent une observation", $this->generateUrl('drm_annexes', $this->document));
            }

            if ($detail->total < 0) {
                $this->addPoint('erreur', 'total_negatif', $detail->getLibelle(), $this->generateUrl('drm_edition_detail', $detail));
            }
        }

        $volumes_restant = array();
        if (!$this->isTeledeclarationDrm) {
            foreach ($this->document->getMouvementsCalculeByIdentifiant($this->document->identifiant, $this->isTeledeclarationDrm) as $mouvement) {
                if ($mouvement->isVrac()) {
                    $vrac = $mouvement->getVrac();
                    if (!$vrac) {
                        $this->addPoint('erreur', 'vrac_detail_exist', sprintf("%s, Contrat n°%s avec %s", $mouvement->produit_libelle, $mouvement->detail_libelle, $mouvement->vrac_destinataire), $this->generateUrl('drm_edition_detail', $detail));
                        continue;
                    }

                    if ($vrac->valide->statut != VracClient::STATUS_CONTRAT_NONSOLDE) {
                        $this->addPoint('erreur', 'vrac_detail_nonsolde', sprintf("Contrat %s", $mouvement->produit_libelle, $vrac->__toString()), $this->generateUrl('vrac_visualisation', $vrac));
                        continue;
                    }
                    $id_volume_restant = $mouvement->produit_hash . $mouvement->vrac_numero;
                    if (!isset($volumes_restant[$id_volume_restant])) {
                        $volumes_restant[$id_volume_restant]['volume'] = $vrac->volume_propose - $vrac->volume_enleve;
                        $volumes_restant[$id_volume_restant]['vrac'] = $vrac;
                    }
                    $volumes_restant[$id_volume_restant]['volume'] += $mouvement->volume;
                }
            }
        }
        foreach ($volumes_restant as $is => $restant) {
            if ($restant['volume'] < 0) {
                $vrac = $restant['vrac'];
                $this->addPoint('vigilance', 'vrac_detail_negatif', sprintf("%s, Contrat %s (%01.02f hl enlevé / %01.02f hl proposé)", $volumes_restant[$id_volume_restant]['vrac']->produit_libelle, $vrac->__toString(), $vrac->volume_propose - $restant['volume'], $vrac->volume_propose), $this->generateUrl('drm_edition', $this->document));
            }
        }
        if (round($total_entrees_replis, 2) != round($total_sorties_replis, 2)) {
            $this->addPoint('erreur', 'repli', sprintf("%s  (+%.2fhl / -%.2fhl)", 'revenir aux mouvements', round($total_entrees_replis, 2), round($total_sorties_replis, 2)), $this->generateUrl('drm_edition', $this->document));
        }
        if ($this->isTeledeclarationDrm) {
            if (round($total_entrees_declassement, 2) != round($total_sorties_declassement, 2)) {
                $this->addPoint('erreur', 'declassement', sprintf("%s  (+%.2fhl / -%.2fhl)", 'revenir aux mouvements', round($total_entrees_declassement, 2), round($total_sorties_declassement, 2)), $this->generateUrl('drm_edition', $this->document));
            }
        }
        if ($this->isTeledeclarationDrm) {

            if ($total_mouvement_absolu && (!$this->document->getEtablissement()->exist('crd_regime') || !$this->document->getEtablissement()->get('crd_regime'))) {
                $this->addPoint('erreur', 'regime_crd', "vous pouvez l'indiquer dans l'écran CRD", $this->generateUrl('drm_crd', $this->document));
            }

            foreach ($this->document->getAllCrdsByRegimeAndByGenre() as $regime => $crdByRegime) {
                foreach ($crdByRegime as $genre => $crds) {
                    foreach ($crds as $type_crd => $crd) {
                        if (!is_null($crd->stock_fin) && $crd->stock_fin < 0) {
                            $genreLibelle = ($genre == 'TRANQ') ? 'TRANQUILLE' : $genre;
                            $this->addPoint('vigilance', 'crd_negatif', $crd->getLibelle() . ' (' . $genreLibelle . ')', $this->generateUrl('drm_crd', $this->document));
                        }
                    }
                }
            }

            if (!$this->document->societe->siret) {
                $this->addPoint('vigilance', 'siret_absent', 'Veuillez enregistrer votre siret', $this->generateUrl('drm_validation_update_societe', $this->document));
            }
            if (!$this->document->declarant->no_accises) {
                $this->addPoint('erreur', 'no_accises_absent', 'Veuillez enregistrer votre numéro d\'accise', $this->generateUrl('drm_validation_update_etablissement', $this->document));
            }

            $societe = $this->document->getEtablissement()->getSociete();

            foreach ($this->document->getProduitsDetails($drmTeledeclaree,'details') as $detail) {
                if((($detail->entrees->exist('retourmarchandisesanscvo') && $detail->entrees->retourmarchandisesanscvo)
                    || ($detail->entrees->exist('retourmarchandisetaxees') && $detail->entrees->retourmarchandisetaxees)
                    || ($detail->entrees->exist('retourmarchandisetaxeesacquitte') && $detail->entrees->retourmarchandisetaxeesacquitte)
                    || ($detail->entrees->exist('retourmarchandisenontaxees') && $detail->entrees->retourmarchandisenontaxees)
                    || ($detail->entrees->exist('transfertcomptamatierecession') && $detail->entrees->transfertcomptamatierecession)) && (!$detail->exist('replacement_date') || !$detail->replacement_date)){
                    $this->addPoint('erreur', 'replacement_date_manquante', 'retour aux annexes', $this->generateUrl('drm_annexes', $this->document));
                    break;
                }

                if ($detail->exist('replacement_date') && $detail->replacement_date) {
                    $date_replacement = date_create_from_format('d/m/Y', $detail->replacement_date);
                    $date_debut_periode = date_create_from_format('Ym', $this->document->periode)->modify('last day of this month');

                    if ($date_debut_periode->format('Ymd') < $date_replacement->format('Ymd')) {
                        $this->addPoint('vigilance', 'reintegration', 'retour aux annexes', $this->generateUrl('drm_annexes', $this->document));
                    }
                }
            }
        }

        $sortiesDocAnnexes = array();
        $vrac_liste = array();
        foreach ($this->document->getProduitsDetails($drmTeledeclaree,'details') as $detail) {
            if (count($detail->sorties->export_details)) {
                foreach ($detail->sorties->export_details as $paysCode => $export) {
                    if ($export->numero_document) {
                        $sortiesDocAnnexes[$export->type_document] = $export->numero_document;
                    }
                }
            }
            if (count($detail->sorties->vrac_details)) {
                foreach ($detail->sorties->vrac_details as $num_vrac => $vrac) {
                    $vrac_liste[$num_vrac] = $vrac;
                    if ($vrac->numero_document) {
                        $sortiesDocAnnexes[$vrac->type_document] = $vrac->numero_document;
                    }
                }
            }
        }
        foreach ($sortiesDocAnnexes as $type_doc => $num) {

            if (!$this->document->exist('documents_annexes') || !count($this->document->documents_annexes)) {
                $this->addPoint('vigilance', 'documents_annexes_absents', 'retour aux annexes', $this->generateUrl('drm_annexes', $this->document));
                break;
            }
            $doc_annexe = $this->document->documents_annexes;
            foreach (array_keys(DRMClient::$drm_documents_daccompagnement) as $document_accompagnement_type) {

                if (($type_doc == $document_accompagnement_type) &&
                        ((!$doc_annexe->exist($document_accompagnement_type)) || (!$doc_annexe->$document_accompagnement_type->fin) || (!$doc_annexe->$document_accompagnement_type->debut)
                        )) {
                    $this->addPoint('vigilance', 'documents_annexes_absents', 'retour aux annexes', $this->generateUrl('drm_annexes', $this->document));
                }
            }
        }
      if ($this->isTeledeclarationDrm) {
        foreach ($vrac_liste as $idVrac => $vracNode) {
          $vracDoc = VracClient::getInstance()->find($idVrac);
          if (!$vracDoc) {
              $this->addPoint('erreur', 'vrac_detail_exist', $idVrac);
          }else {
            if($vracDoc->getVendeurIdentifiant() != $vracNode->getDocument()->getIdentifiant()){
              $this->addPoint('erreur', 'vrac_vendeur_correct', $detail->getLibelle(), $this->generateUrl('drm_edition',$this->document));
            }
            if($vracNode->getProduitDetail()->getCepage()->getHash() != $vracDoc->getConfigProduit()->getHash()){
              $this->addPoint('erreur', 'vrac_produit_correct', $detail->getLibelle(), $this->generateUrl('drm_edition',$this->document));
            }
          }
          $isRaisinMout = (($vracDoc->type_transaction == VracClient::TYPE_TRANSACTION_RAISINS) ||
                  ($vracDoc->type_transaction == VracClient::TYPE_TRANSACTION_MOUTS));
          if($isRaisinMout){
            $this->addPoint('erreur', 'vrac_type_correct', $detail->getLibelle(), $this->generateUrl('drm_edition',$this->document));
          }
        }
      }

        if (count($this->document->getProduits()) === 0) {
            $this->addPoint('vigilance', 'produits_absent', 'Retour aux produits', $this->generateUrl('drm_choix_produit', $this->document));
        }
    }

}

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
        if (!$this->isTeledeclarationDrm) {
            $this->addControle('erreur', 'vrac_detail_nonsolde', "Le contrat est soldé (ou annulé)");
            $this->addControle('erreur', 'vrac_detail_exist', "Le contrat n'existe plus");
        }
        $this->addControle('vigilance', 'total_negatif', "Le stock revendiqué théorique fin de mois est négatif");
        $this->addControle('vigilance', 'vrac_detail_negatif', "Le volume qui sera enlevé sur le contrat est supérieur au volume restant");
        $this->addControle('vigilance', 'crd_negatif', "Le nombre de CRD ne dois pas être négatif");
        $this->addControle('vigilance', 'documents_annexes_absents', "Les numéros de document sont mal renseignés.");
        $this->addControle('vigilance', 'siret_absent', "Le numéro de siret n'a pas été renseigné");
        $this->addControle('vigilance', 'no_accises_absent', "Le numéro d'accise n'a pas été renseigné");
        $this->addControle('vigilance', 'caution_absent', "Le type de caution n'a pas été renseigné");
        $this->addControle('vigilance', 'observations', "Les observations n'ont pas été renseignées");
    }

    public function controle() {
        $total_entrees_replis = 0;
        $total_sorties_replis = 0;
        $total_entrees_declassement = 0;
        $total_sorties_declassement = 0;
        $total_entrees_excedents = 0;
         $total_entrees_manipulation = 0;
         $total_sorties_destructionperte = 0;

        $total_mouvement_absolu = 0;

        foreach ($this->document->getProduitsDetails() as $detail) {

            $total_mouvement_absolu += $detail->total_entrees + $detail->total_sorties;

            if (!$detail->getConfig()->entrees->exist('declassement')) {
                break;
            }
            $total_entrees_replis += $detail->entrees->repli;
            $total_sorties_replis += $detail->sorties->repli;

            $total_entrees_declassement += $detail->entrees->declassement;
            $total_sorties_declassement += $detail->sorties->declassement;

            $total_entrees_excedents += ($detail->entrees->exist('excedents'))? $detail->entrees->excedents : 0;
            $total_entrees_manipulation += ($detail->entrees->exist('manipulation'))? $detail->entrees->manipulation : 0;
            
            $total_sorties_destructionperte += ($detail->sorties->exist('destructionperte'))? $detail->sorties->destructionperte : 0;

            if ($detail->total < 0) {
                $this->addPoint('vigilance', 'total_negatif', $detail->getLibelle(), $this->generateUrl('drm_edition_detail', $detail));
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
                $this->addPoint('vigilance', 'no_accises_absent', 'Veuillez enregistrer votre numéro d\'accise', $this->generateUrl('drm_validation_update_etablissement', $this->document));
            }
            if (!$this->document->declarant->caution) {
                $this->addPoint('vigilance', 'caution_absent', 'Veuillez enregistrer votre type de caution', $this->generateUrl('drm_validation_update_etablissement', $this->document));
            }

            if (!$this->document->observations && 
                    (($total_entrees_excedents > 0)
                    || ($total_entrees_manipulation > 0)
                    || ($total_sorties_destructionperte > 0))) {
                $this->addPoint('vigilance', 'observations', "Entrée excédents (".$total_entrees_excedents." hl), manipulation (".$total_entrees_manipulation." hl), sortie manquant (".$total_sorties_destructionperte.")", $this->generateUrl('drm_annexes', $this->document));
            }
        }

        $sortiesDocAnnexes = array();
        foreach ($this->document->getProduitsDetails() as $detail) {
            if (count($detail->sorties->export_details)) {
                foreach ($detail->sorties->export_details as $paysCode => $export) {
                    if ($export->numero_document) {
                        $sortiesDocAnnexes[$export->type_document] = $export->numero_document;
                    }
                }
            }
            if (count($detail->sorties->vrac_details)) {
                foreach ($detail->sorties->vrac_details as $num_vrac => $vrac) {
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
    }

}

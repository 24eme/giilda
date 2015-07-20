<?php

class DRMValidation extends DocumentValidation {

    protected $isTeledeclarationDrm = false;

    public function __construct($document, $isTeledeclarationDrm = false, $options = null) {
        $this->isTeledeclarationDrm = $isTeledeclarationDrm;
        parent::__construct($document, $options);
    }

    public function configure($isTeledeclarationDrm = false) {
        if (!$this->isTeledeclarationDrm) {
            $this->addControle('erreur', 'repli', "La somme des replis en entrée et en sortie n'est pas la même");
            $this->addControle('erreur', 'vrac_detail_nonsolde', "Le contrat est soldé (ou annulé)");
            $this->addControle('erreur', 'vrac_detail_exist', "Le contrat n'existe plus");
        }
        $this->addControle('vigilance', 'total_negatif', "Le stock revendiqué théorique fin de mois est négatif");
        $this->addControle('vigilance', 'vrac_detail_negatif', "Le volume qui sera enlevé sur le contrat est supérieur au volume restant");
        //     $this->addControle('vigilance', 'total_crd_incoherent', "Le volume de sortie bouteilles est différent de celui des CRDs sorties.");
        $this->addControle('vigilance', 'documents_annexes_absents', "Les numéros de document sont mal renseignés.");
    }

    public function controle() {
        $total_entrees_replis = 0;
        $total_sorties_replis = 0;

        foreach ($this->document->getProduitsDetails() as $detail) {
            $total_entrees_replis += $detail->entrees->repli;
            $total_sorties_replis += $detail->sorties->repli;

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
        if (!$this->isTeledeclarationDrm) {
            if (round($total_entrees_replis, 2) != round($total_sorties_replis, 2)) {
                $this->addPoint('erreur', 'repli', $detail->getLibelle(), $this->generateUrl('drm_edition', $this->document));
            }
        }
        /*
          if (false && $this->isTeledeclarationDrm) {
          $total_sorties_bouteilles = 0;
          foreach ($this->document->getProduitsDetails() as $detail) {
          $total_sorties_bouteilles += $detail->sorties->bouteille;
          }
          $total_sorties_crds = 0;
          foreach ($this->document->getAllCrds() as $crd) {
          $total_sorties_crds += $crd->sorties * $crd->centilitrage;
          }

          if ($total_sorties_crds != $total_sorties_bouteilles) {
          $this->addPoint('vigilance', 'total_crd_incoherent', $total_sorties_bouteilles . 'Hl de sortie bouteilles contre ' . $total_sorties_crds . 'Hl CRD', $this->generateUrl('drm_crd', $this->document));
          }
          } */
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
                $this->addPoint('vigilance', 'documents_annexes_absents', $detail->getLibelle(), $this->generateUrl('drm_annexes', $this->document));
            }
            $doc_annexe = $this->document->documents_annexes;
       //     if($doc_annexe)
        }
    }

}

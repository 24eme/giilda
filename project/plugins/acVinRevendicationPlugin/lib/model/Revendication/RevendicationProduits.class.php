<?php

/**
 * Model for RevendicationProduits
 *
 */
class RevendicationProduits extends BaseRevendicationProduits {

    const STATUT_SUPPRIME = 'supprime';
    const STATUT_MODIFIE = 'modifie';
    const STATUT_IMPORTE = 'importe';

    public function storeProduit($num_ligne, $row, $hashLibelle, $bailleur) {
        $this->libelle_produit_csv = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        $this->produit_hash = $hashLibelle[0];
        $this->produit_libelle = $hashLibelle[1];
        $this->date_certification = $row[RevendicationCsvFile::CSV_COL_DATE];
        $this->statut = self::STATUT_IMPORTE;
        if (!array_key_exists($row[RevendicationCsvFile::CSV_COL_NUMERO_CA], $this->volumes)) {
            $volumes = $this->volumes->add($row[RevendicationCsvFile::CSV_COL_NUMERO_CA]);
            $volumes->volume = floatval($row[RevendicationCsvFile::CSV_COL_VOLUME]);
            $volumes->num_ligne = $num_ligne;
            if ($bailleur) {
                $volumes->bailleur_identifiant = $bailleur->key[EtablissementAllView::KEY_IDENTIFIANT];
                $volumes->bailleur_nom = $bailleur->key[EtablissementAllView::KEY_NOM];

                }
        }
    }

    public function updateProduit($hash, $libelle) {
        $this->produit_hash = $hash;
        $this->produit_libelle = $libelle;
        $this->statut = self::STATUT_MODIFIE;
    }

    public function supprProduit() {
        $this->statut = self::STATUT_SUPPRIME;
    }

}
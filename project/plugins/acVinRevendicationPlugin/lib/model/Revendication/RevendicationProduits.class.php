<?php

/**
 * Model for RevendicationProduits
 *
 */
class RevendicationProduits extends BaseRevendicationProduits {

    const STATUT_SUPPRIME = 'SUPPRIME';
    const STATUT_MODIFIE = 'MODIFIE';
    const STATUT_IMPORTE = 'IMPORTE';
    const STATUT_SAISIE = 'SAISIE';
    const KEY_SAISIE = 'SAISIE';

    public function storeProduit($num_ligne, $row, $hashLibelle, $bailleur) {
        $this->libelle_produit_csv = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        $this->produit_hash = $hashLibelle[0];
        $this->produit_libelle = $hashLibelle[1];
        $this->date_certification = $row[RevendicationCsvFile::CSV_COL_DATE];
        if (!array_key_exists($row[RevendicationCsvFile::CSV_COL_NUMERO_CA], $this->volumes)) {
            $volumes = $this->volumes->add($row[RevendicationCsvFile::CSV_COL_NUMERO_CA]);
            $volumes->volume = floatval($row[RevendicationCsvFile::CSV_COL_VOLUME]);
            $volumes->num_ligne = $num_ligne;
            $volumes->statut = self::STATUT_IMPORTE;
            $volumes->date_certification = $row[RevendicationCsvFile::CSV_COL_DATE];
            $volumes->numero_certification = $row[RevendicationCsvFile::CSV_COL_NUMERO_CA];
            $volumes->ligne = implode('#', $row);
            if ($bailleur) {
                $volumes->bailleur_identifiant = $bailleur->identifiant;
                $volumes->bailleur_nom = $bailleur->nom;
            }
        }
    }

    public function addVolumeSaisi($volume, $date = null) {
        if(is_null($date)) {
            $date = date('Y-m-d');
        }

        $item_volume = $this->volumes->add(self::KEY_SAISIE);
        $item_volume->volume += round($volume * 1, 2);
        $item_volume->statut = self::STATUT_SAISIE;
        $item_volume->date_certification = $date;

        return $item_volume;
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

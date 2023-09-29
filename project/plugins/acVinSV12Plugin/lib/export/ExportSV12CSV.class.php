<?php

class ExportSV12CSV {

    protected $sv12 = null;
    protected $header = false;

    protected $floatHelper = null;

    public function __construct($doc_or_id, $header = true) {
        if ($doc_or_id instanceof SV12) {
            $this->sv12 = $doc_or_id;
        } else {
            $this->sv12 = SV12Client::getInstance()->find($doc_or_id);
        }

        if (!$this->sv12) {
            echo sprintf("WARNING;Le document n'existe pas %s\n", $doc_or_id);
            return;
        }
       $this->floatHelper = FloatHelper::getInstance();

        $this->header = $header;
    }

    public static function getHeaderCsv() {
        return "Campagne;Identifiant;Raison Sociale;CVI;Code Postal;Commune;Certification;Genre;Appellation;Mention;Lieu;Couleur;Cepage;Libelle Produit;Volume;ID Vendeur;Vendeur;SV12 doc ID;Labels\n";
    }

    public function export() {
        if($this->header) {

            $csv .= $this->getHeaderCsv();
        }

        $csv .= $this->exportSV12();

        return $csv;
    }


    public function exportSV12($interpro = null) {
        $csv = '';
        foreach ($this->sv12->contrats as $contrat) {
          if ($interpro && $interpro != $this->getInterproFromMvts($contrat->produit_hash)) continue;
          $csv .= $this->sv12->campagne.";";
          $csv .= $this->sv12->identifiant.";";
          $csv .= $this->sv12->declarant->raison_sociale.";";
          $csv .= $this->sv12->declarant->cvi.";";
          $csv .= $this->sv12->declarant->code_postal.";";
          $csv .= $this->sv12->declarant->commune.";";
          $csv .= $this->getProduitDefinition($contrat->produit_hash).";";
          $csv .= (strpos($contrat->produit_libelle, ' ') !== false)? trim(substr($contrat->produit_libelle, strpos($contrat->produit_libelle, ' '))).";" : trim($contrat->produit_libelle).";";
          $csv .= $contrat->volume.';';
          $csv .= $contrat->vendeur_identifiant.';';
          $csv .= $contrat->vendeur_nom.';';
          $csv .= $this->sv12->_id.';';
          $csv .= ($contrat->exist('labels'))? implode('|', $contrat->labels->toArray(true,false)) : null;
          $csv .= "\n";
        }
        return $csv;
    }

    public function getProduitDefinition($hash) {
        $definitions = explode('/', $hash);
        if (count($definitions) != 16) {
            throw new sfException("Hash ($hash) non valide");
        }
        return str_replace('DEFAUT', '', $definitions[3].';'.$definitions[5].';'.$definitions[7].';'.$definitions[9].';'.$definitions[11].';'.$definitions[13].';'.$definitions[15]);
    }

    public function getInterproFromMvts($hash) {
        foreach ($this->sv12->mouvements as $identifiant => $mouvements) {
            foreach ($mouvements as $mouvement) {
                if ($mouvement->produit_hash == $hash) {
                    return $mouvement->getOrAdd('interpro');
                }
            }
        }
        return null;
    }

}

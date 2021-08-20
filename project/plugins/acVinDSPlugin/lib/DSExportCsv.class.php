<?php
class DSExportCsv {

    protected static $_instance;

    public function __construct() {}

    public static function getInstance()
    {
        if ( ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function getHeaderEdi() {
        return "#Type;Campagne;Identifiant du déclarant;raison sociale du déclarant;CVI du déclarant;Accises du déclarant;Adresse du déclarant;Code postal du déclarant;Commune du déclarant;Famille du déclarant;Sous famille du déclarant;Certification;Genre;Appellation;Mention;Lieu;Couleur;Cépages;Détail;Libellé;Complément;Millésime;Stock courant;Vrac libre courant;Stock antérieur;Vrac libre antérieur;Télédéclaré;Version;Referente;Date de validation;ID doc;ID DRM reprise\n";
    }

    public function exportAll($header = true) {
    	$csv = array();
    	$docs = DSClient::getInstance()->findAll();
    	foreach($docs as $doc) {
        if (!$doc->valide->date_signee)
          continue;
    		$csv[] = $this->exportDS($doc);
    	}
    	return ($header)? $this->getHeaderEdi().implode("", $csv) : implode("", $csv);
    }

    public function exportDS($ds) {
        $csv = '';
        foreach($ds->declaration as $hash => $produit) {
          $produitTab = explode('/', $hash);
          foreach($produit->detail as $key => $stocks) {
            $csv .= $ds->type.";".
            $ds->campagne.";".
            $ds->identifiant.";".
            $ds->declarant->raison_sociale.";".
            $ds->declarant->cvi.";".
            $ds->declarant->no_accises.";".
            $ds->declarant->adresse.";".
            $ds->declarant->code_postal.";".
            $ds->declarant->commune.";".
            $ds->declarant->famille.";".
            $ds->declarant->sous_famille.";".
            $this->cleanDefaut($produitTab[1]).";".
            $this->cleanDefaut($produitTab[3]).";".
            $this->cleanDefaut($produitTab[5]).";".
            $this->cleanDefaut($produitTab[7]).";".
            $this->cleanDefaut($produitTab[9]).";".
            $this->cleanDefaut($produitTab[11]).";".
            $this->cleanDefaut($produitTab[13]).";".
            $this->cleanDefaut($key).";".
            $produit->libelle.";".
            $stocks->denomination_complementaire.";".
            $ds->millesime.";".
            $stocks->stock_declare_millesime_courant.";".
            $stocks->dont_vraclibre_millesime_courant.";".
            $stocks->stock_declare_millesime_anterieur.";".
            $stocks->dont_vraclibre_millesime_anterieur.";".
            (($ds->teledeclare==1)? 'oui' : 'non').";".
            $ds->version.";".
            (($ds->referente==1)? 'oui' : 'non').";".
            $ds->valide->date_signee.";".
            $ds->_id.";".
            $ds->docid_origine_reprise_produits."\n";
          }
        }
        return $csv;
    }

    protected function cleanDefaut($str) {
      return str_replace('DEFAUT', '', $str);
    }

}

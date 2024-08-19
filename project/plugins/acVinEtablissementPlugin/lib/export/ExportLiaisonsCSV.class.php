<?php

class ExportLiaisonsCSV {

    protected $etablissement = null;
    protected $header = false;

    public static function getHeaderCsv() {
        return "identifiant etablissement source;nom etablissement source;cvi etablissement source;ppm etablissement source;liaison type;identifiant etablissement lié;nom etablissement lié;cvi etablissement lié;ppm etablissement lié;hash chais lié; attribus chais liés;aliases;liaison doc id;etablissement source statut;doc id;id/hash liaisons\n";
    }

    public function __construct($header = true) {
        $this->header = $header;
    }

    public function getFileName() {

        return $this->doc->_id . '_' . $this->doc->_rev . '.csv';
    }

    public function protectStr($str) {
    	return str_replace('"', '', $str);
    }

    protected function formatFloat($value) {

        return str_replace(".", ",", $value);
    }

    public function exportAll() {
        $csv = "";
        if ($this->header) {
            $csv .= $this->getHeaderCsv();
        }
        foreach(EtablissementAllView::getInstance()->getAll() as $json_doc) {
            $etablissement = EtablissementClient::getInstance()->find($json_doc->id);
            foreach($etablissement->liaisons_operateurs as $k => $liaison) {
                $csv .= $etablissement->identifiant.";";
                $csv .= $etablissement->nom.";";
                $csv .= $etablissement->cvi.";";
                $csv .= $etablissement->ppm.";";
                $csv .= $liaison->type_liaison.";";
                $csv .= preg_replace('/ETABLISSEMENT-/', '', $liaison->id_etablissement).";";
                $csv .= $liaison->libelle_etablissement.";";
                $csv .= $liaison->cvi.";";
                $csv .= $liaison->ppm.";";
                $csv .= $liaison->hash_chai.";";
                $csv .= ";"; // attributs chais
                $csv .= ";"; // aliaises
                $csv .= $liaison->id_etablissement.";";
                $csv .= $etablissement->statut.";";
                $csv .= $etablissement->_id.";";
                $csv .= $etablissement->_id.'/'.$k;
                $csv .= "\n";
            }
        }
        return $csv;
    }

}

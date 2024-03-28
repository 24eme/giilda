<?php

class ExportCompteCsv implements InterfaceDeclarationExportCsv
{
    protected $compte = null;
    protected $header = false;
    protected $region = null;

    public static function getHeaderCsv() {

        return "#numéro de compte;intitulé;type (client/fournisseur);abrégé;adresse;address complément;code postal;ville;pays;code naf;n° identifiant;n° siret;statut;date creation;téléphone;fax;email;site\n";
    }

    public function __construct($compte, $header = true, $region = null) {
        $this->compte = $compte;
        $this->header = $header;
        $this->region = $region;
    }

    public function getFileName() {

        return $this->compte->_id . '_' . $this->compte->_rev . '.csv';
    }

    public function export() {
        $csv = "";
        if($this->header) {
            $csv .= self::getHeaderCsv();
        }
        $domaine = sfConfig::get('app_routing_context_production_host');
        $type = strtolower($this->compte->type);

        $csv .= sprintf("%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s;%s\n",
                            $this->compte->getCodeComptable(),
                            $this->compte->nom_a_afficher,
                            "CLIENT",
                            $this->compte->nom_a_afficher,
                            $this->compte->adresse,
                            $this->compte->adresse_complementaire,
                            $this->compte->code_postal,
                            $this->compte->commune,
                            $this->compte->pays,
                            '', #NAF
                            $this->compte->identifiant,
                            $this->compte->societe_informations->siret,
                            $this->compte->statut,
                            '', #Date de création
                            ($this->compte->telephone_bureau) ? $this->compte->telephone_bureau : $this->compte->telephone_mobile,
                            $this->compte->fax,
                            $this->compte->email,
                            "https://$domaine/$type/".$this->compte->identifiant."/visualisation",
                          );

        return $csv;
    }

    protected function formatFloat($value) {

        return str_replace(".", ",", $value);
    }

    public function setExtraArgs($args) {
    }

}

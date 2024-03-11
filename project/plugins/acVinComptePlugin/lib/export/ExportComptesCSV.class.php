<?php

/**
 * Description of ExportParcellairePdf
 *
 * @author mathurin
 */
class ExportComptesCsv
{
    protected $compte = null;
    protected $header = false;

    protected $csv;
    protected static $delimiter = ';';

    public static function getHeaderCsv()
    {
        return [
            "Numéro de compte",
            "Intitulé",
            "Type (client/fournisseur)",
            "Abrégé",
            "Adresse",
            "Adresse complément",
            "Code postal",
            "Ville",
            "Pays",
            "Lat",
            "Lon",
            "N° identifiant",
            "N° siret",
            "Statut",
            "Téléphone",
            "Fax",
            "Email",
            "Site",
            "Compte Type",
            "En alerte",
            "Login",
            "Code 1ere connexion",
            "Date de dernière modification",
            "Tags",
            "N° Compte Type"
        ];
    }

    public function __construct($header = true)
    {
        $this->header = $header;
        $this->csv = fopen('php://output', 'w+');
        if ($header) {
            fputcsv($this->csv, self::getHeaderCsv(), self::$delimiter);
        }
    }

    public function getFileName()
    {
        return $this->compte->_id . '_' . $this->compte->_rev . '.csv';
    }

    public function export()
    {
        $compteclient = CompteClient::getInstance();

        foreach (CompteAllView::getInstance()->getAll() as $json_doc) {
            $compte = $compteclient->find($json_doc->id);
            $domaine = sfConfig::get('app_routing_context_production_host');
            $type = strtolower($compte->type);
            $code1erecon = '';
            $login = $compte->login;
            if ($compte->compte_type == 'ETABLISSEMENT') {
                $ecompte = $compte->getSociete()->getMasterCompte();
                $mdp = $ecompte->mot_de_passe;
                if (strpos($mdp, '{TEXT}') === 0) {
                    $code1erecon = substr($mdp, 6);
                }
                $login = $ecompte->login;
            }elseif (strpos($compte->mot_de_passe, '{TEXT}') === 0) {
                $code1erecon = substr($compte->mot_de_passe, 6);
            }
            $data = [
                $compte->getCodeComptable(),
                $compte->nom_a_afficher,
                'CLIENT',
                $compte->nom_a_afficher,
                $compte->adresse,
                $compte->adresse_complementaire,
                $compte->code_postal,
                $compte->commune,
                $compte->pays,
                $compte->lat,
                $compte->lon,
                $compte->identifiant,
                $compte->societe_informations->siret,
                $compte->statut,
                ($compte->telephone_bureau) ?: $compte->telephone_mobile,
                $compte->fax,
                $compte->email,
                "https://$domaine/$type/$compte->identifiant/visualisation",
                $compte->compte_type,
                $compte->isEnAlerte(),
                ''.$login.' ',
                $code1erecon,
                $compte->date_modification,
                $this->compte2strtags($compte),
                $compte->_id
            ];

            fputcsv($this->csv, $data, self::$delimiter);
        }

        fclose($this->csv);
    }

    private function compte2strtags($compte) {
        $tagsArray = array();
        foreach ($compte->tags as $keys => $json) {
            foreach ($json as $key => $value) {
              $tagsArray[] = $keys.":".$value;
            }
        }
        return implode(',',$tagsArray);
    }
}

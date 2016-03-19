<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MaintenanceTransfertChaiTask
 *
 * @author mathurin
 */
class ExportContratsFATask extends sfBaseTask {

    const CSV_FA_NUM_LIGNE = 0;
    const CSV_FA_TYPE_CONTRAT = 1;
    const CSV_FA_CAMPAGNE = 2;
    const CSV_FA_NUM_ARCHIVAGE = 3;
    const CSV_FA_CODE_LIEU_VISA = 4;
    const CSV_FA_CODE_ACTION = 5;
    const CSV_FA_DATE_CONTRAT = 6;
    const CSV_FA_DATE_VISA = 7;
    const CSV_FA_CODE_COMMUNE_LIEU_VINIFICATION = 8;
    const CSV_FA_INDICATION_DOUBLE_FIN = 9;
    const CSV_FA_CODE_INSEE_DEPT_COMMUNE_ACHETEUR = 10;
    const CSV_FA_NATURE_ACHETEUR = 11;
    const CSV_FA_SIRET_ACHETEUR = 12;
    const CSV_FA_CVI_VENDEUR = 13;
    const CSV_FA_NATURE_VENDEUR = 14;
    const CSV_FA_SIRET_VENDEUR = 15;
    const CSV_FA_COURTIER = 16; // (O/N)
    const CSV_FA_DELAI_RETIRAISON = 17;
    const CSV_FA_POURCENTAGE_ACCOMPTE = 18;
    const CSV_FA_DELAI_PAIEMENT = 19;
    const CSV_FA_CODE_TYPE_PRODUIT = 20;
    const CSV_FA_CODE_DENOMINATION_VIN_IGP = 21;
    const CSV_FA_PRIMEUR = 22;
    const CSV_FA_BIO = 23;
    const CSV_FA_COULEUR = 24;
    const CSV_FA_ANNEE_RECOLTE = 25;
    const CSV_FA_CODE_ELABORATION = 26; // (O/N)
    const CSV_FA_VOLUME = 27;
    const CSV_FA_DEGRE = 28; //(Degré vin si type de contrat = V (vins) Degré en puissance si type de contrat = M (moût))
    const CSV_FA_PRIX = 29;
    const CSV_FA_UNITE_PRIX = 30; // H pour Hl
    const CSV_FA_CODE_CEPAGE = 31;
    const CSV_FA_CODE_DEST = 32; // Z

    protected $produitsConfiguration = null;

    protected function configure() {



        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'export';
        $this->name = 'contrats-france-agrimer';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [export-contrats-france-agrimer|INFO] task update contrat from chai src to chai dst.
Call it with:

  [php symfony export:contrats-france-agrimer|INFO]
EOF;
        
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $this->produitsConfiguration = ConfigurationClient::getCurrent()->getProduits();


        echo "#num_ligne;type_contrat;campagne;num_archive;code_lieu_visa;code_action;date_contrat;date_visa;code_commune_lieu_vinification;indicateur_double_fin;code_insee_dept_commune_acheteur;nature_acheteur;siret_acheteur;cvi_vendeur;nature_vendeur;siret_vendeur;courtier (O/N);delai_retiraison;pourcentage_accompte;delai_paiement;code_type_produit;code_denomination_vin_IGP;primeur;bio;couleur;annee_recolte;code_elaboration (O/N);volume;degre (Degré vin si type de contrat = V (vins) Degré en puissance si type de contrat = M (moût));prix;unité_prix (H);code_cepage;code_dest (Z)\n";

        $contrats = $this->getContrats();
        $this->printCSV($contrats->rows);
    }

    protected function getContrats() {

        return VracClient::getInstance()->retrieveAllVracs();
    }

    protected function printCSV($contratsView) {
        if (!count($contratsView)) {
            echo "Aucun contrats\n";
        }
        $cpt = 0;
        foreach ($contratsView as $contratView) {
            if (!preg_match('/\/[a-z]+\/[a-z]+\/IGP(.*)/', $contratView->value[VracHistoryView::VALUE_PRODUIT])) {
                continue;
            }
            $cpt++;
            $ligne = array();
            $contrat = VracClient::getInstance()->find($contratView->id);

            $produit = $this->produitsConfiguration[$contratView->value[VracHistoryView::VALUE_PRODUIT]];

            $acheteur = $contrat->getAcheteurObject();
            $acheteurCompte = $acheteur->getCompte();
            $acheteurSociete = $acheteur->getSociete();

            $vendeur = $contrat->getVendeurObject();
            $vendeurCompte = $acheteur->getCompte();
            $vendeurSociete = $acheteur->getSociete();

            $ligne[self::CSV_FA_NUM_LIGNE] = $cpt;
            $type_contrat = "";
            if ($contrat->type_transaction == VracClient::TYPE_TRANSACTION_VIN_VRAC) {
                $type_contrat = "V";
            }
            $ligne[self::CSV_FA_TYPE_CONTRAT] = $type_contrat;
            $ligne[self::CSV_FA_CAMPAGNE] = substr($contrat->campagne, 0, 4);
            $ligne[self::CSV_FA_NUM_ARCHIVAGE] = $contrat->numero_archive;
            $ligne[self::CSV_FA_CODE_LIEU_VISA] = "083?";
            $ligne[self::CSV_FA_CODE_ACTION] = 'N/C';
            $ligne[self::CSV_FA_DATE_CONTRAT] = $contrat->date_signature;
            $ligne[self::CSV_FA_DATE_VISA] = $contrat->date_visa;
            $ligne[self::CSV_FA_CODE_COMMUNE_LIEU_VINIFICATION] = $contrat->vendeur->code_postal . '?';
            $ligne[self::CSV_FA_INDICATION_DOUBLE_FIN] = 'N?';
            $ligne[self::CSV_FA_CODE_INSEE_DEPT_COMMUNE_ACHETEUR] = $acheteurCompte->insee;
            $ligne[self::CSV_FA_NATURE_ACHETEUR] = '?';
            $ligne[self::CSV_FA_SIRET_ACHETEUR] = $acheteurSociete->siret;

            $ligne[self::CSV_FA_CVI_VENDEUR] = $vendeur->cvi;
            $ligne[self::CSV_FA_NATURE_VENDEUR] = '?';
            $ligne[self::CSV_FA_SIRET_VENDEUR] = $vendeurSociete->siret;
            $ligne[self::CSV_FA_COURTIER] = ($contrat->mandataire_exist) ? 'O' : 'N';
            $delai_retiraison = $this->diffDate($contrat->date_limite_retiraison, $contrat->date_debut_retiraison, 'i');
            $ligne[self::CSV_FA_DELAI_RETIRAISON] = $delai_retiraison;
            $ligne[self::CSV_FA_POURCENTAGE_ACCOMPTE] = $contrat->acompte;
            $ligne[self::CSV_FA_DELAI_PAIEMENT] = ($contrat->delai_paiement == 'COMPTANT') ? '1' : '0'; //??
            $ligne[self::CSV_FA_CODE_TYPE_PRODUIT] = $produit->getCodeProduit(); //??
            $ligne[self::CSV_FA_CODE_DENOMINATION_VIN_IGP] = "PA?"; //??
            $ligne[self::CSV_FA_PRIMEUR] = "N"; //??
            $ligne[self::CSV_FA_BIO] = "N"; //??
            $ligne[self::CSV_FA_COULEUR] = $produit->getCouleur()->getKey(); //??
            $ligne[self::CSV_FA_ANNEE_RECOLTE] = substr($contrat->campagne, 0, 4); //??
            $ligne[self::CSV_FA_CODE_ELABORATION] = 'N ou P';
            $ligne[self::CSV_FA_VOLUME] = $contrat->volume_propose;
            $ligne[self::CSV_FA_DEGRE] = $contrat->degre;
            $ligne[self::CSV_FA_PRIX] = $contrat->prix_initial_unitaire_hl; 
            $ligne[self::CSV_FA_UNITE_PRIX] = 'H'; //??
            $ligne[self::CSV_FA_CODE_CEPAGE] = $produit->getCodeProduit(); //??
            $ligne[self::CSV_FA_CODE_DEST] = "Z"; //??
            
            foreach ($ligne as $champ) {
                echo '"' . $champ . '";';
            }
            echo "\n";
        }
    }
    public function diffDate($date1, $date2, $retour) {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
	    $diff = $date1->diff($date2);
	    return $diff->{$retour};
  	}

}

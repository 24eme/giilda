<?php

class GenerationExportSage extends GenerationAbstract
{
    public function generate() {
        /*$this->generation->remove('documents');
        $this->generation->add('documents');*/

        $this->generation->setStatut(GenerationClient::GENERATION_STATUT_ENCOURS);

        $sagefile = "generation/".$this->generation->date_emission."_sage.txt";
        $facturesfile = "generation/".$this->generation->date_emission."_factures.csv";
        $clientsfile = "generation/".$this->generation->date_emission."_clients.csv";
        
        $handle_factures = fopen(sfConfig::get('sf_web_dir')."/".$facturesfile, 'a');
        $handle_clients = fopen(sfConfig::get('sf_web_dir')."/".$clientsfile, 'a');

        if(!count($this->generation->documents)) {
            fwrite($handle_factures, ExportFactureCSV::getHeaderCsv());
            fwrite($handle_clients, ExportCompteCSV::getHeaderCsv());
        }

        $batch_size = 500;
        $batch_i = 1;

        foreach(FactureEtablissementView::getInstance()->getFactureNonVerseeEnCompta() as $vfacture) {
            $facture = FactureClient::getInstance()->find($vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID]);

            if(!$facture) {
                throw new sfException(sprintf("Document %s introuvable", $vfacture->key[FactureEtablissementView::KEYS_FACTURE_ID]));
            }

            $export = new ExportFactureCSV($facture, false);

            if(!$facture->versement_comptable) {

                fwrite($handle_factures, $export->exportFacture());
                $this->generation->documents->add(null, $facture->_id);
                $facture->versement_comptable = 1;
                $facture->save();
            }

            if($facture->versement_comptable && !$facture->versement_comptable_paiement && $facture->isPayee( )) {
                fwrite($handle_factures, $export->exportPaiement());
                $this->generation->documents->add(null, str_replace("FACTURE-", "PAIEMENT-", $facture->_id));
                $facture->versement_comptable_paiement = 1;
                $facture->save();
            }

            $compte = $facture->getCompte();
            if(!$compte) {
                throw new sfException(sprintf("Document COMPTE-%s introuvable", $facture->identifiant));
            }

            $export = new ExportCompteCSV($compte, false);

            fwrite($handle_clients, $export->export());
            $batch_i++;
            if($batch_i > $batch_size) {
              $this->generation->save();
              $batch_i = 1;
            }
        }

        $this->generation->save();

        fclose($handle_factures);
        fclose($handle_clients);

        file_put_contents(sfConfig::get('sf_web_dir')."/".$sagefile, shell_exec(sprintf("bash %s/bin/facture/csv2sage.sh %s %s", sfConfig::get('sf_root_dir'), sfConfig::get('sf_web_dir')."/".$clientsfile, sfConfig::get('sf_web_dir')."/".$facturesfile)));
        $this->generation->setStatut(GenerationClient::GENERATION_STATUT_GENERE);

        if(count($this->generation->documents)) {
            $this->generation->add('fichiers')->add(urlencode("/".$sagefile), 'Export SAGE');
            $this->generation->add('fichiers')->add(urlencode("/".$facturesfile), 'Export CSV des factures');
            $this->generation->add('fichiers')->add(urlencode("/".$clientsfile), 'Export CSV des clients');
        }

        $this->generation->save();
    }

    public function getDocumentName() {
        
        return 'Sage';
    }

} 
<?php

class GenerationExportShell extends GenerationAbstract
{
    public function generate() {
        /*$this->generation->remove('documents');
        $this->generation->add('documents');*/

        $this->generation->setStatut(GenerationClient::GENERATION_STATUT_ENCOURS);

        if (!file_exists(sfConfig::get('app_export_shell'))) {
            $this->generation->setStatut(GenerationClient::GENERATION_STATUT_ENERREUR);
            $this->generation->save();
            return false;
        }
        
        $this->generation->save();

        exec('bash '.sfConfig::get('app_export_shell'), $generatedFiles);
        foreach($generatedFiles as $file) {
            $names = explode('|', $file);
            $this->generation->add('fichiers')->add($this->publishFile($names[0], $this->generation->date_emission.'-'.$names[1], ''), $names[2]);
        }

        $this->generation->setStatut(GenerationClient::GENERATION_STATUT_GENERE);
        $this->generation->save();

    }

    public function getDocumentName() {
        return 'ExportShell';
    }

} 

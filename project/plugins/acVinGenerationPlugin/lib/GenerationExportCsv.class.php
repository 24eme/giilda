<?php

class GenerationExportCsv extends GenerationAbstract
{
    public function generate() {
        $this->generation->remove('documents');
        $this->generation->add('documents');

        $this->generation->setStatut(GenerationClient::GENERATION_STATUT_ENCOURS);

        if(!$this->generation->arguments->exist('type_document')) {
            
            throw new sfException("Le type de document est requis");
        }

        if(!$this->generation->arguments->exist('campagne')) {
            
            throw new sfException("La campagne est requise");
        }

        $validation = ($this->generation->arguments->exist('validation')) ? $this->generation->arguments->exist('validation') : true;

        $ids = DeclarationClient::getInstance()->getIds($this->generation->arguments->type_document, $this->generation->arguments->campagne);

        $webfile = "/generation/".$this->generation->date_emission.".csv";
        $file = sfConfig::get('sf_web_dir').$webfile;

        $handle = fopen($file, 'w');

        fwrite($handle, "\xef\xbb\xbf"); //UTF8 BOM (pour windows)

        $className = DeclarationClient::getInstance()->getExportCsvClassName($this->generation->arguments->type_document);
          
        fwrite($handle, $className::getHeaderCsv());

        $batch_size = 500;

        $batch_i = 1;
        foreach($ids as $id) {
            
            if(!$id) {
                throw new sfException(sprintf("Document id vide"));
            }

            $doc = DeclarationClient::getInstance()->find($id);

            if(!$doc) {
                throw new sfException(sprintf("Document %s introuvable", $id));
            }

            if($validation && !$doc->validation) {               
                continue;
            }

            $export = DeclarationClient::getInstance()->getExportCsvObject($doc, false);
            fwrite($handle, $export->export());

            $this->generation->documents->add(null, $id);

            $batch_i++;
            if($batch_i > $batch_size) {
              $this->generation->save();
              $batch_i = 1;
            }
        }

        fclose($handle);

        $this->generation->setStatut(GenerationClient::GENERATION_STATUT_GENERE);

        $this->generation->add('fichiers')->add(urlencode($webfile), 
        'CSV de '.count($this->generation->documents).' documents');

        $this->generation->save();
    }

    public function getDocumentName() {
        
        return 'CSV';
    }

} 
<?php
/**
 * Model for CSV
 *
 */

class CSV extends BaseCSV {

    
    public function getFileContent() {
        return file_get_contents($this->getAttachmentUri($this->getFileName()));
    }
    
    public function getFileName() {
        return 'import_edi_'.$this->identifiant.'_'.$this->periode.'.csv';
    }
}
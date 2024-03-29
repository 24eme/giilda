<?php

class GenerationExportRelances extends GenerationExportShell
{

    public function getDocumentName() {
        return 'ExportRelances';
    }

    public function getShellScript() {
        $interpro = $this->getArgInterpro();
        return FactureConfiguration::getInstance($interpro)->getExportRelances();
    }

}

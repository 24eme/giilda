<?php

class GenerationExportRelances extends GenerationExportShell
{

    public function getDocumentName() {
        return 'ExportRelances';
    }

    public function getShellScript() {
        return FactureConfiguration::getInstance()->getExportRelances();
    }

}

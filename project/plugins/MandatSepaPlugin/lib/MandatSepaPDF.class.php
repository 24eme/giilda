<?php
class MandatSepaPDF extends ExportPDF {

    protected $mandatSepa = null;

    public function __construct($mandatSepa, $type = 'pdf', $use_cache = false, $file_dir = null, $filename = null) {
        $this->mandatSepa = $mandatSepa;
        if (!$filename) {
            $filename = $this->getFileName(true);
        }
        parent::__construct($type, $use_cache, $file_dir, $filename);
    }

    public function create() {
        $this->printable_document->addPage($this->getPartial('mandatsepa/pdf', array('mandatSepa' => $this->mandatSepa)));
    }

    public function output() {
        if($this->printable_document instanceof PageableHTML) {
            return parent::output();
        }
        return file_get_contents($this->getFile());
    }

    public function getFile() {
        if($this->printable_document instanceof PageableHTML) {
            return parent::getFile();
        }
        return sfConfig::get('sf_cache_dir').'/pdf/'.$this->getFileName(true);
    }

    protected function getHeaderTitle() {
        $titre = sprintf("Mandat de prélèvement SEPA");
        return $titre;
    }

    protected function getHeaderSubtitle() {
        $header_subtitle = "Référence : ".$this->mandatSepa->getReference(false);
        return $header_subtitle;
    }

    protected function getFooterText() {
        $footer= "";
        return $footer;
    }

    protected function getConfig() {
        return new MandatSepaPDFConfig();
    }

    public function getFileName($with_rev = false) {
        return self::buildFileName($this->mandatSepa, true);
    }

    public static function buildFileName($mandatSepa, $with_rev = false) {
        $filename = $mandatSepa->_id;
        if ($with_rev) {
            $filename .= '_' . $mandatSepa->_rev;
        }
        return $filename . '.pdf';
    }
}

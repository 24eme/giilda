<?php

class MandatSepaPDFConfig extends ExportPDFConfig
{
    public function __construct() {
        parent::__construct();
        $this->subject = 'Mandat de prélèvement SEPA';
        $this->orientation = self::ORIENTATION_PORTRAIT;
        $this->keywords = 'facturation,prélèvement,sepa';
        $this->creator = 'VinsIGP';
        $this->author = 'VinsIGP';
    }
}

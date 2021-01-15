<?php

class GenerationFacturePapier extends GenerationPDF
{
    protected $factures = [];

    public function preGeneratePDF()
    {
        parent::preGeneratePDF();

        foreach ($this->generation->getMasterGeneration()->documents as $id) {
            $facture = FactureClient::getInstance()->find($id);

            if (! $facture) {
                throw new sfException("Facture $id n'existe pas");
            }

            if ($facture->exist('telechargee') && $facture->telechargee) {
                continue;
            }

            $this->factures[$id] = $facture;
            $this->generation->documents->add(null, $id);
        }
    }

    public function generatePDFForADocumentID($docid)
    {
        return new FactureLatex($this->factures[$docid], $this->config);
    }
}

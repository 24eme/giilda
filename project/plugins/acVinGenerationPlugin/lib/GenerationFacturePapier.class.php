<?php

class GenerationFacturePapier extends GenerationPDF
{
    public function generate()
    {
        foreach ($this->generation->getMasterGeneration()->documents as $id) {
            $facture = FactureClient::getInstance()->find($id);

            if ($facture->exist('telechargee') && $facture->telechargee) {
                continue;
            }

            $this->generation->documents->add(null, $id);
        }
    }
}

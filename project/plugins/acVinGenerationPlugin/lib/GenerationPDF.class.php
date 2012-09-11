<?php

class GenerationPDF {

  private $generation;

  function __construction(Generation $g) {
    $this->generation = $g;
  }

  function concatenatePDFs($pdffiles) {
  }

  function generatePDFOnePageRange($pdfs) {
    $files = array();
    foreach ($pdsf as $pdf) {
      $files[] = $pdf->getPDFFile();
    }
    return $this->concatenatePDFs($files);
  }

  function generatePDF() {
    $factures = array();
    foreach ($this->generation->documents as $factureid) {
      $facture = FactureClient::getInstance()->find($factureid);
      $pdf = new FactureLatex($facture);
      array_push($factures[$pdf->getNbPages()], $pdf);
    }
    $pages = array();
    foreach ($factures as $page => $pdfs) {
      $pages[$page] = $this->generatePDFOnePageRange();
    }
    return $this->concatenatePDFs($pages);
  }

}
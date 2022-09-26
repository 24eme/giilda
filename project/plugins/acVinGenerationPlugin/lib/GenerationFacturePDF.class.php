<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class GenerationFacturePDF
 * @author mathurin
 */
class GenerationFacturePDF extends GenerationPDF {

    const BATCH_SAVE = 15;

    function __construct(Generation $g, $config = null, $options = null) {
        parent::__construct($g, $config, $options);
    }

    public function preGeneratePDF() {
        parent::preGeneratePDF();
        $arguments = $this->generation->arguments->toArray();

        $regions = null;
        $interpro = null;
        if(sfConfig::get('app_facturation_region')) {
            $regions[] = sfConfig::get('app_facturation_region');
        }
        if (isset($arguments['region'])) {
            $regions = [$arguments['region']];
        }
        if (isset($arguments['interpro'])) {
            $interpro = $arguments['interpro'];
        }

        $allMouvementsByRegion = FactureClient::getInstance()->getMouvementsForMasse($interpro, $regions);

        $mouvementsBySoc = FactureClient::getInstance()->getMouvementsNonFacturesBySoc($allMouvementsByRegion);
        if (!isset($arguments['modele']) || !$arguments['modele']) {
            throw new sfException("Le modele n'existe pas dans les arguments de la génération");
        }
        $mouvementsBySoc = FactureClient::getInstance()->filterWithParameters($mouvementsBySoc, $arguments);
        $message_communication = (array_key_exists('message_communication', $arguments)) ? $arguments['message_communication'] : null;
        if (!$this->generation->exist('somme'))
          $this->generation->somme = 0;
        $cpt = count($this->generation->documents);
        foreach ($mouvementsBySoc as $societeID => $mouvementsSoc) {
          $societe = SocieteClient::getInstance()->find($societeID);
          if (!$societe)
              throw new sfException($societeID . " unknown :(");
          $modele = $arguments['modele'];
          if ($modele == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12_NEGO) {
              if (!$societe->isNegociant()) {
                continue;
              }
              $modele = FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV12;
          }
          $facture = FactureClient::getInstance()->createDocFromMouvements($mouvementsSoc, $societe, $modele, $arguments['date_facturation'], $message_communication, $interpro);
          $facture->save();
          $this->generation->somme += $facture->total_ht;
          $this->generation->documents->add($cpt, $facture->_id);
          $cpt++;
        }
    }

    protected function generatePDFForADocumentId($factureid) {
      $facture = FactureClient::getInstance()->find($factureid);
      if (!$facture) {
	throw new sfException("Facture $factureid doesn't exist\n");
      }
      return new FactureLatex($facture, $this->config);
    }

    protected function getDocumentName() {
      return "Factures";
    }

    function postGeneratePDF() {
        if (!file_exists(sfConfig::get('sf_root_dir').'/bin/postGenerationFacturePDF.sh'))
            return false;
        exec(sfConfig::get('sf_root_dir').'/bin/postGenerationFacturePDF.sh', $generatedFiles);
        foreach($generatedFiles as $file) {
            $names = explode('|', $file);
            $this->generation->add('fichiers')->add($this->publishFile($names[0], $this->generation->date_emission.'-'.$names[1], ''), $names[2]);
        }
        return true;
    }

}

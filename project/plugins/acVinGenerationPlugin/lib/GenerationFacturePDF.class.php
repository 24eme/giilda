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

        $template = TemplateFactureClient::getInstance()->find($this->generation->arguments->modele);

        if(!$template) {
            throw new sfException(sprintf("Le template de facture %s n'existe pas", $this->generation->arguments->modele));
        }

        $comptes_id = FactureClient::getInstance()->getComptesIdFilterWithParameters($this->generation->arguments->toArray());

        $message_communication = $this->generation->arguments->exist('message_communication') ? $this->generation->arguments->get('message_communication') : null;
        $date_facturation = $this->generation->arguments->exist('date_facturation') ? $this->generation->arguments->get('date_facturation') : null;

        if(!$this->generation->exist('somme')) {
          $this->generation->somme = 0;
        }
        
        $cpt = count($this->generation->documents);
        $batch_cpt = 0;
        foreach($comptes_id as $compte_id) {
            $compte = CompteClient::getInstance()->find($compte_id);

            if(!$compte) {
                throw new sfException(sprintf("Compte inexistant %s", $compte_id));
            }

            try {
                /*if(!$compte->cvi) {
                    throw new sfException(sprintf("Ce compte n'a pas de numéro CVI"));
                }*/

              $cotisations = $template->generateCotisations($compte, $template->campagne);
            } catch (Exception $e) {
              $this->generation->message .= sprintf("%s (%s) : %s\n", $compte->nom_a_afficher, $compte->_id, $e->getMessage());
              $this->generation->save();
              continue;
            }
            if(!count($cotisations)) {
                continue;
            }

            $facture = FactureClient::getInstance()->createDoc($cotisations, $compte, $date_facturation, $message_communication, $template->arguments->toArray(true, false));
            $facture->save();
            $this->generation->somme += $facture->total_ttc;
            $this->generation->documents->add($cpt, $facture->_id);
            
            $batch_cpt++;
            if($batch_cpt >= (self::BATCH_SAVE)) {
              $this->generation->save();
              $batch_cpt = 0;
            }

            $cpt++;
        }

        $this->generation->save();
    }

    public function preRegeneratePDF() {
        parent::preRegeneratePDF();

        $documents_generated = array_flip($this->generation->documents->toArray(true, false));

        $cpt = count($this->generation->documents);
        $batch_cpt = 0;
        foreach($this->generation->documents_regenerate as $f) {
            if(array_key_exists($f->_id, $documents_generated)) {
              continue;
            }

            try {
              $facture = FactureClient::getInstance()->regenerate($f);
              $facture->save();
            } catch (Exception $e) {
              $this->generation->message .= sprintf("%s (%s) : %s\n", $compte->nom_a_afficher, $compte->_id, $e->getMessage());
              $this->generation->documents->add($cpt, $f->_id);
              $this->generation->save();
              $cpt++;
              continue;
            }

            $this->generation->somme += $facture->total_ttc;
            $this->generation->documents->add($cpt, $facture->_id);
            
            $batch_cpt++;
            if($batch_cpt >= (self::BATCH_SAVE)) {
              $this->generation->save();
              $batch_cpt = 0;
            }

            $cpt++;
        }

        $this->generation->save();
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

    public static function isRegenerable() {

        return true;
    }
}

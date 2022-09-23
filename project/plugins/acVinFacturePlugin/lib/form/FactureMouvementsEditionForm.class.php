<?php

/**
 * Description of FactureMouvementsEditionForm
 *
 * @author mathurin
 */
class FactureMouvementsEditionForm extends acCouchdbObjectForm {

    protected $interpro_id;
    protected $interproFacturable;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->interpro_id = "INTERPRO-declaration";
        if(isset($options['interpro_id'])) {
            $this->interpro_id = $options['interpro_id'];
        }
        if(isset($options['interproFacturable'])) {
            $this->interproFacturable = $options['interproFacturable'];
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setValidator("libelle", new sfValidatorString(array("required" => true)));
        $this->widgetSchema->setLabel('libelle', 'Libellé opération');

        $this->embedForm('mouvements', new FactureMouvementEditionLignesForm($this->getObject(), array('interpro_id' => $this->interpro_id, 'interproFacturable' => $this->interproFacturable)));

        $this->widgetSchema->setNameFormat('facture_mouvements_edition[%s]');
    }

    protected function doUpdateObject($values) {
      $mouvements = $values['mouvements'];
      unset($values['mouvements']);
    	parent::doUpdateObject($values);
      if (!$this->getObject()->date) {
            $this->getObject()->set('date', date('Y-m-d'));
            $this->getObject()->getOrAdd('valide')->set('date_saisie', date('Y-m-d'));
      }
      $inserted_keys = array();
      $ordre = $this->getObject()->getStartIndexForSaisieForm();
      foreach($mouvements as $cle => $mouvement) {
          $kExploded = explode('_', $cle);
          $id = $kExploded[1];
          $key = $kExploded[2];
          if (!$mouvement['identifiant']||!$mouvement['quantite']||!$mouvement['prix_unitaire']) {
            continue;
          }
          if ($id == 'nouveau') {
            $k = uniqid();
            $societe = SocieteClient::getInstance()->find($mouvement['identifiant']);
            $societeMvtKey = EtablissementClient::getInstance()->getFirstIdentifiant($societe->identifiant);
          } else {
            $k = $key;
            $societe = null;
            $societeMvtKey = $id;
          }

          $mvt = $this->getObject()->mouvements->getOrAdd($societeMvtKey)->getOrAdd($k);
          $inserted_keys[$societeMvtKey.'_'.$k] = 1;

          $mvt->identifiant = $societeMvtKey;
          $mvt->updateIdentifiantAnalytique($mouvement['identifiant_analytique']);
          $mvt->libelle = $mouvement['libelle'];
          $mvt->quantite = floatval($mouvement['quantite']);
          $mvt->prix_unitaire = floatval($mouvement['prix_unitaire']);
          if (isset($mouvement['taux_tva'])) {
            $mvt->add('taux_tva', floatval($mouvement['taux_tva']));
          }
          if (!$mvt->facture) {
            $mvt->facture = 0;
          }
          $mvt->facturable = 1;
          if ($this->interproFacturable) {
              $mvt->interpro = $this->interproFacturable;
          }
          if ($societe) {
            $mvt->region = ($societe->getRegionViticole(false))? $societe->getRegionViticole() : $societe->type_societe;
          }

          $mvt->date = $this->getObject()->date;
          $mvt->vrac_numero = $ordre;
          $ordre++;
      }
      // Suppression des lignes supprimees dynamiquement
      $mvtsToRemove = array();
      foreach ($this->getObject()->getOrAdd('mouvements') as $etbId => $mvtsEtb) {
          foreach ($mvtsEtb as $keyMvt => $mvt) {
              if (!$mvt->facture && !isset($inserted_keys[$etbId . '_' . $keyMvt])) {
                  $mvtsToRemove[] = $mvt;
              }
          }
      }
      foreach ($mvtsToRemove as $mvtToRemove) {
          $mvtToRemove->delete();
      }
      if ($this->getObject()->mouvements->exist('nouveau')) {
        $this->getObject()->mouvements->remove('nouveau');
      }
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if($taintedValues && $form instanceof FactureMouvementEditionLignesForm) {
                $files = ($taintedFiles && isset($taintedFiles[$key]))? $taintedFiles[$key] : null;
                $form->bind($taintedValues[$key], $files);
                $this->updateEmbedForm($key, $form);
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

}

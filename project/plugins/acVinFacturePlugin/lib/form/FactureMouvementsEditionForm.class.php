<?php
/**
 * Description of FactureMouvementsEditionForm
 *
 * @author mathurin
 */
class FactureMouvementsEditionForm extends acCouchdbObjectForm {

    protected $interpro_id;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->interpro_id = $options['interpro_id'];
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setWidget('date', new bsWidgetFormInputDate());

        $this->setValidator("libelle", new sfValidatorString(array("required" => true)));
        $this->setValidator('date', new sfValidatorString(array('required' => false)));

        $this->getObject()->mouvements->add("nouvelle");

        $this->embedForm('mouvements', new FactureMouvementEditionLignesForm($this->getObject()->mouvements, array('interpro_id' => $this->interpro_id)));

        $this->widgetSchema->setNameFormat('facture_mouvements_edition[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $dateFacture = Date::getIsoDateFromFrenchDate($values["date"]);
        $this->getObject()->set('date',$dateFacture);
        if ($this->getObject()->mouvements->exist("nouvelle")) {
            $mvtsEtb = $this->getObject()->mouvements->get("nouvelle")->toArray(true, false);
            $nouveauMvt = $this->getObject()->mouvements->get("nouvelle")->get("nouvelle");
            $this->getObject()->mouvements->remove("nouvelle");
            $identifiant = preg_replace('/^SOCIETE-/', '', $nouveauMvt->identifiant);
            $this->getObject()->mouvements->getOrAdd($identifiant)->add(uniqid(), $nouveauMvt);
        }
        foreach ($this->getObject()->mouvements as $mouvementEtb) {
            foreach ($mouvementEtb as $mouvement) {
                $mouvement->facturable = 1;
                $mouvement->facture = 0;
                $mouvement->region = EtablissementClient::HORS_REGION;
                $mouvement->date = $dateFacture;
            }
        }

//      $this->getObject()->lignes->cleanLignes();
        $this->getObject()->valide->date_saisie = $values['date'];
    }

}

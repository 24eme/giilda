<?php
/**
 * Description of class
 * @author mathurin
 */
class FactureEditionSepaForm extends acCouchdbObjectForm {

    protected $sepaSociete = null;
    protected $societe = null;

    public function __construct(Societe $societe, $options = array(), $CSRFSecret = null) {
        $this->societe = $societe;
        parent::__construct($societe, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $this->setWidget('nom_bancaire', new sfWidgetFormInput());
        $this->setValidator('nom_bancaire', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('nom_bancaire', 'Nom bancaire :');

        $this->setWidget('iban', new sfWidgetFormInput());
        $this->setValidator('iban', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('iban', 'IBAN :');

        $this->setWidget('bic', new sfWidgetFormInput());
        $this->setValidator('bic', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('bic', 'Bic :');

        $this->widgetSchema->setNameFormat('facture_edition_sepa[%s]');
    }

    private function getSepaSociete() {
        if (!$this->sepaSociete) {
            $this->sepaSociete = $this->societe->getSepaSociete();
        }
        return $this->sepaSociete;
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $this->getSepaSociete();
        $this->setDefault('nom_bancaire', $this->sepaSociete->nom_bancaire);
        $this->setDefault('iban', $this->sepaSociete->iban);
        $this->setDefault('bic', $this->sepaSociete->bic);
    }

    public function getDiff() {
        $diff = array();
        $this->getSepaSociete();
        foreach ($this->getValues() as $key => $new_value) {
            if (!preg_match('/^_revision$/', $key)) {
                if ($this->sepaSociete->$key != $new_value) {
                    $diff[$key] = $new_value;
                }
            }
        }
        return $diff;
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->societe->add('sepa')->nom_bancaire = $values['nom_bancaire'];
        $this->societe->add('sepa')->iban = $values['iban'];
        $this->societe->add('sepa')->bic = $values['bic'];
        $this->societe->add('sepa')->date_activation = null;
        $this->societe->save();

    }


}

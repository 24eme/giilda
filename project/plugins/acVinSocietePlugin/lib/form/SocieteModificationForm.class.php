<?php

/**
 * Description of class SocieteModificationForm
 * @author mathurin
 */
class SocieteModificationForm extends CompteGeneriqueForm {

    private $types_societe = null;
    private $statuts = null;

    public function __construct(Societe $societe, $options = array(), $CSRFSecret = null) {
        parent::__construct($societe, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $this->setWidget('raison_sociale', new bsWidgetFormInput());
        $this->setWidget('raison_sociale_abregee', new bsWidgetFormInput());

        $this->setWidget('type_societe', new bsWidgetFormChoice(array('choices' => $this->getSocieteTypes(), 'expanded' => false)));
        $this->setValidator('type_societe', new sfValidatorChoice(array('required' => true, 'choices' => $this->getSocieteTypesValid())));

        if ($this->getObject()->isNegoOrViti()) {

            $this->setWidget('cooperative', new bsWidgetFormChoice(array('choices' => $this->getCooperative(), 'multiple' => false, 'expanded' => true)));
        }

        $this->setWidget('siret', new bsWidgetFormInput());
        $this->setWidget('code_naf', new bsWidgetFormInput());
        $this->setWidget('no_tva_intracommunautaire', new bsWidgetFormInput());

        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->widgetSchema->setLabel('raison_sociale', 'Nom de la société *');
        $this->widgetSchema->setLabel('raison_sociale_abregee', 'Abrégé');

        if ($this->getObject()->isNegoOrViti()) {
            $this->widgetSchema->setLabel('cooperative', 'Cave coopérative *');
        }

        $this->widgetSchema->setLabel('siret', 'SIRET');
        $this->widgetSchema->setLabel('code_naf', 'Code Naf');
        $this->widgetSchema->setLabel('no_tva_intracommunautaire', 'TVA Intracom.');

        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('raison_sociale_abregee', new sfValidatorString(array('required' => false)));

        if ($this->getObject()->isNegoOrViti()) {
            $this->setValidator('cooperative', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCooperative()))));
        }

        $this->setValidator('siret', new sfValidatorString(array('required' => false)));
        $this->setValidator('code_naf', new sfValidatorString(array('required' => false)));
        $this->setValidator('no_tva_intracommunautaire', new sfValidatorString(array('required' => false)));

        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));


        $this->widgetSchema->setNameFormat('societe_modification[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        if ($this->getObject()->isNegoOrViti()) {
            if (is_null($this->getObject()->cooperative)) {
                $this->setDefault('cooperative', 0);
            }
        }
    }

    public function getCooperative() {
        return array('Non', 'Oui');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

    public function getSocieteTypes() {
        $societeTypes = SocieteClient::getInstance()->getSocieteTypes();

        return $societeTypes;
    }

    public function getSocieteTypesValid() {
        $societeType = $this->getSocieteTypes();
        $types = array();
        foreach ($societeType as $types) {
            if (!is_array($types))
                $result[] = $types;
            else {
                foreach ($types as $entree) {
                    $result[] = $entree;
                }
            }
        }
        return $result;
    }

}

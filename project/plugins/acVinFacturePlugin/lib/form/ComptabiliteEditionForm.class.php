<?php

class ComptabiliteEditionForm extends acCouchdbObjectForm {

    const NOUVELLE_LIGNE = "nouvelle";

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {

        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {


        $this->getObject()->getOrAdd('identifiants_analytiques')->add(self::NOUVELLE_LIGNE);

        foreach ($this->getObject()->getOrAdd('identifiants_analytiques') as $iaKey => $identifiant_analytique) {


            $this->setWidget("identifiant_analytique_numero_compte_" . $iaKey, new sfWidgetFormInput());
            $this->setWidget("identifiant_analytique_" . $iaKey, new sfWidgetFormInput());
            $this->setWidget("identifiant_analytique_libelle_compta_" . $iaKey, new sfWidgetFormInput());


            $this->setValidator("identifiant_analytique_numero_compte_" . $iaKey, new sfValidatorNumber(array("required" => false)));
            $this->setValidator("identifiant_analytique_" . $iaKey, new sfValidatorNumber(array("required" => false)));
            $this->setValidator("identifiant_analytique_libelle_compta_" . $iaKey, new sfValidatorString(array('required' => false)));
        }
        $this->widgetSchema->setNameFormat('comptabilite_edition[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->remove('identifiants_analytiques');
        $identifiants_analytiques = $this->getObject()->getOrAdd('identifiants_analytiques');
        foreach ($values as $key => $value) {
            $matches = array();
            if (preg_match('/^identifiant_analytique([a-z_]*)_([0-9]+_[0-9a-z]+)/', $key, $matches)) {
                if (!$matches[1]) {
                    $identifiants_analytiques->getOrAdd($matches[2])->identifiant_analytique = $value;
                } else {
                    $identifiants_analytiques->getOrAdd($matches[2])->add('identifiant_analytique' . $matches[1], $value);
                }
            }
            if (preg_match('/^identifiant_analytique([a-z_]*)_nouvelle/', $key, $matches)
                && $values['identifiant_analytique_numero_compte_nouvelle'])  {
                $keyid = $values['identifiant_analytique_numero_compte_nouvelle'] . '_' . $values['identifiant_analytique_nouvelle'];
                if (!$values['identifiant_analytique_nouvelle']) {
                    $keyid = $values['identifiant_analytique_numero_compte_nouvelle'] . '_' . md5($values['identifiant_analytique_libelle_compta_nouvelle']);
                }
                $newNode = $identifiants_analytiques->getOrAdd($keyid);
                if (!$matches[1]) {
                    $newNode->identifiant_analytique = $value;
                } else {
                    $newNode->add('identifiant_analytique' . $matches[1], $value);
                }
            }
        }
        $verif_ia = clone $identifiants_analytiques;
        foreach($verif_ia as $key => $value) {
            if (!$value->identifiant_analytique_numero_compte) {
                $identifiants_analytiques->remove($key);
            }
        }
    }

    public function setDefaults($defaults) {
        parent::setDefaults($defaults);

        foreach ($this->getObject()->getOrAdd('identifiants_analytiques') as $iaKey => $identifiant_analytique) {
            $this->setDefault("identifiant_analytique_numero_compte_" . $iaKey, $identifiant_analytique->identifiant_analytique_numero_compte);
            $this->setDefault("identifiant_analytique_" . $iaKey, $identifiant_analytique->identifiant_analytique);
            $this->setDefault("identifiant_analytique_libelle_compta_" . $iaKey, $identifiant_analytique->identifiant_analytique_libelle_compta);
        }
    }

}

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
        $this->setWidget('code_comptable_client', new bsWidgetFormInput());

        $this->setWidget('siret', new bsWidgetFormInput());
        $this->setWidget('code_naf', new bsWidgetFormInput());
        $this->setWidget('no_tva_intracommunautaire', new bsWidgetFormInput());

        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->widgetSchema->setLabel('raison_sociale', 'Nom de la société *');
        $this->widgetSchema->setLabel('code_comptable_client', 'Code comptable');

        $this->widgetSchema->setLabel('siret', 'SIRET');
        $this->widgetSchema->setLabel('code_naf', 'Code Naf');
        $this->widgetSchema->setLabel('no_tva_intracommunautaire', 'TVA Intracom.');

        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('code_comptable_client', new sfValidatorString(array('required' => false)));


        $this->setValidator('siret', new sfValidatorRegex(array("required" => false, "pattern" => "/^[0-9]{14}$/"), array("invalid" => "Le siret doit être un nombre à 14 chiffres")));
        $this->setValidator('code_naf', new sfValidatorString(array('required' => false)));
        $this->setValidator('no_tva_intracommunautaire', new sfValidatorString(array('required' => false)));

        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));

        $this->setWidget('societe_maison_mere', new WidgetSociete(['interpro_id' => $this->getObject()->interpro]));
        $this->widgetSchema->setLabel('societe_maison_mere', 'Maison mère de la société');
        $this->widgetSchema->setHelp('societe_maison_mere', 'dans le cas où la société maison mère est une autre société');
        $this->setValidator('societe_maison_mere', new ValidatorSociete(array('required' => false)));

        $this->widgetSchema->setNameFormat('societe_modification[%s]');
    }

    public function doUpdateObject($values) {
        if($values['code_comptable_client'] === "" || is_null($values['code_comptable_client'])) {
            $values['code_comptable_client'] = ($this->getObject()->getIdentifiant()*1)."";
        }
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

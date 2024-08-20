<?php

class EtablissementRelationChaiForm extends acCouchdbForm {
    protected $etablissementRelation = null;
    protected $etablissementChai = null;
    protected $typeLiaison = null;

    public function __construct(acCouchdbDocument $doc, $typeLiaison, $etablissementRelation, $etablissementChai, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->typeLiaison = $typeLiaison;
        $this->etablissementRelation = $etablissementRelation;
        $this->etablissementChai = $etablissementChai;
        parent::__construct($doc, $defaults, $options, $CSRFSecret);

        $this->setDefault('attributs_chai', array(EtablissementClient::CHAI_ATTRIBUT_APPORT => EtablissementClient::CHAI_ATTRIBUT_APPORT));
    }

    public function configure() {
        parent::configure();

        $this->setWidget('hash_chai', new sfWidgetFormChoice(array('expanded' => false, 'multiple' => false, 'choices' => $this->getChais())));
        $this->widgetSchema->setLabel('hash_chai', 'Chai :');
        $this->setValidator('hash_chai', new sfValidatorChoice(array('required' => false, 'multiple' => false, 'choices' => array_keys($this->getChais()))));

        $this->setWidget('attributs_chai', new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true, 'choices' => $this->getAttributs())));
        $this->widgetSchema->setLabel('attributs_chai', 'Attributs :');
        $this->setValidator('attributs_chai', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($this->getAttributs()))));

        $this->widgetSchema->setNameFormat('etablissement_relation[%s]');
    }

    public function save() {
        $chai = null;
        $attributsChai = null;
        if($this->getValue('hash_chai')) {
            $chai = $this->etablissementChai->get($this->getValue('hash_chai'));
            $attributsChai = $this->getValue('attributs_chai');
        }
        $liaison = $this->getDocument()->addLiaison($this->typeLiaison, $this->etablissementRelation, true, $chai, $attributsChai);
        $this->getDocument()->save();

        return $liaison;
    }

    public function getChais() {
        $chais = array();
        if($this->etablissementChai->exist('chais')) {
            foreach($this->etablissementChai->chais as $chai) {
                $chais[$chai->getHash()] = $chai->nom." - ".$chai->adresse.", ".$chai->code_postal." ".$chai->commune;
            }
        }
        return array_merge(array("" => ""), $chais);
    }

    public function getAttributs(){
       $attributs = EtablissementClient::$chaisAttributsLibelles;
       asort($attributs);

       return $attributs;
    }

}

<?php

class EtablissementForm extends acCouchdbObjectForm
{
	protected $updatedValues;
	protected $coordonneesEtablissement = null;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
        $this->updatedValues = array();
    }

     public function configure() {
       $this->setWidgets(array(
         // "siret" => new sfWidgetFormInput(array("label" => "N° SIRET")),
		    "ppm" => new sfWidgetFormInput(array("label" => "N° PPM")),
            "adresse" => new sfWidgetFormInput(array("label" => "Adresse")),
            "commune" => new sfWidgetFormInput(array("label" => "Commune")),
            "code_postal" => new sfWidgetFormInput(array("label" => "Code Postal")),
            "telephone_bureau" => new sfWidgetFormInput(array("label" => "Tél. Bureau")),
						"telephone_mobile" => new sfWidgetFormInput(array("label" => "Tél. Mobile")),
            "email" => new sfWidgetFormInput(array("label" => "Email")),
			"chais_nom" =>  new sfWidgetFormInput(array("label" => "Nom")),
			"chais_adresse" =>  new sfWidgetFormInput(array("label" => "Adresse")),
			"chais_commune" =>  new sfWidgetFormInput(array("label" => "Commune")),
			"chais_code_postal" =>  new sfWidgetFormInput(array("label" => "Code postal")),
			"chais_telephone" => new sfWidgetFormInput(array("label" => "Tél.")),
        ));
	$ppmMsg = 'Le PPM doit impérativement commencer par une lettre suivie de 8 chiffres';
        $this->setValidators(array(
            //'siret' => new sfValidatorRegex(array("required" => false, "pattern" => "/^[0-9]{14}$/"), array("invalid" => "Le siret doit être un nombre à 14 chiffres")),
			'ppm' =>  new sfValidatorRegex(array('required' => false,
											'pattern' => "/^[A-Z]{1}[0-9]{8}$/",
											'min_length' => 9,
											'max_length' => 9),
											array('invalid' => $ppmMsg." (invalid)",
											'min_length' => $ppmMsg." (taille min)",
											'max_length' => $ppmMsg." (taille max)",
										)),
            'adresse' => new sfValidatorString(array("required" => false)),
            'commune' => new sfValidatorString(array("required" => false)),
            'code_postal' => new sfValidatorString(array("required" => false)),
            'telephone_bureau' => new sfValidatorString(array("required" => false)),
						'telephone_mobile' => new sfValidatorString(array("required" => false)),
       	    'email' => new sfValidatorEmailStrict(array("required" => false)),
			'chais_nom' => new sfValidatorString(array("required" => false)),
			'chais_adresse' => new sfValidatorString(array("required" => false)),
			'chais_commune' => new sfValidatorString(array("required" => false)),
			'chais_code_postal' => new sfValidatorString(array("required" => false)),
			"chais_telephone" => new sfValidatorString(array("required" => false)),
        ));

		if(!DRevConfiguration::getInstance()->hasLogementAdresse()) {
			unset($this->widgetSchema['chais_nom']);
			unset($this->validatorSchema['chais_nom']);
			unset($this->widgetSchema['chais_adresse']);
			unset($this->validatorSchema['chais_adresse']);
			unset($this->widgetSchema['chais_commune']);
			unset($this->validatorSchema['chais_commune']);
			unset($this->widgetSchema['chais_code_postal']);
			unset($this->validatorSchema['chais_code_postal']);
			unset($this->widgetSchema['chais_telephone']);
			unset($this->validatorSchema['chais_telephone']);
		}

        if(!$this->getOption("use_email")) {
            $this->getValidator('email')->setOption('required', false);
        }

        if($this->getObject()->exist('siren') && $this->getObject()->identifiant == $this->getObject()->siren) {
            unset($this['siret']);
        }

        $this->widgetSchema->setNameFormat('etablissement[%s]');
    }

	private function getCoordonneesEtablissement() {
		if (!$this->coordonneesEtablissement) {
			$this->coordonneesEtablissement = $this->getObject();
		}
		return $this->coordonneesEtablissement;
	}

	public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $this->getCoordonneesEtablissement();

		if(DRevConfiguration::getInstance()->hasLogementAdresse() && $this->getObject()->getDocument()->isAdresseLogementDifferente()) {
			$this->setDefault('chais_nom', $this->getObject()->getDocument()->chais->nom);
			$this->setDefault('chais_adresse', $this->getObject()->getDocument()->chais->adresse);
			$this->setDefault('chais_commune', $this->getObject()->getDocument()->chais->commune);
			$this->setDefault('chais_code_postal', $this->getObject()->getDocument()->chais->code_postal);
			$this->setDefault('chais_telephone', $this->getObject()->getDocument()->chais->telephone);
		}
    }

    public function save($con = null) {

        parent::save($con);
    }

    public function doUpdateObject($values) {
    	foreach ($this as $field => $widget) {
    		if (!$widget->isHidden()) {
    			if ($this->getObject()->exist($field) && $this->getObject()->get($field) != $values[$field]) {
    				$this->updatedValues[$field] = array($this->getObject()->get($field), $values[$field]);
    			}
    		}
    	}
		parent::doUpdateObject($values);
        if (DRevConfiguration::getInstance()->hasLogementAdresse() && $this->getObject()->getDocument()->exist('chais')) {
            $this->getObject()->getDocument()->chais->nom = $values['chais_nom'];
			$this->getObject()->getDocument()->chais->adresse = $values['chais_adresse'];
			$this->getObject()->getDocument()->chais->commune = $values['chais_commune'];
			$this->getObject()->getDocument()->chais->code_postal = $values['chais_code_postal'];
			$this->getObject()->getDocument()->chais->telephone = $values['chais_telephone'];

			if(!$this->getObject()->getDocument()->isAdresseLogementDifferente()) {
			    $this->getObject()->getDocument()->remove('chais');
			    $this->getObject()->getDocument()->add('chais');
			}
        }

	}

    public function getUpdatedValues()
    {
    	return $this->updatedValues;
    }

    public function hasUpdatedValues()
    {
    	return (count($this->updatedValues) > 0);
    }


}

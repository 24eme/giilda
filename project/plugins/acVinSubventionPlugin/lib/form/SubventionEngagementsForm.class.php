<?php

class SubventionEngagementsForm extends acCouchdbForm {

    public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->engagements = $doc->getConfiguration()->getEngagements();
        $this->engagementsPrecisions = $doc->getConfiguration()->getEngagementsPrecisions();
        foreach ($doc->engagements as $key => $value) {
            $defaults["engagement_$key"] = 1;
        }
        foreach ($doc->engagements_precisions as $key => $value) {
            $defaults["precision_engagement_$key"] = 1;
        }
        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function getEngagements() {
        return $this->engagements;
    }

    public function getEngagementsPrecisions() {
        return $this->engagementsPrecisions;
    }

    public function configure() {
        foreach ($this->engagements as $key => $libelle) {
	        $this->setWidget("engagement_$key", new sfWidgetFormInputCheckbox());
	        $this->getWidget("engagement_$key")->setLabel($libelle);
	        $this->setValidator("engagement_$key", new sfValidatorBoolean(array('required' => true)));
	    }
	    foreach ($this->engagementsPrecisions as $eng => $precisions) {
	        foreach ($precisions as $k => $libelle) {
	            $key = "$eng/$k";
    	        $this->setWidget("precision_engagement_$key", new sfWidgetFormInputCheckbox());
    	        $this->getWidget("precision_engagement_$key")->setLabel($libelle);
    	        $this->setValidator("precision_engagement_$key", new sfValidatorBoolean(array('required' => false)));
	        }
	    }
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('subvention_engagements[%s]');
    }

    public function save() {
        $values = $this->getValues();
        $this->getDocument()->remove('engagements');
        $this->getDocument()->add('engagements');
	    foreach ($this->engagements as $key => $libelle) {
	        if (isset($values["engagement_$key"]) && $values["engagement_$key"]) {
	            $this->getDocument()->engagements->add($key, true);
	        }
	    }
        foreach ($this->engagementsPrecisions as $eng => $precisions) {
           foreach ($precisions as $k => $libelle) {
               $key = "$eng/$k";
               if (isset($values["precision_engagement_$key"]) && $values["precision_engagement_$key"]) {
                   $this->getDocument()->engagements_precisions->add($key, 1);
               }
           }
        }
        $this->getDocument()->save();
    }

}

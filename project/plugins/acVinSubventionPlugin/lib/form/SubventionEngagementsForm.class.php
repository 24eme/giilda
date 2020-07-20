<?php

class SubventionEngagementsForm extends acCouchdbForm {

    public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->engagements = $doc->getConfiguration()->getEngagements();
        $this->engagementsPrecisions = $doc->getConfiguration()->getEngagementsPrecisions();
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

        $this->getDocument()->save();
    }

}

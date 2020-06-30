<?php

class MessageForm extends acCouchdbForm {

    protected $messageId;

    public function __construct(acCouchdbDocument $doc, $messageId) {

        $this->messageId = $messageId;

        $defaults = array();
        $defaults['message'] = $doc->get($this->messageId);

        parent::__construct($doc, $defaults, array(), null);
    }

    public function configure() {
    	$this->setWidgets(array(
    		'message' => new bsWidgetFormInput(array(), array('placeholder' => "Saisir un message")),
    	));
    	$this->setValidators(array(
    		'message' => new sfValidatorString(array('required' => false)),
    	));
		$this->widgetSchema->setLabels(array(
			'message' => $this->messageId,
		));

        $this->widgetSchema->setNameFormat('messages[%s]');
    }

    public function save() {
        $this->getDocument()->add($this->messageId);
        $this->getDocument()->set($this->messageId, $this->getValue('message') ? $this->getValue('message') : null);
        $this->getDocument()->save();
    }
}

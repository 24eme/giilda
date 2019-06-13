<?php

class FactureChoixCampagneForm extends sfForm
{
    private $campagne;

    public function __construct($campagne = null)
    {
        if ($campagne === null) {
            $campagne = date('Y');
        }
        $this->campagne = $campagne;
        parent::__construct();
    }

    public function configure()
    {
        $year = date('Y');
        $range = range($year, $year - 10);

        $list = [];
        foreach($range as $year) {
            $list[$year] = $year;
        }

        $this->setWidgets([
            'campagne' => new sfWidgetFormChoice(['choices' => $list, 'default' => $this->campagne])
        ]);
        $this->setValidators([
            'campagne' => new sfValidatorChoice(['required' => true, 'choices' => $list])
        ]);
        $this->widgetSchema->setLabels([
            'campagne' => 'Année de facturation'
        ]);

        $this->widgetSchema->setNameFormat('facturecampagne[%s]');
    }
}

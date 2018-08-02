<?php
class DAESCSVUploadForm extends BaseForm
{

    private $periode = null;
    private $identifiant = null;

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->periode = $options['periode'];
        $this->identifiant = $options['identifiant'];
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'file' => new sfWidgetFormInputFile()
        ));
        $this->widgetSchema->setLabels(array(
            'file' => "Fichier d'import de vos DAE"
        ));
        $this->setValidators(array(
            'file' => new ValidatorImportCsv(array('file_path' => sfConfig::get('sf_data_dir') . '/upload'))
        ));

        $this->widgetSchema->setNameFormat('daes_upload[%s]');
    }




}

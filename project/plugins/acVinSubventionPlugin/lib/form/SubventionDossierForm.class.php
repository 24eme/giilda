<?php

/**
 * Description of SubventionDossierForm
 *
 * @author mathurin
 */
class SubventionDossierForm extends BaseForm {

    protected $subvention;

  	public function __construct($subvention, $defaults = array(), $options = array(), $CSRFSecret = null){

      $this->subvention = $subvention;

      parent::__construct($defaults, $options, $CSRFSecret);

    }

    public function configure() {

      $this->setWidgets(array(
          'file' => new sfWidgetFormInputFile()
      ));
      $this->widgetSchema->setLabels(array(
          'file' => "Dossier"
      ));
      $this->setValidators(array(
          'file' => new ValidatorImportXls(array('file_path' => sfConfig::get('sf_data_dir') . '/subventions'))
      ));
      $this->widgetSchema->setNameFormat('subvention_dossier[%s]');

    }

    public function save() {
      $file = $this->getValue('file');
      if (!$file && $this->subvention->isNew()) {
        throw new sfException("Une erreur lors de l'upload est survenue");
      }
      if ($file && !$file->isSaved()) {
        $file->save();
      }

      $isNew = false;
      if ($this->subvention->isNew()) {
        $this->subvention->save();
        $isNew = true;
      }
      if ($file) {
        try {
          $this->subvention->storeDossier($file->getSavedName());
          $date = new \DateTime( 'now');
          $this->subvention->dossier_date = $date->format('Y-m-d H:i:s');
        } catch (sfException $e) {
          if ($isNew) {
            $this->subvention->remove();
          }
          throw new sfException($e);
        }
        unlink($file->getSavedName());
      }

      $this->subvention->save();
      return $this->subvention;
    }



}

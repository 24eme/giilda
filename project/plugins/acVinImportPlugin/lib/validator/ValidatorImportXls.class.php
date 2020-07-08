<?php

class ValidatorImportXls extends sfValidatorFile
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('file_path');
    $this->addMessage('invalid_file', "Le fichier fourni ne peut être lu");
    $options['required'] = true;
    parent::configure($options, $messages);
    $this->setMessage('mime_types', "Le fichier fourni doit être un xls");
    $this->options['mime_types'] = array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

  }

  protected function doClean($value)
  {
	parent::doClean($value);
    $xlsValidated = new XlsValidatedFile($value['name'], 'application/vnd.ms-excel', $value['tmp_name'], $value['size'], $this->getOption('file_path'));

    $errorSchema = new sfValidatorErrorSchema($this);

    $xlsValidated->save();

    try {
      // verif??
      $xlsValidated->getSavedName();
    }catch(Exception $e) {
      $xls = null;
      $errorSchema->addError(new sfValidatorError($this, $e->getMessage()));
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }

    $xlsValidated->setxls($xls);
    return $xlsValidated;
  }

}

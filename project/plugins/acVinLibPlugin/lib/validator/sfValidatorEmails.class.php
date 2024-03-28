<?php

class sfValidatorEmails extends sfValidatorString
{
  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    foreach(explode(';', $clean) as $email) {
        if (!preg_match(sfValidatorEmail::REGEX_EMAIL, $email)) {
            throw new sfValidatorError($this, 'invalid', array('value' => $email));
        }
    }

    return $clean;
  }
}

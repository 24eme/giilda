<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CompteNewGroupeForm extends baseForm {

    public function configure()
    {

      $this->setWidget('nom_groupe', new bsWidgetFormInput());
      $this->widgetSchema->setLabel('nom_groupe', 'Nom du groupe : ');
      $this->setValidator('nom_groupe', new sfValidatorString(array('required' => false)));
      $this->widgetSchema->setNameFormat('compte_groupe_nom_ajout[%s]');
    }

}

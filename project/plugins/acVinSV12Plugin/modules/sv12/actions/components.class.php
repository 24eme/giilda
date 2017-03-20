<?php


class sv12Components extends sfComponents {

    public function executeFormEtablissementChoice() {
        if (!$this->identifiant) {
            $this->identifiant = null;
        }
        $autofocus = array();
        if ($this->autofocus) {
            $autofocus = array('autofocus' => 'autofocus');
        }

        if (!$this->form) {
            $this->form = new SV12EtablissementChoiceForm('INTERPRO-declaration', array('identifiant' => $this->identifiant), $autofocus);
        }
    }

    public function executeStocksRecap() {
        $this->sv12 = SV12Client::getInstance()->findMaster($this->etablissement->identifiant, $this->campagne);
    }
}

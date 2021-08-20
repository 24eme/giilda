<?php

class DSProduitDetailsForm extends acCouchdbObjectForm {

    public function configure() {
		foreach ($this->getObject()->detail as $key => $value) {
			$this->embedForm($key, new DSProduitDetailStocksForm($value));
		}
        $this->widgetSchema->setNameFormat('[%s]');
    }

}

<?php

class globalActions extends sfActions {
	public function executeError500(sfWebRequest $request) {
       $this->exception = $request->getParameter('exception');
	}
	public function executeHome(sfWebRequest $request) {
        if($this->getUser()->hasCredential('transactions')) {
	        return $this->redirect('vrac');
        }

        return $this->redirect('societe');
	}
}

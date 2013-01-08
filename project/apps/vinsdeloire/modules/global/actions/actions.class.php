<?php

class globalActions extends sfActions {
	public function executeError500(sfWebRequest $request) {
	  $this->exception = $request->getParameter('exception');
	}
}

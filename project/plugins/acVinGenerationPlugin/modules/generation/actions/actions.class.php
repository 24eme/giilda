<?php
class generationActions extends sfActions {
    
  public function executeFacture(sfWebRequest $request) {
      $this->generation = GenerationClient::getInstance()->find(GenerationClient::getInstance()->getId($request['type_document'], $request['date_emission']));

  }

    
}

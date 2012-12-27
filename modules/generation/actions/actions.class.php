<?php
class generationActions extends sfActions {
    
  public function executeView(sfWebRequest $request) {
      $this->type = $request['type_document'];
      $this->identifiant = isset($request['identifiant'])? $request['identifiant'] : null;
      $this->nom = ($this->identifiant)? EtablissementClient::getInstance()->retrieveById($this->identifiant)->nom : null;
      $this->generation = GenerationClient::getInstance()->find(GenerationClient::getInstance()->getId($this->type, $request['date_emission']));
      $this->forward404Unless($this->generation);

  }
  
public function executeList(sfWebRequest $request) {
      $this->type = $request['type_document'];
      $this->historyGeneration = GenerationClient::getInstance()->findHistoryWithType($this->type);
  }
    
}

 <?php
class stocksActions extends sfActions {
    public function executeIndex(sfWebRequest $request) {    
        $this->form = new StocksEtablissementChoiceForm('INTERPRO-inter-loire');
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                
                return $this->redirect('stocks_etablissement', $this->form->getEtablissement());
            }
        }

        $this->setTemplate('index');
    }

    public function executeMonEspace(sfWebRequest $request) {    
        $this->etablissement = $this->getRoute()->getEtablissement();
    }   
}
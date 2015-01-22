<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteLatex
 * @author mathurin
 */
class RelanceLatex extends GenericLatex {

  private $relance = null;
  const RELANCE_OUTPUT_TYPE_PDF = 'pdf';
  const RELANCE_OUTPUT_TYPE_LATEX = 'latex';


  function __construct(Relance $a, $config = null) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->relance = $a;
  }

  public function getNbPages() {
       return intval(exec("/usr/bin/pdfinfo ".$this->getPDFFile()." | grep 'Pages' | sed 's/Pages:[ ]*//'"));
  }
  
  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->relance->_id.'_'.$this->relance->_rev;
  }


  public function getLatexFileContents() {        
        $t = html_entity_decode(htmlspecialchars_decode(
                        get_partial('relance/latexContent_'.$this->relance->type_relance, array('relance' => $this->relance))
                        , HTML_ENTITIES));
        return $t;
    
  }

  public function getPublicFileName($extention = '.pdf') {
    return 'relance_'.$this->relance->_id.'_'.$this->relance->_rev.$extention;
  }

}

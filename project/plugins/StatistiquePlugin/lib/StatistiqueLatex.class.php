<?php
class StatistiqueLatex extends GenericLatex 
{
	protected $csv;
	protected $type;
	protected $options;

  	function __construct($csv, $type, $options = array()) 
  	{
    	sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    	$this->csv = $csv;
    	$this->type = $type;
    	$this->options = $options;
  	}

  	public function getNbPages() 
  	{
    	return 1;
  	}

  	public function getLatexFileNameWithoutExtention() 
  	{
    	return $this->getTEXWorkingDir().'statistiques_'.$this->type.'_'.date('YmdHi');
  	}

  	public function getLatexFileContents() 
  	{
    	return html_entity_decode(htmlspecialchars_decode(get_partial('statistique/pdf_'.$this->type, array('csv' => $this->csv, 'options' => $this->options)), HTML_ENTITIES));
  	}

  	public function getPublicFileName($extention = '.pdf') 
  	{
    	return 'statistiques_'.$this->type.'_'.date('YmdHi').$extention;
  	}

  	public function generatePDF() {
	    $cmdCompileLatex = '/usr/bin/latexmk -pdf -output-directory="'.$this->getTEXWorkingDir().'" -interaction=nonstopmode "'.$this->getLatexFile().'" 2>&1';
	    exec($cmdCompileLatex);
	    $pdfpath = $this->getLatexFileNameWithoutExtention().'.pdf';
	    if (!file_exists($pdfpath)) {
	      throw new sfException("pdf not created ($pdfpath): ".$output);
	    }
	    return $pdfpath;
  	}
}
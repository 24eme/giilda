<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  	public function setup()
  	{
        $this->enablePlugins('acLessphpPlugin');
		$this->enablePlugins('acCouchdbPlugin');
		$this->enablePlugins('acVinImportPlugin');
        $this->enablePlugins('acVinLibPlugin');
		//$this->enablePlugins('acVinComptePlugin');
        $this->enablePlugins('UserPlugin');
		$this->enablePlugins('acVinVracPlugin');        
		$this->enablePlugins('DRMPlugin');
		$this->enablePlugins('acVinDRMPlugin');
		// $this->enablePlugins('TiersPlugin');
        $this->enablePlugins('acVinEtablissementPlugin');
		$this->enablePlugins('acVinConfigurationPlugin');
		$this->enablePlugins('InterproPlugin');
		$this->enablePlugins('ImportPlugin');
		$this->enablePlugins('acVinFacturePlugin');
                $this->enablePlugins('acVinSV12Plugin');
                 $this->enablePlugins('acVinGenerationPlugin');

  	}
}

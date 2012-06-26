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
		$this->enablePlugins('acVinVracPlugin');
		$this->enablePlugins('VracPlugin');
		$this->enablePlugins('DrmPlugin');
		$this->enablePlugins('TiersPlugin');
		$this->enablePlugins('ConfigurationPlugin');
  	}
}

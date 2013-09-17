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
	    $this->enablePlugins('DRMPlugin');
	    $this->enablePlugins('acVinDRMPlugin');
        $this->enablePlugins('acVinEtablissementPlugin');
	    $this->enablePlugins('acVinConfigurationPlugin');
	    $this->enablePlugins('InterproPlugin');
	    $this->enablePlugins('ImportPlugin');
	    $this->enablePlugins('acVinFacturePlugin');
        $this->enablePlugins('acVinSV12Plugin');
        $this->enablePlugins('acVinGenerationPlugin');
        $this->enablePlugins('acVinDocumentPlugin');
        $this->enablePlugins('acVinDSPlugin');
        $this->enablePlugins('acVinRevendicationPlugin');
        $this->enablePlugins('acVinAlertePlugin');
        $this->enablePlugins('acVinSocietePlugin');
        $this->enablePlugins('acVinStocksPlugin');
        $this->enablePlugins('acVinComptePlugin');
        $this->enablePlugins('acElasticaPlugin');
        $this->enablePlugins('acVinRelancePlugin');
        $this->enablePlugins('acLdapPlugin');
        $this->enablePlugins('acExceptionNotifierPlugin');

	    $this->dispatcher->connect('application.throw_exception', array('acError500', 'handleException'));
  	}
}

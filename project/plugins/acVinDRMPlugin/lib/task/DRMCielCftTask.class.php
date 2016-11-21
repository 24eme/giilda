<?php

class DRMCielCftTask extends sfBaseTask
{
  protected function configure()
  {

  	$this->addArguments(array(
      new sfCommandArgument('target', sfCommandArgument::REQUIRED, 'Cible contenant les DRM en retour de CIEL'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', 0),
    ));

    $this->namespace        = 'drm';
    $this->name             = 'ciel-cft';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $checkingMode = $options['checking'];
    $contextInstance = sfContext::createInstance($this->configuration);
    $list = simplexml_load_file($arguments['target'], 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $rapport = array();
    if ($list !== FALSE) {
    	foreach ($list->children() as $item) {
    		$xmlIn = simplexml_load_file($item, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    		if ($list !== FALSE) {
    			$ea = (string) $xmlIn->{"declaration-recapitulative"}->{"identification-declarant"}->{"numero-agrement"};
    			$periode = sprintf("%4d%02d", (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"annee"}, (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"mois"});
    			if ($drm = DRMCielView::getInstance()->findByAccisesPeriode($ea, $periode)) {
    				$drmCiel = $drm->getOrAdd('transmission_douane');
    				if (!$drmCiel->coherente) {
    					if ($xml = $contextInstance->getController()->getAction('drm_xml', 'main')->getPartial('drm_xml/xml', array('drm' => $drm))) {
    						$xmlOut = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    						$compare = new DRMCielCompare($xmlIn, $xmlOut);
    						if (!$compare->hasDiff()) {
    							if (!$checkingMode) {
    								$drm->transmission_douane->coherente = 1;
    								$drm->save();
    							}
    							$rapport[] = 'OK // La DRM '.$drm->_id.' a été validée avec succès';
    						} else {
    							$exist = false;
    							if ($drm->isVersionnable()) {
    								if (!$checkingMode) {
	    								$drm_rectificative = $drm->generateModificative();
		    							$drm_rectificative->add('transmission_douane', $drm->transmission_douane);
		    							$drm_rectificative->transmission_douane->xml = null;
		    							$drm_rectificative->transmission_douane->diff = $xmlIn->asXML();
		    							$drm_rectificative->save();
    								}
	    							$diffs = '<ul>';
	    							foreach ($compare->getDiff()as $k => $v) {
	    								$diffs .= "<li>$k : $v</li>";
	    							}
	    							$diffs .= '</ul>';
	    							$rapport[] = 'ATT // La DRM '.$drm->_id.' doit être rectifiée suite aux modifications suivantes : '.$diffs;
    							} else {
    								$rapport[] = 'La DRM '.$drm->_id.' à déjà été traitée';
    							}
    						}
    					} else {
    						$rapport[] = 'Oups // Impossible de générer le XML de La DRM '.$drm->_id;
    					}
    					
    				} else {
    					$rapport[] = 'La DRM '.$drm->_id.' à déjà été traitée';
    				}
    			} else {
    				$rapport[] = 'Oups // La DRM '.$periode.' de l\'établissement '.$ea.' n\'a pas été saisie sur le portail interprofessionnel: '.$item;
    			}
    		} else {
    			$rapport[] = 'Oups // Impossible d\'interroger la DRM : '.$item;
    		}
    	}
    } else {
		$rapport[] = 'Oups // Impossible d\'interroger le service : '.$arguments['target'];
    }
    $s = '<ul>';
    foreach ($rapport as $item) {
    	$s .= '<li>'.$item.'</li>';
    }
    $s .= '</ul>';
    if ($checkingMode) {
    	echo str_replace("<li>", "\t", str_replace(array("<ul>", "</ul>", "</li>"), "\n", implode("\n", $rapport)));
    } else {
	    $to = sfConfig::get('app_ac_exception_notifier_email');
	    $to = ($to && isset($to->to)) ? $to->to : 'vins@actualys.com';
	    $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $to, "Giilda // Rapport CFT", $s)->setContentType('text/html');
	    $this->getMailer()->sendNextImmediately()->send($message);
    }
  }
}

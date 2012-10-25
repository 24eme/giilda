<?php

class RevendicationRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        
        $r = $event->getSubject();
        $r->prependRoute('revendication_upload', new sfRoute('/revendication', array('module' => 'revendication',
								  'action' => 'upload')));
        
        $r->prependRoute('revendication_uploadCSV', new sfRoute('/revendication/:odg/:campagne/upload', array('module' => 'revendication',
                                                            'action' => 'uploadCSV')));
        
//	$r->prependRoute('revendication_viewupload', new sfRoute('/revendication/:md5', array('module' => 'revendication',
//                                                                  'action' => 'viewupload')));

        
        $r->prependRoute('revendication_downloadCSV', new sfRoute('/revendication/:md5/csv', array('module' => 'revendication',
                                                                  'action' => 'downloadCSV')));
        
        $r->prependRoute('revendication_create', new sfRoute('/revendication/:odg/:campagne/create', array('module' => 'revendication',
                                                                  'action' => 'create')));

        $r->prependRoute('revendication_view_erreurs', new RevendicationRoute('/revendication/:odg/:campagne/erreurs', array('module' => 'revendication',
                                                            'action' => 'viewErreurs'),
                                                            array('sf_method' => array('get', 'post')),
                                                            array('model' => 'Revendication',
                                                                'type' => 'object')));
        
        $r->prependRoute('revendication_edition', new RevendicationRoute('/revendication/:odg/:campagne/edition', array('module' => 'revendication',
                                                            'action' => 'edition'),
                                                            array('sf_method' => array('get', 'post')),
                                                            array('model' => 'Revendication',
                                                                'type' => 'object')));
        
        $r->prependRoute('revendication_edition_row', new RevendicationRoute('/revendication/:odg/:campagne/edition-row/:cvi/:row', array('module' => 'revendication',
                                                            'action' => 'editionRow'),
                                                            array('sf_method' => array('get', 'post')),
                                                            array('model' => 'Revendication',
                                                                'type' => 'object')));
        
        $r->prependRoute('revendication_add_alias_to_configuration', new RevendicationRoute('/revendication/:odg/:campagne/add-alias/:alias', array('module' => 'revendication',
                                                                  'action' => 'addAliasToProduit'),
                                                                    array('sf_method' => array('get', 'post')),
                                                                    array('model' => 'Revendication',
                                                                    'type' => 'object')));
        
        
    }
}

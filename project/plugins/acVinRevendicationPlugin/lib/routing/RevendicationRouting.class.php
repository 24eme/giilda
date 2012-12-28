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

        $r->prependRoute('revendication', new sfRoute('/revendication', array('module' => 'revendication',
                    'action' => 'index')));

        $r->prependRoute('revendication_upload', new RevendicationRoute('/revendication-import/odg/:odg/:campagne/upload', array('module' => 'revendication',
                    'action' => 'upload'), array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));

        $r->prependRoute('revendication_update', new RevendicationRoute('/revendication-import/odg/:odg/:campagne/update', array('module' => 'revendication',
                    'action' => 'update'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));

        $r->prependRoute('revendication_view_erreurs', new RevendicationRoute('/revendication-import/odg/:odg/:campagne/erreurs', array('module' => 'revendication',
                    'action' => 'viewErreurs'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));

        $r->prependRoute('revendication_etablissement', new EtablissementRoute('/revendication/:identifiant', array('module' => 'revendication',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));



        $r->prependRoute('revendication_choose_etablissement', new sfRoute('/revendication/choix-etablissement', array('module' => 'revendication',
                    'action' => 'chooseEtablissement')));

        /* $r->prependRoute('revendication_uploadCSV', new sfRoute('/revendication/odg/:odg/:campagne/upload', array('module' => 'revendication',
          'action' => 'uploadCSV'))); */

//	$r->prependRoute('revendication_viewupload', new sfRoute('/revendication/:md5', array('module' => 'revendication',
//                                                                  'action' => 'viewupload')));


        $r->prependRoute('revendication_downloadCSV', new sfRoute('/revendication/csv/:odg/:md5', array('module' => 'revendication',
                    'action' => 'downloadCSV')));



        $r->prependRoute('revendication_edition', new RevendicationRoute('/revendication/odg/:odg/:campagne/edition', array('module' => 'revendication',
                    'action' => 'edition'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));

        $r->prependRoute('revendication_edition_create', new RevendicationRoute('/revendication-creation/odg/:odg/:campagne/edition', array('module' => 'revendication',
                    'action' => 'editionCreation'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));

        $r->prependRoute('revendication_edition_row', new sfRoute('/revendication/odg/:odg/:campagne/edition-row/:identifiant/produit/:produit/:row/:retour', array('module' => 'revendication',
                    'action' => 'editionRow', 'retour' => 'odg')));

        $r->prependRoute('revendication_delete_row', new sfRoute('/revendication/odg/:odg/:campagne/delete-row/:identifiant/produit/:produit/:row', array('module' => 'revendication',
                    'action' => 'deleteRow')));

        $r->prependRoute('revendication_add_alias_to_configuration', new RevendicationRoute('/revendication/odg/:odg/:campagne/ajout-alias/:alias', array('module' => 'revendication',
                    'action' => 'addAliasToProduit'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));

        $r->prependRoute('revendication_delete_line', new RevendicationRoute('/revendication/odg/:odg/:campagne/supprimer/:num_ligne/:num_ca', array('module' => 'revendication',
                    'action' => 'deleteLine'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));

        $r->prependRoute('revendication_add_row', new sfRoute('/revendication/odg/:odg/:campagne/ajout-lignes', array('module' => 'revendication',
                    'action' => 'addRows')));

        $r->prependRoute('revendication_delete', new RevendicationRoute('/revendication/odg/:odg/:campagne/supprimer', array('module' => 'revendication',
                    'action' => 'delete'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Revendication',
                            'type' => 'object')));
    }

}

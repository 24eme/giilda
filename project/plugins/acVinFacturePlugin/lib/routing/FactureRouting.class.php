<?php

class FactureRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();
        $r->prependRoute('facture', new sfRoute('/facture', array('module' => 'facture',
								  'action' => 'index')));
        $r->prependRoute('facture_societe', new SocieteRoute('/facture/:identifiant/:campagne', array('module' => 'facture',
													'action' => 'monEspace', 'campagne' => null),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Societe',
									       'type' => 'object')
									 ));

        $r->prependRoute('facture_etablissement', new EtablissementRoute('/facture/etablissement/:identifiant', array('module' => 'facture',
													'action' => 'etablissement'),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Etablissement',
									       'type' => 'object')
									 ));

        $r->prependRoute('facture_generation', new sfRoute('/facture/generation', array('module' => 'facture',
										      'action' => 'generation')));

       $r->prependRoute('facture_generer', new SocieteRoute('/facture/:identifiant/generer', array('module' => 'facture',
													'action' => 'generer'),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Societe',
									       'type' => 'object')
									 ));

       $r->prependRoute('facture_redirect_to_doc', new sfRoute('/facture/redirect/:iddocument', array('module' => 'facture', 'action' => 'redirect')));

        $r->prependRoute('facture_pdf', new FactureRoute('/facture/:identifiant/pdf', array('module' => 'facture',
												 'action' => 'latex'),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Facture',
									       'type' => 'object')
									 ));
        $r->prependRoute('defacturer', new FactureRoute('/facture/:identifiant/defacturer',
                                                                                array('module' => 'facture',
                                                                                      'action' => 'defacturer'),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Facture',
									       'type' => 'object')
                                                                               ));


         $r->prependRoute('facture_debrayage',  new SocieteRoute('/facture/connexion/:identifiant', array('module' => 'facture',
                          'action' => 'connexion'),
                          array('sf_method' => array('get','post')),
                          array('model' => 'Societe',
                                'type' => 'object')
                                ));

        $r->prependRoute('facture_teledeclarant',  new sfRoute('/facture/societe/:identifiant/:campagne', array('module' => 'facture',
                                'action' => 'societe', 'campagne' => null)));

        $r->prependRoute('facture_sepa_modification',  new sfRoute('/facture/societe/:identifiant/sepa/modifier', array('module' => 'facture',
                                'action' => 'sepaModification')));

        $r->prependRoute('facture_sepa_visualisation',  new sfRoute('/facture/societe/:identifiant/sepa/visualisation', array('module' => 'facture',
                                'action' => 'sepaVisualisation')));

        $r->prependRoute('facture_sepa_pdf',  new sfRoute('/facture/societe/:identifiant/sepa/pdf', array('module' => 'facture',
                                'action' => 'sepaLatex')));


    }
}

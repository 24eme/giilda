<?php

/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * DRMRouting configuration.
 * 
 * @package    DRMRouting
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Mathurin Petit <mathurin.petit@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class DRMRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();

        $r->prependRoute('drm', new sfRoute('/drm', array('module' => 'drm',
            'action' => 'chooseEtablissement')));

        $r->prependRoute('drm_etablissement', new EtablissementRoute('/drm/:identifiant/:campagne', array('module' => 'drm',
            'action' => 'monEspace', 'campagne' => null), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('drm_etablissement_static', new EtablissementRoute('/drm-static/:identifiant/:campagne', array('module' => 'drm',
            'action' => 'monEspaceStatic', 'campagne' => null), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('drm_inProcess', new EtablissementRoute('/drm/:identifiant/drm-conflit', array('module' => 'drm',
            'action' => 'inProcess', 'campagne' => null), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));


        $r->prependRoute('drm_etablissement_stocks', new EtablissementRoute('/drm/:identifiant/stocks/:campagne', array('module' => 'drm',
            'action' => 'stocks',
            'campagne' => null), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('drm_historique', new EtablissementRoute('/drm/:identifiant/historique/:campagne', array('module' => 'drm',
            'action' => 'historique',
            'campagne' => null), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('drm_nouvelle', new EtablissementRoute('/drm/:identifiant/nouvelle/:periode', array('module' => 'drm',
            'action' => 'nouvelle',
            'periode' => null), array('sf_method' => array('get')), array('model' => 'Etablissement',
            'type' => 'object')));

        $r->prependRoute('drm_delete', new DRMRoute('/drm/:identifiant/delete/:periode_version', array('module' => 'drm',
            'action' => 'delete'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),)));

        $r->prependRoute('drm_rectificative', new DRMRoute('/drm/:identifiant/rectifier/:periode_version', array('module' => 'drm',
            'action' => 'rectificative'), array(), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('valid'),
        )));

        $r->prependRoute('drm_modificative', new DRMRoute('/drm/:identifiant/modificative/:periode_version', array('module' => 'drm',
            'action' => 'modificative'), array(), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('valid'),
        )));

        $r->prependRoute('drm_redirect_etape', new DRMRoute('/drm/:identifiant/redirect-etape/:periode_version', array('module' => 'drm',
            'action' => 'redirectEtape'), array('sf_method' => array('get')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_validation', new DRMRoute('/drm/:identifiant/edition/:periode_version/validation', array('module' => 'drm_validation',
            'action' => 'validation'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'))));


        $r->prependRoute('drm_redirect_to_visualisation', new sfRoute('/drm/redirect/:identifiant_drm', array('module' => 'drm', 'action' => 'redirect'), array('sf_method' => array('get'))
        ));


        $r->prependRoute('drm_visualisation', new DRMRoute('/drm/:identifiant/visualisation/:periode_version/:hide_rectificative', array('module' => 'drm_visualisation',
            'action' => 'visualisation',
            'hide_rectificative' => null), array('sf_method' => array('get')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('valid'))));



        $r->prependRoute('drm_edition', new DRMRoute('/drm/:identifiant/edition/:periode_version/edition', array('module' => 'drm_edition',
            'action' => 'saisieMouvements'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));



        $r->prependRoute('drm_edition_detail', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/edition/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail', array('module' => 'drm_edition',
            'action' => 'detail'), array('sf_method' => array('get')), array('model' => 'DRMDetail',
            'type' => 'object',
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_edition_produit_ajout', new DRMRoute('/drm/:identifiant/edition/:periode_version/edition/produit-ajout', array('module' => 'drm_edition',
            'action' => 'produitAjout'), array('sf_method' => array('get', 'post')), array('model' => 'DRMAppellation',
            'type' => 'object',
            'add_noeud' => true,
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_edition_update', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/edition/update/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail', array('module' => 'drm_edition',
            'action' => 'update'), array('sf_method' => array('post')), array('model' => 'DRMDetail',
            'type' => 'object',
            'control' => array('edition'),
        )));



        $r->prependRoute('drm_vrac_details', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/details-vrac/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail', array('module' => 'drm_vrac_details',
            'action' => 'produit'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'))));


        $r->prependRoute('drm_export_details', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/details-export/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail', array('module' => 'drm_export_details',
            'action' => 'produit'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'))));

        $r->prependRoute('drm_cooperative_details', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/details-cooperative/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail', array('module' => 'drm_cooperative_details',
            'action' => 'produit'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'))));

        $r->prependRoute('drm_edition_produit_addlabel', new DRMDetailRoute('/drm/:identifiant/edition/:periode_version/addlabel/:certification/:genre/:appellation/:mention/:lieu/:couleur/:cepage/:detail', array('module' => 'drm_edition',
            'action' => 'addLabel'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'))));

        //ROUTING TELEDECLARATION

        $r->prependRoute('drm_societe', new sfRoute('/drm/societe/:identifiant', array('module' => 'drm',
            'action' => 'societe')));

         $r->prependRoute('drm_choix_creation', new sfRoute('/drm/:identifiant/choix-creation/:periode', array('module' => 'drm',
            'action' => 'choixCreation')));
        
        
        $r->prependRoute('drm_choix_produit', new DRMRoute('/drm/:identifiant/edition/:periode_version/choix-produits', array('module' => 'drm_ajout_produit',
            'action' => 'choixPoduits'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_choix_produit_add_produit', new DRMRoute('/drm/:identifiant/edition/:periode_version/ajout-produits/:certification_hash', array('module' => 'drm_ajout_produit',
            'action' => 'choixAjoutPoduits'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_crd', new DRMRoute('/drm/:identifiant/edition/:periode_version/crd', array('module' => 'drm_crds',
            'action' => 'crd'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_ajout_crd', new DRMRoute('/drm/:identifiant/edition/:periode_version/crd-ajout', array('module' => 'drm_crds',
            'action' => 'ajoutTypeCrd'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));


        $r->prependRoute('drm_choix_regime_crd', new DRMRoute('/drm/:identifiant/edition/:periode_version/crd-choix-regime', array('module' => 'drm_crds',
            'action' => 'choixRegimeCrd'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_annexes', new DRMRoute('/drm/:identifiant/edition/:periode_version/annexes', array('module' => 'drm_annexes',
            'action' => 'annexes'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));



        $r->prependRoute('drm_choix_favoris', new DRMRoute('/drm/:identifiant/edition/:periode_version/favoris', array('module' => 'drm_edition',
            'action' => 'choixFavoris'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));


        $r->prependRoute('drm_validation_update_etablissement', new DRMRoute('/drm/:identifiant/edition/:periode_version/validation-update-etablissement', array('module' => 'drm_validation',
            'action' => 'updateEtablissement'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));
        $r->prependRoute('drm_validation_update_societe', new DRMRoute('/drm/:identifiant/edition/:periode_version/validation-update-societe', array('module' => 'drm_validation',
            'action' => 'updateSociete'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object',
            'control' => array('edition'),
        )));

        $r->prependRoute('drm_pdf', new DRMRoute('/drm/:identifiant/pdf/:periode_version', array('module' => 'drm_pdf', 'action' => 'latex'), array('sf_method' => array('get', 'post')), array('model' => 'DRM', 'type' => 'object')
        ));

        $r->prependRoute('drm_debrayage', new EtablissementRoute('/drm/connexion/:identifiant', array('module' => 'drm',
            'action' => 'connexion'), array('sf_method' => array('get', 'post')), array('model' => 'DRM',
            'type' => 'object')
        ));
    }

}

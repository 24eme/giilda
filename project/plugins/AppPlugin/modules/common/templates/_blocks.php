<?php if ($sf_user->hasCredential('transactions')) :
     include_component('common', 'blockItem', array(
            'libelle' => 'DRM',
            'description' => 'description DRM ....',
            'prefix' => 'drm',
            'route' => 'drm',
            'route_etablissement' => 'drm_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
     ));

     include_component('common', 'blockItem', array(
            'libelle' => 'Contrats',
         'description' => 'description Contrats ....',
            'prefix' => 'vrac',
            'route' => 'vrac',
            'route_etablissement' => 'vrac_recherche',
            'etablissement' => $etablissement,
            'target' => '_self'
     ));

      include_component('common', 'blockItem', array(
            'libelle' => 'Factures',
          'description' => 'description Factures ....',
            'prefix' => 'facture',
            'route' => 'facture',
            'route_etablissement' => 'facture_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
      ));

       include_component('common', 'blockItem', array(
            'libelle' => 'Stocks',
           'description' => 'description Stocks ....',
            'prefix' => 'stocks',
            'route' => 'stocks',
            'route_etablissement' => 'stocks_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
       ));

   endif;


   if ($sf_user->hasCredential('contacts')) :
       include_component('common', 'blockItem', array(
            'libelle' => 'Contacts',
            'description' => 'description Contacts ....',
            'prefix' => 'societe',
            'route' => 'societe',
            'route_etablissement' => 'etablissement_visualisation',
            'etablissement' => $etablissement,
            'target' => '_self'
       ));
	endif;

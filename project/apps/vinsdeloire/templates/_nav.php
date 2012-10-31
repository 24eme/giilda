<nav id="navigation">
    <ul>
        <?php include_component('global', 'navItem', array(
            'libelle' => 'DRM',
            'prefix' => 'drm',
            'route' => 'drm',
            'route_etablissement' => 'drm_etablissement',
            'etablissement' => $etablissement,
        )) ?>

        <?php include_component('global', 'navItem', array(
            'libelle' => 'Contrats',
            'prefix' => 'vrac',
            'route' => 'vrac',
            'route_etablissement' => 'vrac_recherche',
            'etablissement' => $etablissement,
        )) ?>

        <?php include_component('global', 'navItem', array(
            'libelle' => 'Facture',
            'prefix' => 'facture',
            'route' => 'facture',
            'route_etablissement' => 'facture_etablissement',
            'etablissement' => $etablissement,
        )) ?>
        
        <li><a href="#">Contacts</a></li>
        
        <?php include_component('global', 'navItem', array(
            'libelle' => 'Import VR',
            'prefix' => 'revendication',
            'route' => 'revendication_upload',
            'route_etablissement' => 'revendication_etablissement',
            'etablissement' => null,
        )) ?>

        <?php include_component('global', 'navItem', array(
            'libelle' => 'SV12',
            'prefix' => 'sv12',
            'route' => 'sv12',
            'route_etablissement' => 'sv12_etablissement',
            'etablissement' => $etablissement,
        )) ?>

        <?php include_component('global', 'navItem', array(
            'libelle' => 'Stocks',
            'prefix' => 'ds',
            'route' => 'ds',
            'route_etablissement' => 'ds_etablissement',
            'etablissement' => $etablissement,
        )) ?>
        
        <?php
        include_component('global', 'navItem', array(
            'libelle' => 'Alertes',
            'prefix' => 'alerte',
            'route' => 'alerte',
            'route_etablissement' => 'alerte_etablissement',
            'etablissement' => $etablissement,
        )) ?>

        <li><a href="#">Relance</a></li>
    </ul>
</nav>

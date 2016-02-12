<div id="navbar" class="navbar-collapse collapse">
<ul class="nav navbar-nav"><?php if ($sf_user->hasCredential('transactions')) : 
     include_component('global', 'navItem', array(
            'libelle' => 'DRM',
            'prefix' => 'drm',
            'route' => 'drm',
            'route_etablissement' => 'drm_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
     ));

     include_component('global', 'navItem', array(
            'libelle' => 'Contrats',
            'prefix' => 'vrac',
            'route' => 'vrac',
            'route_etablissement' => 'vrac_recherche',
            'etablissement' => $etablissement,
            'target' => '_self'
     ));

      include_component('global', 'navItem', array(
            'libelle' => 'Factures',
            'prefix' => 'facture',
            'route' => 'facture',
            'route_etablissement' => 'facture_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
      ));

/*
      include_component('global', 'navItem', array(
            'libelle' => 'Import VR',
            'prefix' => 'revendication',
            'route' => 'revendication',
            'route_etablissement' => 'revendication_etablissement',
            'etablissement' => null,
            'target' => '_self'
      ));

       include_component('global', 'navItem', array(
            'libelle' => 'SV12',
            'prefix' => 'sv12',
            'route' => 'sv12',
            'route_etablissement' => 'sv12_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
       ));

       include_component('global', 'navItem', array(
            'libelle' => 'DS',
            'prefix' => 'ds',
            'route' => 'ds',
            'route_etablissement' => 'ds_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
       ));
*/
       include_component('global', 'navItem', array(
            'libelle' => 'Stocks',
            'prefix' => 'stocks',
            'route' => 'stocks',
            'route_etablissement' => 'stocks_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
       ));
/*
       include_component('global', 'navItem', array(
            'libelle' => 'Alertes',
            'prefix' => 'alerte',
            'route' => 'alerte',
            'route_etablissement' => 'alerte_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
       ));
*/

       /*include_component('global', 'navItem', array(
            'libelle' => 'Relance',
            'prefix' => 'relance',
            'route' => 'relance',
            'route_etablissement' => 'relance_etablissement',
            'etablissement' => $etablissement,
            'target' => '_self'
       ));*/
   
   endif;


   if ($sf_user->hasCredential('contacts')) :
       include_component('global', 'navItem', array(
            'libelle' => 'Contacts',
            'prefix' => 'societe',
            'route' => 'societe',
            'route_etablissement' => 'societe_choose',
            'etablissement' => null,
            'target' => '_self'
       ));
	endif; 
?></ul>
<ul class="nav navbar-nav navbar-right">
<?php if (!$sf_user->hasCredential(Roles::TELEDECLARATION)): ?>
        <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-search"></span><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo url_for("statistiques_vrac") ?>">Contrat d'achat</a></li> 
            <li><a href="<?php echo url_for("statistiques_drm") ?>">DRM</a></li>
            <li><a href="<?php echo url_for("societe") ?>">Contacts</a></li>
          </ul>
        </li>
<?php endif; ?>
<?php if ($sf_user->hasCredential('admin')) : ?>
        <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo url_for("produits") ?>">Catalogue produit</a></li> 
            <li><a href="<?php echo url_for("comptabilite_edition") ?>">Codes analytiques</a></li>
          </ul>
        </li>
<?php endif; ?>
<?php if ($sf_user->hasCredential(Roles::TELEDECLARATION)): ?>
     <li><a tabindex="-1" href="<?php echo url_for("compte_teledeclarant_modification") ?>">Mon compte</a></li>
<?php endif; ?>
<?php if ($sf_user->isAuthenticated()): ?>
<?php if ($sf_user->isUsurpationCompte()): ?>
     <li><a tabindex="-1" href="<?php echo url_for('vrac_dedebrayage') ?>">Quitter</a></li>
<?php else: ?>
     <li><a tabindex="-1" href="<?php echo url_for('auth_logout') ?>"><span class="glyphicon glyphicon-log-out"></span></a></li>
<?php endif; ?>
<?php else: ?>
     <li><a tabindex="-1" href="<?php echo url_for('homepage') ?>">Connexion</a></li>
<?php endif; ?>
</ul>
</div>

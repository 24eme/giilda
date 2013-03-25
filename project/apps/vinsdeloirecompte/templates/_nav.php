<nav id="navigation">
    <ul>
        <?php include_component('global', 'navItem', array(
            'libelle' => 'Contacts',
            'prefix' => 'societe',
            'route' => 'societe',
            'route_etablissement' => 'societe_choose',
            'etablissement' => null,
            'target' => '_self'
        )) ?>
    </ul>
</nav>

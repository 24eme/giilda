<p id="fil_ariane">
    <strong>
        <?php echo link_to("Page d'accueil", 'revendication'); ?> > <?php echo link_to("Import Volumes Revendiqués", 'revendication_upload'); ?> > Import
    </strong>
</p><?php
        if (is_null($revendication)){
            $revendication = new stdClass();
        }
        if (!isset($revendication->etape)){
            $revendication->etape = 0;
        }
        ?>
<ol id="rail_etapes">
    <?php
    include_partial('revendication/etapeItem', array('num_etape' => 0,
        'revendication_etape' => $revendication->etape,
        'actif' => $actif,
        'label' => 'Import',
        'url_etape' => 'revendication_upload'
    ));
    ?>

    <?php
    include_partial('revendication/etapeItem', array('num_etape' => 1,
        'revendication_etape' => $revendication->etape,
        'actif' => $actif,
        'label' => 'Erreurs synthax.',
        'url_etape' => 'revendication_view_erreurs'
    ));
    ?>

    <?php
    include_partial('revendication/etapeItem', array('num_etape' => 2,
        'revendication_etape' => $revendication->etape,
        'actif' => $actif,
        'label' => 'Erreurs',
        'url_etape' => 'vrac_condition'
    ));
    ?>

<?php
include_partial('revendication/etapeItem', array('num_etape' => 3,
    'revendication_etape' => $revendication->etape,
    'actif' => $actif,
    'label' => 'Edition',
    'url_etape' => 'vrac_validation'
));
?>


</ol>
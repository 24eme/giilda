<p id="fil_ariane">
    <?php echo link_to("Page d'accueil", 'revendication'); ?> &GT; <?php echo link_to("Volumes Revendiqués de ".$revendication->odg." (".$revendication->campagne.")", 'revendication_upload',array('odg' => $revendication->odg,'campagne' => $revendication->campagne)); ?> &GT; 
    <strong>
        Import
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
        'revendication' => $revendication,
        'actif' => $actif,
        'label' => 'Import',
        'url_etape' => 'revendication_upload'
    ));
    ?>

    <?php
    include_partial('revendication/etapeItem', array('num_etape' => 1,
        'revendication_etape' => $revendication->etape,
        'revendication' => $revendication,
        'actif' => $actif,
        'label' => 'Erreurs',
        'url_etape' => 'revendication_view_erreurs'
    ));
    ?>

<?php
include_partial('revendication/etapeItem', array('num_etape' => 2,
    'revendication_etape' => $revendication->etape,
    'revendication' => $revendication,
    'actif' => $actif,
    'label' => 'Edition',
    'url_etape' => 'revendication_edition'
));
?>


</ol>
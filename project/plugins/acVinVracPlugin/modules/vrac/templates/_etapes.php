<?php if($vrac->etape==null) $vrac->etape=0; ?>
<?php $pourcentage = ($vrac->etape) * 25; ?>
<nav class="navbar navbar-default ">
    <ul class="nav navbar-nav">
        <?php 
            $counter = 0;
            foreach ($etapes as $etapeCle => $etapeLibelle) {
                include_partial('etapeItem',array('num_etape' => $counter,
                                                 'vrac' => $vrac,
                                                 'label' => $etapeLibelle,
                                                 'actif' => $actif,
                                                 'url_etape' => 'vrac_'.$etapeCle,
                                                 'urlsoussigne' => $urlsoussigne
                                                )); 
                $counter++;
            }
        ?>
    </ul>
</nav>
<?php
    if($vrac->etape==null) $vrac->etape=0;
    $pourcentage = ($vrac->etape) * 25;
?>
    <ol class="breadcrumb">
    	<?php 
    		$counter = 0;
    		foreach ($etapes as $etapeCle => $etapeLibelle) {
    	
    			include_partial('etapeItem',array('num_etape' => $counter,
                                                 'vrac' => $vrac,
                                                 'actif' => $actif,
                                                 'label' => $etapeLibelle,
                                                 'url_etape' => 'vrac_'.$etapeCle,
                                                 'urlsoussigne' => $urlsoussigne
                                                )); 
    			$counter++;
    		}
        ?>
        
        
    </ol>
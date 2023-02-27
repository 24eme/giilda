<?php
if (strpos($vrac->produit, 'certifications/AOP/genres/TRANQ/appellations/COS')) {
    include_partial('vrac/pdf_cahors', array('vrac' => $vrac));
}else {
    include_partial('vrac/pdf_ivso', array('vrac' => $vrac));
}

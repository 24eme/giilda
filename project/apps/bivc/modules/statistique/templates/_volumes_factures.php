<?php 
use_helper('BivcStatistique');

$result = $result->getRawValue();
$csv = "Produit;CVO;Volume\n";
foreach ($result['agg_page']['buckets'] as $appellation) {
	foreach($appellation['agg_line']['buckets'] as $cvo) {
		$appellationLibelle = $appellation['key'];
		//$totalTotal = (formatNumber($appellation['total']['value']) != 0)? formatNumber($appellation['produit_total']['value']) : null;
		$csv .= $appellationLibelle.';'.$cvo['key']. ';'.$cvo['total']['value']."\n";
	}
}

echo $csv;

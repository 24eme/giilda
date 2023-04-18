<?php 
use_helper('BivcStatistique');

$result = $result->getRawValue();
$csv = "Produit;CVO;Volume;Total\n";
// Totaux calculés ici et pas dans elastic par manque de temps
$total_cvo = 0;
$total_total = 0;
foreach ($result['agg_page']['buckets'] as $appellation) {
	foreach($appellation['agg_line']['buckets'] as $appellation_cvo) {
		$appellationLibelle = $appellation['key'];
		$cvo = $appellation_cvo['key'];
		$volume = $appellation_cvo['total']['value'];
		$total = $cvo * $volume;
		$csv .= $appellationLibelle.';'.$cvo. ';'.$volume .';'. $total ."\n";
		$total_cvo += $cvo;
		$total_total += $total;
	}
}
$csv .= "TOTAL;" . $total_cvo . ';' . $result['produit_total']['value'] . ';' . $total_total;

echo $csv;
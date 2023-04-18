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
		$csv .= $appellationLibelle.';'.sprintf('%.02f', $cvo). ';'.sprintf('%.04f', $volume) .';'. sprintf('%.04f', $total) ."\n";
		$total_cvo += $cvo;
		$total_total += $total;
	}
}
$csv .= "TOTAL;" . sprintf('%.02f', $total_cvo) . ';' . sprintf('%.04f', $result['produit_total']['value']) . ';' . sprintf('%.04f', $total_total);

echo $csv;
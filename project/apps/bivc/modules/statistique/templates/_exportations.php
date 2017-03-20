<?php 
use_helper('Statistique');
use_helper('BivcStatistique');

$csv = "Appellation;Pays;Blanc;Rosé;Rouge;TOTAL\n";
foreach ($result['agg_page']['buckets'] as $appellation) {
	$appellationLibelle = getAppellationLibelle(strtoupper($appellation['key']));
	$totalBlanc = formatNumber($appellation['total_blanc']['value'], 2);
	$totalRose = formatNumber($appellation['total_rose']['value'], 2);
	$totalRouge = formatNumber($appellation['total_rouge']['value'], 2);
	$totalTotal = formatNumber($appellation['total_total']['value'], 2);
	foreach ($appellation['agg_line']['buckets'] as $pays) {
		$paysLibelle = $pays['key'];
		$blanc = formatNumber($pays['blanc']['agg_column']['value'], 2);
		$rose = formatNumber($pays['rose']['agg_column']['value'], 2);
		$rouge = formatNumber($pays['rouge']['agg_column']['value'], 2);
		$total = formatNumber($pays['total']['agg_column']['value'], 2);
		$csv .= $appellationLibelle.';'.$paysLibelle.';'.$blanc.';'.$rose.';'.$rouge.';'.$total."\n";
	}
	$csv .= $appellationLibelle.';TOTAL;'.$totalBlanc.';'.$totalRose.';'.$totalRouge.';'.$totalTotal."\n";
}
$csv .= 'TOTAL;TOTAL;'.formatNumber($result['totaux_blanc']['value'], 2).';'.formatNumber($result['totaux_rose']['value'], 2).';'.formatNumber($result['totaux_rouge']['value'], 2).';'.formatNumber($result['totaux_total']['value'], 2)."\n";
echo $csv;
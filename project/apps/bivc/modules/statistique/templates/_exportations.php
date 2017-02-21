<?php 
use_helper('Statistique');
use_helper('BivcStatistique');

$csv = "Appellation;Pays;Blanc;Rosé;Rouge;TOTAL\n";
foreach ($result['agg_page']['buckets'] as $appellation) {
	$appellationLibelle = getAppellationLibelle(strtoupper($appellation['key']));
	$totalBlanc = formatNumber($appellation['total_blanc']['value']);
	$totalRose = formatNumber($appellation['total_rose']['value']);
	$totalRouge = formatNumber($appellation['total_rouge']['value']);
	$totalTotal = formatNumber($appellation['total_total']['value']);
	foreach ($appellation['agg_line']['buckets'] as $pays) {
		$paysLibelle = $pays['key'];
		$blanc = formatNumber($pays['blanc']['agg_column']['value']);
		$rose = formatNumber($pays['rose']['agg_column']['value']);
		$rouge = formatNumber($pays['rouge']['agg_column']['value']);
		$total = formatNumber($pays['total']['agg_column']['value']);
		if (!$blanc && !$rose && !$rouge) {
			continue;
		}
		$csv .= $appellationLibelle.';'.$paysLibelle.';'.$blanc.';'.$rose.';'.$rouge.';'.$total."\n";
	}
	$csv .= $appellationLibelle.';TOTAL;'.$totalBlanc.';'.$totalRose.';'.$totalRouge.';'.$totalTotal."\n";
}
echo $csv;
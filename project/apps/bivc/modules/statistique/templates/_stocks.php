<?php 
use_helper('Statistique');
use_helper('BivcStatistique');

$csv = "Appellation;Catégorie;Stock initial;Stock actuel;TOTAL mvt\n";
foreach ($result['agg_page']['buckets'] as $appellation) {
	$appellationLibelle = $appellation['key'];
	$totalStockInitial = formatNumber($appellation['total_stock_initial']['value']);
	$totalStockFinal = formatNumber($appellation['total_stock_final']['value']);
	$totalTotal = formatNumber($appellation['total_total']['value']);
	foreach ($appellation['agg_line']['buckets'] as $categorie) {
		$categorieLibelle = getFamilleLibelle($categorie['key']);
		$stockInitial = formatNumber($categorie['stock_initial']['agg_column']['value']);
		$stockFinal = formatNumber($categorie['stock_final']['agg_column']['value']);
		$totalMvt = formatNumber($categorie['total']['value']);
		$csv .= $appellationLibelle.';'.$categorieLibelle.';'.$stockInitial.';'.$stockFinal.';'.$totalMvt."\n";
	}
	$csv .= $appellationLibelle.';TOTAL;'.$totalStockInitial.';'.$totalStockFinal.';'.$totalTotal."\n";
}
$csv .= 'TOTAL;TOTAL;'.formatNumber($result['totaux_stock_initial']['value']).';'.formatNumber($result['totaux_stock_final']['value']).';'.formatNumber($result['totaux_total']['value'])."\n";
echo $csv;
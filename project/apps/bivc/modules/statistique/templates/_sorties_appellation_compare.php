<?php 
use_helper('Statistique');
use_helper('BivcStatistique');

$csv = "Appellation;Couleur;France N-1;France;France %;Export N-1;Export;Export %;Négoce N-1;Négoce;Négoce %;TOTAL N-1;TOTAL;TOTAL %\n";
$current = $current->getRawValue();
$lastPeriode = $lastPeriode->getRawValue();
$currentKeys = array_keys($current);
$currentPartKeys = array();
foreach ($current as $key => $values) {
	$tabKey = explode('/', $key);
	if (!in_array($tabKey[0], $currentPartKeys)) {
		$currentPartKeys[] = $tabKey[0];
	}
	if ($tabKey[0] == 'TOTAL') {
		foreach ($lastPeriode as $subkey => $subvalues) {
			$subtabKey = explode('/', $subkey);
			if (!in_array($subtabKey[0], $currentPartKeys)) {
				$csv .= $subtabKey[0].';'.$subtabKey[1].';'.$subvalues[0].';'.null.';'.getEvol($subvalues[0], 0).';'.$subvalues[1].';'.null.';'.getEvol($subvalues[1], 0).';'.$subvalues[2].';'.null.';'.getEvol($subvalues[2], 0).';'.$subvalues[3].';'.null.';'.getEvol($subvalues[3], 0)."\n";
			}
		}
	}
	if ($tabKey[1] == 'TOTAL') {
		foreach ($lastPeriode as $subkey => $subvalues) {
			$subtabKey = explode('/', $subkey);
			if ($subtabKey[0] == $tabKey[0] && !in_array($subkey, $currentKeys)) {
				$csv .= $subtabKey[0].';'.$subtabKey[1].';'.$subvalues[0].';'.null.';'.getEvol($subvalues[0], 0).';'.$subvalues[1].';'.null.';'.getEvol($subvalues[1], 0).';'.$subvalues[2].';'.null.';'.getEvol($subvalues[2], 0).';'.$subvalues[3].';'.null.';'.getEvol($subvalues[3], 0)."\n";
			}
		}
	}
	if (isset($lastPeriode[$key])) {
		$csv .= $tabKey[0].';'.$tabKey[1].';'.$lastPeriode[$key][0].';'.$values[0].';'.getEvol($lastPeriode[$key][0], $values[0]).';'.$lastPeriode[$key][1].';'.$values[1].';'.getEvol($lastPeriode[$key][1], $values[1]).';'.$lastPeriode[$key][2].';'.$values[2].';'.getEvol($lastPeriode[$key][2], $values[2]).';'.$lastPeriode[$key][3].';'.$values[3].';'.getEvol($lastPeriode[$key][3], $values[3])."\n";
	} else {
		$csv .= $tabKey[0].';'.$tabKey[1].';'.null.';'.$values[0].';'.getEvol(0, $values[0]).';'.null.';'.$values[1].';'.getEvol(0, $values[1]).';'.null.';'.$values[2].';'.getEvol(0, $values[2]).';'.null.';'.$values[3].';'.getEvol(0, $values[3])."\n";
	}
}
echo $csv;
<?php

function noeudXml($produit, $noeud, &$xml, $exceptions = array()) {
	foreach ($noeud as $key => $children) {
		if (!is_numeric($key)) {
			$xml .= "<$key>";
			$xml .= noeudXml($produit, $children, $xml, $exceptions);
			$xml .= "</$key>";
		} else {
			$val = $produit->getTotalVolume($noeud);
			if ($val) {
				return (in_array($noeud->getKey(), $exceptions))? $val : sprintf("%01.02f", $val);
			} else {
				return (in_array($noeud->getKey(), array('stock-debut-periode', 'stock-fin-periode')))? 0 : null;
			}
		}
	}
}

function storeMultiArray(&$node, $keys, $value) {
	$k = array_shift($keys);
	if (!$k) {
		if (!is_array($node)) {
			return $value + $node;
		}else{
			return $value;
		}
	}
	if (!is_array($node)) {
		$node = array();
	}
	$node[$k] = storeMultiArray($node[$k], $keys, $value);
	return $node;
}

function multiArray2XML($preXML) {
	$xml = '';
	foreach ($preXML as $k => $v) {
		if (is_array($v)) {
			$xml .= "<$k>";
			$xml .= multiArray2XML($v);
			$xml .= "</$k>";
		}else{
			$xml .= "<$k>";
                        $xml .= sprintf('%.04f', $v);
                        $xml .= "</$k>";
		}
	}
	return $xml;
}

function details2XmlDouane($detail) {
	$confDetail = $detail->getConfig()->getDocument()->declaration->details;
        $preXML = array();
        foreach (array('stocks_debut', 'entrees', 'sorties', 'stocks_fin') as $type) {
	  foreach ($detail->get($type) as $k => $v) {
		if (($v || ($k == 'revendique' && preg_match('/^stock/', $type))) && $confDetail->get($type)->exist($k) && $confDetail->get($type)->get($k)->douane_cat) {
                        $preXML = storeMultiArray($preXML, split('/', $confDetail->get($type)->get($k)->douane_cat),  $v);
		}
	  }
	}
	return multiArray2XML($preXML);
}

function formatXml($xml, $level = 0) {
	while (preg_match("/\<[a-zA-Z0-9\-\_]*\>\<\/[a-zA-Z0-9\-\_]*\>/", $xml)) {
		$xml = preg_replace("/\<[a-zA-Z0-9\-\_]*\>\<\/[a-zA-Z0-9\-\_]*\>/", "", $xml);
	}
	$xml = preg_replace("/\<([a-zA-Z0-9\-\_]*)\>\<([a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level + 1)."<$2>", $xml);
	$xml = preg_replace("/\<(\/[a-zA-Z0-9\-\_]*)\>\<([a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level)."<$2>", $xml);
	$xml = preg_replace("/\<(\/[a-zA-Z0-9\-\_]*)\>\<(\/[a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level)."<$2>", $xml);
	$xml = preg_replace("/\<(\/[a-zA-Z0-9\-\_]*)\>\<(\/[a-zA-Z0-9\-\_]*)\>/", str_repeat("\t", 1)."<$1>\n".str_repeat("\t", $level)."<$2>", $xml);
	$xml = preg_replace("/\<([a-zA-Z0-9\-\_]*)\>\<([a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level + 2)."<$2>", $xml);
	return str_repeat("\t", $level).$xml."\n";
}

function drm2CrdCiel($drm) {
	$crds = array();
	foreach ($drm->crds as $type => $tcrds) {
		foreach ($tcrds as $k => $crd) {
			if (!isset($crds[$type.$crd->genre])) {
				$crds[$type.$crd->genre] = array();
			}
			if (!isset($crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)])) {
				$crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)] = clone $crd;
			}else{
				$crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->stock_debut += $crd->stock_debut;
                                $crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->entrees_achats += $crd->entrees_achats;
                                $crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->entrees_excedents += $crd->entrees_excedents;
                                $crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->entrees_retours += $crd->entrees_retours;
                                $crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->sorties_destructions += $crd->sorties_destructions;
                                $crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->sorties_utilisations += $crd->sorties_utilisations;
                                $crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->sorties_manquants += $crd->sorties_manquants;
                                $crds[$type.$crd->genre][sprintf('%.f', $crd->centilitrage)]->stock_fin += $crd->stock_fin;
			}
		}
	}
	return $crds;
}

function crdGenre2CategorieFiscale($g) {
	$crdGenre2CategorieFiscaleArray = array('TRANQ' => 'T', 'MOUSSEUX' => 'M');
	return $crdGenre2CategorieFiscaleArray[$g];
}
function crdType2TypeCapsule($t) {
	$crdType2TypeCapsuleArray = array('COLLECTIFSUSPENDU'=>'COLLECTIVES_DROITS_SUSPENDUS', 'COLLECTIFAQUITTE' => 'COLLECTIVES_DROITS_AQUITTES', 'PERSONNALISE'=>'PERSONNALISEES');
	return $crdType2TypeCapsuleArray[$t];
}
function documentAnnexeKey2XMLTag($d) {
	$documentAnnexeKey2XMLTagArray = array('DAE' => 'daa-dca', 'DAA/DAC' => 'daa-dca', 'DAADAC' => 'daa-dca', 'DSA/DSAC' => 'dsa-dsac', 'DSADSAC' => 'dsa-dsac', 'EMPREINTE'=>'numero-empreintes');
	return $documentAnnexeKey2XMLTagArray[$d];
}

function formatCodeINAO($s) {
	if (strlen($s) == 5) {
		return "$s ";
	}
	return $s;
}
function formatDateDouane($s) {
	return preg_replace('/([0-9][0-9])[^0-9]([0-9][0-9])[^0-9]([0-9][0-9][0-9][0-9])/', '\3-\2-\1', $s);
}

function centilisation2Douane($c) {
	$conv = array(
'0.000000' => 'CL_75',
'0.001000' => 'CL_10',
'0.001250' => 'CL_12_5',
'0.001870' => 'CL_18_7',
'0.002000' => 'CL_20',
'0.002500' => 'CL_25',
'0.003500' => 'CL_35',
'0.003750' => 'CL_37_5',
'0.005000' => 'CL_50',
'0.006200' => 'CL_62',
'0.007000' => 'CL_70',
'0.007500' => 'CL_75',
'0.010000' => 'CL_100',
'0.015000' => 'CL_150',
'0.017500' => 'CL_175',
'0.020000' => 'CL_200',
'0.022500' => 'BIB_225',
'0.030000' => 'BIB_300',
'0.040000' => 'BIB_400',
'0.050000' => 'BIB_500',
'0.080000' => 'BIB_800',
'0.100000' => 'BIB_1000');
	return $conv[sprintf('%.f', $c)];
}

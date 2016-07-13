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
		if (is_int($node)) {
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
                        $xml .= $v;
                        $xml .= "</$k>";
		}
	}
	return $xml;
}

function details2XmlDouane($detail) {
	$confDetail = $detail->getConfig()->getDocument()->declaration->detail;
        $preXML = array();
        foreach (array('stocks_debut', 'entrees', 'sorties', 'stocks_fin') as $type) {
	  foreach ($detail->get($type) as $k => $v) {
		if ($v && $confDetail->get($type)->exist($k) && $confDetail->get($type)->get($k)->douane_cat) {
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
		$crds[$type.$crd->genre][] = $crd;
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
	$documentAnnexeKey2XMLTagArray = array('DAE' => 'daa-dca', 'DAA/DAC' => 'daa-dca', 'DSA/DSAC' => 'dsa-dsac', 'Empreinte'=>'numero-empreintes');
	return $documentAnnexeKey2XMLTagArray[$d];
}

function formatCodeINAO($s) {
	if (strlen($s) == 5) {
		return "$s ";
	}
	return $s;
}

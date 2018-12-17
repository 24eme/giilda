<?php
use_helper('Statistique');
use_helper('IvbdStatistique');
$millesime = "2017";

$csv = 'Type contrat;Produit;Nombre de contrats;Qté en hl;Prix moyen;Volume '.$millesime.'; Cours '.$millesime.';Volume autres millesimes;Cours autres millesimes;'."\n";
foreach ($result['agg_page']['buckets'] as $type_contrats) {
	$type_contrats_libelle = getConditionnementLibelle(strtoupper($type_contrats['key']));
	foreach ($type_contrats['agg_page']['buckets'] as $produit_contrats_millesimes) {
		$produitLibelle = getProduitLibelle($produit_contrats_millesimes['key']);
		$nbContrats = $produit_contrats_millesimes["doc_count"];

		$toutMillesimesPrix = 0.0;
		$toutMillesimesVolume = 0.0;
		$toutMillesimesMoyenne = 0.0;

		$millesimesNb = 0;
		$millesimesPrix = 0.0;
		$millesimesVolume = 0.0;
		$millesimesMoyenne = 0.0;

		$horsMillesimesNb = 0;
		$horsMillesimesPrix = 0.0;
		$horsMillesimesVolume = 0.0;
		$horsMillesimesMoyenne = 0.0;

		foreach ($produit_contrats_millesimes['agg_line']['buckets'] as $contrats_millesimes) {
			$toutMillesimesVolume += formatNumber($contrats_millesimes['vol_prix']['agg_column']['value'],2);
			$toutMillesimesPrix += formatNumber($contrats_millesimes['ca']['agg_column']['value'],2);
			if($contrats_millesimes['key'] == $millesime){
				$millesimesNb = $contrats_millesimes['doc_count'];
				$millesimesVolume = formatNumber($contrats_millesimes['vol_prix']['agg_column']['value'],2);
				$millesimesPrix = formatNumber($contrats_millesimes['ca']['agg_column']['value'],2);
				$millesimesMoyenne  = formatNumber($contrats_millesimes['moyenne']['value'],2);
			}else{
				$horsMillesimesNb += $contrats_millesimes['doc_count'];
				$horsMillesimesVolume += formatNumber($contrats_millesimes['vol_prix']['agg_column']['value'],2);
				$horsMillesimesPrix += formatNumber($contrats_millesimes['ca']['agg_column']['value'],2);
			}
		}

		$toutMillesimesMoyenne = ($toutMillesimesVolume)? formatNumber($toutMillesimesPrix / $toutMillesimesVolume / 225,2) : 0.0;
		$horsMillesimesMoyenne = ($horsMillesimesVolume)? formatNumber($horsMillesimesPrix / $horsMillesimesVolume / 225,2) : 0.0;
		$csv .= $type_contrats_libelle.';'.$produitLibelle.';'.$nbContrats.';'.$toutMillesimesVolume.';'.$toutMillesimesMoyenne.';'.$millesimesVolume.';'.$millesimesMoyenne.';'.$horsMillesimesVolume.';'.$horsMillesimesMoyenne."\n";

	}
}
echo $csv;

<?php use_helper('DRMXml'); ?>
<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?>

<message-interprofession xmlns="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0 echanges-interprofession-1.7.xsd">
	<siren-interprofession><?php echo sfConfig::get('app_ciel_siren'); ?></siren-interprofession>
	<declaration-recapitulative>
		<identification-declarant>
			<numero-agrement><?php echo $drm->declarant->no_accises ?></numero-agrement>
<?php if ($drm->declarant->cvi): ?>
			<numero-cvi><?php echo $drm->declarant->cvi ?></numero-cvi>
<?php endif; ?>
		</identification-declarant>
		<periode>
			<mois><?php echo $drm->getMois() ?></mois>
			<annee><?php echo $drm->getAnnee() ?></annee>
		</periode>
		<declaration-neant><?php echo ($drm->declaration->hasStockEpuise() && !$drm->isMoisOuvert())? "true" : "false"; ?></declaration-neant>
<?php if (!$drm->declaration->hasStockEpuise() || $drm->isMoisOuvert()): ?>
		<droits-suspendus>
<?php foreach (xmlGetProduitsDetails($drm, true, DRM::DETAILS_KEY_SUSPENDU) as $produit):	?>
			<produit>
<?php if ($produit->getCodeDouane()): ?>
			<?php if($produit->isCodeDouaneNonINAO()): ?>
				<libelle-fiscal><?php echo formatCodeINAO($produit->getCodeDouane()) ?></libelle-fiscal>
			<?php else: ?>
				<code-inao><?php echo formatCodeINAO($produit->getCodeDouane()) ?></code-inao>
			<?php endif; ?>
<?php endif; ?>
				<libelle-personnalise><?php echo xmlProduitLibelle($produit); ?></libelle-personnalise>
<?php if ($produit->getTav()): ?>
				<tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if ($produit->isPremix()): ?>
				<premix>true</premix>
<?php else: ?>
				<premix>false</premix>
<?php endif; ?>
<?php if ($produit->exist('observations')): ?>
				<observations><?php echo $produit->get('observations'); ?></observations>
<?php endif; ?>
				<balance-stocks>

<?php
	$xml = details2XmlDouane($produit, $drm->isNegoce());
	echo formatXml($xml, 5);
?>
				</balance-stocks>
			</produit>
<?php endforeach; ?>
			<stockEpuise>false</stockEpuise>
		</droits-suspendus>
<?php if ($drm->hasExportableProduitsAcquittes()): ?>
		<droits-acquittes>
<?php foreach (xmlGetProduitsDetails($drm, true, DRM::DETAILS_KEY_ACQUITTE) as $produit): ?>
			<produit>
<?php if ($produit->getCodeDouane()): ?>
			<?php if($produit->isCodeDouaneNonINAO()): ?>
				<libelle-fiscal><?php echo formatCodeINAO($produit->getCodeDouane()) ?></libelle-fiscal>
			<?php else: ?>
				<code-inao><?php echo formatCodeINAO($produit->getCodeDouane()) ?></code-inao>
			<?php endif; ?>
<?php endif; ?>
				<libelle-personnalise><?php echo xmlProduitLibelle($produit); ?></libelle-personnalise>
<?php if ($produit->getTav()): ?>
				<tav><?php echo sprintf("%01.02f", $produit->getTav()) ?></tav>
<?php endif; ?>
<?php if (false && $produit->getPremix()): ?>
				<premix>true</premix>
<?php endif; ?>
<?php if ($produit->exist('observations')): ?>
				<observations><?php echo $produit->get('observations'); ?></observations>
<?php endif; ?>
				<balance-stocks>

<?php
	$xml = details2XmlDouane($produit, $drm->isNegoce());
	echo formatXml($xml, 5);?>
				</balance-stocks>
			</produit>
<?php endforeach; ?>
			<stockEpuise>false</stockEpuise>
    	</droits-acquittes>
<?php endif; ?>
<?php endif; ?>
<?php if ($drm->exist('crds') && $drm->crds): foreach(drm2CrdCiel($drm) as $gcrds): $fkey = key($gcrds);?>
    	<compte-crd>
      		<categorie-fiscale-capsules><?php echo crdGenre2CategorieFiscale($gcrds[$fkey]->genre) ?></categorie-fiscale-capsules>
      		<type-capsule><?php echo crdType2TypeCapsule($gcrds[$fkey]->type) ?></type-capsule>
<?php foreach($gcrds as $crd) : ?>
                <centilisation volume="<?php echo centilisation2douane($crd->centilitrage, $crd->detail_libelle); ?>"<?php
 									if (centilisation2douane($crd->centilitrage, $crd->detail_libelle) == 'AUTRE') : ?> volumePersonnalise="<?php printf('%.01lf', $crd->centilitrage * 10000);
									?>" bib="<?php echo ($crd->isBib()) ? 'true' : 'false' ; ?>"<?php endif; ?>>
        		<stock-debut-periode><?php echo $crd->stock_debut ?></stock-debut-periode>
<?php if ($crd->entrees_achats || $crd->entrees_excedents || $crd->entrees_retours || $crd->entrees_autres): ?>
        		<entrees-capsules>
<?php if ($crd->entrees_achats): ?>
				<achats><?php echo $crd->entrees_achats ?></achats>
<?php endif; ?>
<?php if ($crd->entrees_retours): ?>
				<retours><?php echo $crd->entrees_retours ?></retours>
<?php endif; ?>
<?php if ($crd->entrees_excedents): ?>
				<excedents><?php echo $crd->entrees_excedents ?></excedents>
<?php endif; ?>
<?php if ($crd->entrees_autres): ?>
				<autres-entrees><?php echo $crd->entrees_autres ?></autres-entrees>
<?php endif; ?>
        		</entrees-capsules>
<?php endif; ?>
<?php if ($crd->sorties_utilisations || $crd->sorties_destructions || $crd->sorties_manquants || $crd->sorties_autres): ?>
        		<sorties-capsules>
<?php if ($crd->sorties_utilisations): ?>
				<utilisations><?php echo $crd->sorties_utilisations ?></utilisations>
<?php endif; ?>
<?php if ($crd->sorties_destructions): ?>
				<destructions><?php echo $crd->sorties_destructions ?></destructions>
<?php endif; ?>
<?php if ($crd->sorties_manquants): ?>
				<manquants><?php echo $crd->sorties_manquants ?></manquants>
<?php endif; ?>
<?php if ($crd->sorties_autres): ?>
				<autres-sorties><?php echo $crd->sorties_autres ?></autres-sorties>
<?php endif; ?>
        		</sorties-capsules>
<?php endif; ?>
        		<stock-fin-periode><?php echo ($crd->stock_fin)? $crd->stock_fin : 0 ?></stock-fin-periode>
<?php if ($crd->exist('observations')): ?>
            <observations><?php echo $crd->get('observations'); ?></observations>
<?php endif; ?>
      		</centilisation>
<?php endforeach; ?>
    	</compte-crd>
<?php endforeach; endif;
$documents_annexes = array();
foreach($drm->documents_annexes as $k => $v): if ($k != 'DAE')  :
	$documents_annexes[$k] = $v;
endif; endforeach;
if (count($documents_annexes)): ?>
    	<document-accompagnement>
<?php foreach($documents_annexes as $k => $v): ?>
	        <<?php echo documentAnnexeKey2XMLTag($k); ?>>
        		<debut-periode><?php echo $v->debut ?></debut-periode>
        		<fin-periode><?php echo $v->fin ?></fin-periode>
            <nombre-document-empreinte><?php echo $v->nb ?></nombre-document-empreinte>
          </<?php echo documentAnnexeKey2XMLTag($k); ?>>
<?php endforeach; ?>
    	</document-accompagnement>
<?php endif; ?>
<?php if ($drm->exist('releve_non_apurement')) foreach($drm->releve_non_apurement as $k => $releve) if ($releve->numero_document && $releve->date_emission): ?>
    	<releve-non-apurement>
      		<numero-daa-dac-dae><?php echo $releve->numero_document; ?></numero-daa-dac-dae>
      		<date-expedition><?php echo formatDateDouane($releve->date_emission); ?></date-expedition>
					<?php if($releve->numero_accise): ?>
      			<numero-accise-destinataire><?php echo $releve->numero_accise; ?></numero-accise-destinataire>
					<?php endif; ?>
    	</releve-non-apurement>
<?php endif; ?>
<?php if ($drm->declaratif->exist('statistiques') && ($drm->declaratif->statistiques->jus || $drm->declaratif->statistiques->mcr || $drm->declaratif->statistiques->vinaigre)): ?>
    	<statistiques>
<?php if ($drm->declaratif->statistiques->jus): ?>
		<quantite-mouts-jus><?php echo sprintf("%.2f", $drm->declaratif->statistiques->jus) ?></quantite-mouts-jus>
<?php endif; ?>
<?php if ($drm->declaratif->statistiques->mcr): ?>
		<quantite-mouts-mcr><?php echo sprintf("%.2f", $drm->declaratif->statistiques->mcr) ?></quantite-mouts-mcr>
<?php endif; ?>
<?php if ($drm->declaratif->statistiques->vinaigre): ?>
		<quantite-vins-vinaigre><?php echo sprintf("%.2f", $drm->declaratif->statistiques->vinaigre) ?></quantite-vins-vinaigre>
<?php endif; ?>
    	</statistiques>
<?php endif; ?>
  	</declaration-recapitulative>
</message-interprofession>

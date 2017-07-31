<?php
use_helper('Vrac');
use_helper('Vracpdf');
use_helper('Display');
?>


\def\INTERLOIRECOORDONNEESTITRE{<?php echo "Interprofession des Vins du Val de Loire"; ?>}
\def\INTERLOIRECOORDONNEESADRESSE{<?php echo "InterLoire - 62, rue Blaise Pascal - CS 61921"; ?>}
\def\INTERLOIRECOORDONNEESCPVILLE{<?php echo "37019 TOURS CEDEX 1"; ?>}
\def\INTERLOIRECOORDONNEESTELEPHONENANTES{<?php echo "Vignoble Nantais : Tél. 02 47 60 55 15"; ?>}
\def\INTERLOIRECOORDONNEESTELEPHONEANJOU{<?php echo "Vignoble Anjou-Saumur : Tél. 02 47 60 55 36"; ?>}
\def\INTERLOIRECOORDONNEESTELEPHONETOURS{<?php echo "Vignoble Touraine : Tél. 02 47 60 55 18"; ?>}
\def\INTERLOIRECOORDONNEESFAX{<?php echo "Fax : 02 47 60 55 09"; ?>}
\def\INTERLOIRECOORDONNEESEMAIL{<?php echo "Email : contact@vinsvaldeloire.fr"; ?>}

\def\VINIGPCOORDONNEESTITRE{<?php echo "C.I.V.D.L"; ?>}
\def\VINIGPCOORDONNEESADRESSE{<?php echo "37 avenue Jean Joxé"; ?>}
\def\VINIGPCOORDONNEESCPVILLE{<?php echo "49100 Angers"; ?>}
\def\VINIGPCOORDONNEESTELEPHONENANTES{<?php echo "~"; ?>}
\def\VINIGPCOORDONNEESFAX{<?php echo "Tél: 02.41.87.25.81"; ?>}
\def\VINIGPCOORDONNEESEMAIL{<?php echo "~"; ?>}

\def\CONTRATNUMENREGISTREMENT{<?php echo $vrac->getNumeroArchive(); ?>}
\def\CONTRATVISA{<?php echo $vrac->getVisa(); ?>}
\def\CONTRATDATEENTETE{<?php echo getDateEntete($vrac); ?>}

\def\CONTRAT_TITRE{<?php echo "CONTRAT D'ACHAT EN PROPRIETE"; ?>}

<?php
$vendeur_raison_sociale = ($vrac->vendeur->raison_sociale) ?
        cut_latex_string($vrac->vendeur->raison_sociale, 50) : cut_latex_string($vrac->getVendeurObject()->getSociete()->raison_sociale, 50);

$acheteur_raison_sociale = ($vrac->acheteur->raison_sociale) ?
        cut_latex_string($vrac->acheteur->raison_sociale, 50) : cut_latex_string($vrac->getAcheteurObject()->getSociete()->raison_sociale, 50);

$mandataire_raison_sociale = "";
if ($vrac->mandataire_exist) {
    $mandataire_raison_sociale = ($vrac->mandataire->raison_sociale) ?
            cut_latex_string($vrac->mandataire->raison_sociale, 50) : cut_latex_string($vrac->getMandataireObject()->getSociete()->raison_sociale, 50);
}
?>

\def\CONTRATVENDEURNOM{<?php echo $vendeur_raison_sociale; ?>}
\def\CONTRATVENDEURCVI{<?php echo $vrac->vendeur->cvi; ?>}
\def\CONTRATVENDEURSIRET{<?php echo $vrac->getVendeurObject()->getSociete()->getSiret(); ?>}
\def\CONTRATVENDEURACCISES{<?php echo $vrac->vendeur->no_accises; ?>}
\def\CONTRATVENDEURADRESSE{<?php echo cut_latex_string($vrac->vendeur->adresse, 45); ?>}
\def\CONTRATVENDEURCOMMUNE{<?php echo cut_latex_string(sprintf("%s %s", $vrac->vendeur->code_postal, $vrac->vendeur->commune), 55); ?>}


\def\CONTRATACHETEUREURNOM{<?php echo $acheteur_raison_sociale; ?>}
\def\CONTRATACHETEURCVI{<?php echo $vrac->acheteur->cvi; ?>}
\def\CONTRATACHETEURSIRET{<?php echo $vrac->getAcheteurObject()->getSociete()->getSiret(); ?>}
\def\CONTRATACHETEURACCISES{<?php echo $vrac->acheteur->no_accises; ?>}
\def\CONTRATACHETEURADRESSE{<?php echo cut_latex_string($vrac->acheteur->adresse, 45); ?>}
\def\CONTRATACHETEURCOMMUNE{<?php echo cut_latex_string(sprintf("%s %s", $vrac->acheteur->code_postal, $vrac->acheteur->commune), 55); ?>}

\def\CONTRATCOURTIERNOM{<?php echo $mandataire_raison_sociale; ?>}
\def\CONTRATCOURTIERCARTEPRO{<?php echo ($vrac->mandataire->carte_pro) ? ", n° carte professionnelle:~" . $vrac->mandataire->carte_pro : "."; ?>}

\def\CONTRATTYPE{<?php echo showType($vrac); ?>}
\def\CONTRATTYPEUNITE{<?php echo showUnite($vrac) ?>}
\def\CONTRATPRODUITLIBELLE{<?php echo $vrac->produit_libelle; ?>}
\def\CONTRATPRODUITMILLESIME{<?php echo $vrac->millesime; ?>}
\def\CONTRATPRODUITQUANTITE{<?php echo formatQuantiteFr($vrac); ?>}
\def\CONTRATPRIXUNITAIRE{<?php echo formatPrixFr($vrac->prix_unitaire); ?>}
\def\CONTRATTYPEEXPLICATIONPRIX{<?php echo vracTypeExplication($vrac); ?>}

\def\CONTRATDATEMAXENLEVEMENT{<?php echo cut_latex_string(Date::francizeDate($vrac->getMaxEnlevement()), 50); ?>}
\def\CONTRATFRAISDEGARDE{ <?php echo ($vrac->exist('enlevement_frais_garde') && !is_null($vrac->enlevement_frais_garde)) ?
        formatPrixFr($vrac->getFraisDeGarde()) . '~\euro/hl' : '~~~~~\euro/hl';
?>}

\def\CONTRATLIEUCREATION{<?php echo cut_latex_string($vrac->getResponsableLieu(), 70); ?>}
\def\CONTRATDATECREATION{<?php echo cut_latex_string(Date::francizeDate($vrac->valide->date_saisie), 70); ?>}

\def\CONTRATMANDATAIREVISA{<?php echo getMandataireVisa($vrac); ?>}
\def\CONTRATDATESIGNATUREVENDEUR{<?php echo getDateSignatureVendeur($vrac); ?>}
\def\CONTRATDATESIGNATUREACHETEUR{<?php echo getDateSignatureAcheteur($vrac); ?>}

<?php if ($vrac->isDomaine()): ?>
    \def\CONTRATGENERIQUEDOMAINE{Domaine <?php echo $vrac->domaine ?>}
<?php else: ?>
    \def\CONTRATGENERIQUEDOMAINE{}
<?php endif; ?>

\def\CONTRATBIO{<?php echo ($vrac->isBio())? 'Agriculture Biologique (AB)' : ''; ?>}

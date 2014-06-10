<?php 
use_helper('Vrac');
use_helper('Vracpdf');
use_helper('Display');
?>


\def\INTERLOIRECOORDONNEESTITRE{<?php echo "Interprofession des Vins du Val de Loire"; ?>}
\def\INTERLOIRECOORDONNEESADRESSE{<?php echo "InterLoire - 62, rue Blaise Pascal - CS 61921"; ?>}
\def\INTERLOIRECOORDONNEESCPVILLE{<?php echo "37019 TOURS CEDEX 1"; ?>}
\def\INTERLOIRECOORDONNEESTELEPHONENANTES{<?php echo "Vignoble Nantais: Tél. 02 47 60 55 36"; ?>}
\def\INTERLOIRECOORDONNEESTELEPHONEANJOU{<?php echo "Vignoble Anjou-Saumur: Tèl. 02 47 60 55 36"; ?>}
\def\INTERLOIRECOORDONNEESTELEPHONETOURS{<?php echo "Vignoble Touraine: Tèl. 02 47 60 55 18"; ?>}
\def\INTERLOIRECOORDONNEESFAX{<?php echo "Fax: 02 47 60 55 18"; ?>}
\def\INTERLOIRECOORDONNEESEMAIL{<?php echo "Email:contact@vinsdeloire.fr"; ?>}

\def\CONTRATNUMENREGISTREMENT{<?php echo $vrac->getNumeroArchive(); ?>}
\def\CONTRATVISA{<?php echo $vrac->getVisa(); ?>}
\def\CONTRATDATEENTETE{<?php echo getDateValidation($vrac); ?>}

\def\CONTRAT_TITRE{<?php echo "CONTRAT D'ACHAT EN PROPRIETE"; ?>}


\def\CONTRATVENDEURNOM{<?php echo cut_latex_string($vrac->vendeur->nom,34); ?>}
\def\CONTRATVENDEURCVI{<?php echo $vrac->vendeur->cvi; ?>}
\def\CONTRATVENDEURACCISE{<?php echo $vrac->vendeur->no_accises; ?>}
\def\CONTRATVENDEURNUMTVA{<?php echo $vrac->vendeur->no_tva_intracomm; ?>}
\def\CONTRATVENDEURLIEU{<?php echo cut_latex_string($vrac->vendeur->commune,34); ?>}

\def\CONTRATACHETEUREURNOM{<?php echo cut_latex_string($vrac->acheteur->nom,33); ?>}
\def\CONTRATACHETEURCVI{<?php echo $vrac->acheteur->cvi; ?>}
\def\CONTRATACHETEURACCISE{<?php echo $vrac->acheteur->no_accises; ?>}
\def\CONTRATACHETEURNUMTVA{<?php echo $vrac->acheteur->no_tva_intracomm; ?>}
\def\CONTRATACHETEURLIEU{<?php echo cut_latex_string($vrac->acheteur->commune,33); ?>}
\def\CONTRATACHETEURDEPT{<?php echo substr($vrac->acheteur->code_postal,0,2); ?>}

\def\CONTRATCOURTIERNOM{M<?php echo $vrac->mandataire->nom; ?>}
\def\CONTRATCOURTIERCARTEPRO{<?php echo $vrac->mandataire->carte_pro; ?>}

\def\CONTRATTYPE{<?php echo showType($vrac); ?>}
\def\CONTRATTYPEUNITE{<?php echo showUnite($vrac) ?>}
\def\CONTRATPRODUITLIBELLE{<?php echo $vrac->produit_libelle; ?>}
\def\CONTRATPRODUITMILLESIME{<?php echo $vrac->millesime; ?>}
\def\CONTRATPRODUITQUANTITE{<?php echo $vrac->getQuantite(); ?>}
\def\CONTRATPRIX{<?php echo $vrac->prix_initial_total; ?>}
\def\CONTRATTYPEEXPLICATIONPRIX{<?php echo vracTypeExplication($vrac);?>}

\def\CONTRATDATEMAXENLEVEMENT{Au plus tard le<?php echo cut_latex_string('07/09/1985',50); ?>}
\def\CONTRATFRAISDEGARDE{ <?php echo $vrac->getFraisDeGarde(); ?> ~ \euro/<?php echo showUnite($vrac) ?> }

\def\CONTRATLIEUCREATION{<?php echo cut_latex_string($vrac->mandataire->commune,70); ?>}
\def\CONTRATDATECREATION{<?php echo cut_latex_string($vrac->valide->date_saisie,70); ?>}

\def\CONTRATMANDATAIREVISA{<?php echo getMandataireVisa($vrac); ?>}
\def\CONTRATDATESIGNATUREVENDEUR{<?php echo getDateSignatureVendeur($vrac); ?>}
\def\CONTRATDATESIGNATUREACHETEUR{<?php echo getDateSignatureAcheteur($vrac); ?>}
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
\def\CONTRATDATEENTETE{<?php echo Date::francizeDate(getDateValidation($vrac)); ?>}

\def\CONTRAT_TITRE{<?php echo "CONTRAT D'ACHAT EN PROPRIETE"; ?>}


\def\CONTRATVENDEURNOM{<?php echo cut_latex_string($vrac->vendeur->nom,55); ?>}
\def\CONTRATVENDEURCVI{<?php echo $vrac->vendeur->cvi; ?>}
\def\CONTRATVENDEURACCISE{<?php echo $vrac->vendeur->no_accises; ?>}
\def\CONTRATVENDEURNUMTVA{<?php echo $vrac->vendeur->no_tva_intracomm; ?>}
\def\CONTRATVENDEURLIEU{<?php echo cut_latex_string($vrac->vendeur->commune,33); ?>}

\def\CONTRATACHETEUREURNOM{<?php echo cut_latex_string($vrac->acheteur->nom,31); ?>}
\def\CONTRATACHETEURCVI{<?php echo $vrac->acheteur->cvi; ?>}
\def\CONTRATACHETEURACCISE{<?php echo $vrac->acheteur->no_accises; ?>}
\def\CONTRATACHETEURNUMTVA{<?php echo $vrac->acheteur->no_tva_intracomm; ?>}
\def\CONTRATACHETEURLIEU{<?php echo cut_latex_string($vrac->acheteur->commune,55); ?>}
\def\CONTRATACHETEURDEPT{<?php echo substr($vrac->acheteur->code_postal,0,2); ?>}

\def\CONTRATCOURTIERNOM{<?php echo cut_latex_string($vrac->mandataire->nom,60);; ?>}
\def\CONTRATCOURTIERCARTEPRO{<?php echo $vrac->mandataire->carte_pro; ?>}

\def\CONTRATTYPE{<?php echo showType($vrac); ?>}
\def\CONTRATTYPEUNITE{<?php echo showUnite($vrac) ?>}
\def\CONTRATPRODUITLIBELLE{<?php echo $vrac->produit_libelle; ?>}
\def\CONTRATPRODUITMILLESIME{<?php echo $vrac->millesime; ?>}
\def\CONTRATPRODUITQUANTITE{<?php echo $vrac->getQuantite(); ?>}
\def\CONTRATPRIXUNITAIRE{<?php echo $vrac->prix_unitaire; ?>}
\def\CONTRATTYPEEXPLICATIONPRIX{<?php echo vracTypeExplication($vrac);?>}

\def\CONTRATDATEMAXENLEVEMENT{Au plus tard le~<?php echo cut_latex_string(Date::francizeDate($vrac->getMaxEnlevement()),50); ?>}
\def\CONTRATFRAISDEGARDE{ <?php echo $vrac->getFraisDeGarde(); ?> ~ \euro/<?php echo showUnite($vrac) ?> }

\def\CONTRATLIEUCREATION{<?php echo cut_latex_string($vrac->getResponsableLieu(),70); ?>}
\def\CONTRATDATECREATION{<?php echo cut_latex_string(Date::francizeDate($vrac->valide->date_saisie),70); ?>}

\def\CONTRATMANDATAIREVISA{<?php echo getMandataireVisa($vrac); ?>}
\def\CONTRATDATESIGNATUREVENDEUR{<?php echo getDateSignatureVendeur($vrac); ?>}
\def\CONTRATDATESIGNATUREACHETEUR{<?php echo getDateSignatureAcheteur($vrac); ?>}
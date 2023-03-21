<?php use_helper('Display'); ?>
\documentclass[a4paper,8pt]{article}
\usepackage{geometry} % paper=a4paper
\usepackage[english]{babel}
\usepackage[utf8]{inputenc}
\usepackage{units}
\usepackage{geometry}
\usepackage{graphicx}
\usepackage{fancyhdr}
\usepackage{fp}
\usepackage[table]{xcolor}
\usepackage{tikz}
\usepackage{array}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{marvosym}
\usepackage{lastpage}
\usepackage{truncate}

\usetikzlibrary{fit}

\renewcommand\sfdefault{phv}

\renewcommand{\familydefault}{\sfdefault}
\renewcommand{\TruncateMarker}{\small{...}}

\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{19cm}
\setlength{\headheight}{5.3cm}
\setlength{\headwidth}{19.5cm}
\setlength{\topmargin}{-4cm}
\addtolength{\textheight}{29.9cm}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}

\fancyhf{}
\def\PdfTitre{<?php echo $pdf_titre; ?>}
\def\NomInterpro{<?php echo $factureConfiguration->getNomInterproFacture(); ?>}
\def\InterproAdresse{ <?php echo $factureConfiguration->getEmetteurLibre()['adresse']; ?>\\
		       <?php echo $factureConfiguration->getEmetteurLibre()['code_postal']." ".$factureConfiguration->getEmetteurLibre()['ville']; ?> }
\def\InterproContact{\\ <?php echo $factureConfiguration->getEmetteurLibre()['telephone']; ?>\\
                            <?php if($factureConfiguration->getEmetteurLibre()['email']): ?>Email : <?php echo $factureConfiguration->getEmetteurLibre()['email']; ?><?php endif; ?>
										}
\def\InterproSIRET{<?php echo $factureConfiguration->getInfosInterpro()['siret']; ?>}
\def\InterproAPE{<?php echo $factureConfiguration->getInfosInterpro()['ape']; ?>}
\def\InterproTVAIntracomm{<?php echo $factureConfiguration->getInfosInterpro()['tva_intracom']; ?>}
\def\InterproBANQUE{<?php echo $factureConfiguration->getCoordonneesBancaire()['banque']; ?>}
\def\InterproBIC{<?php echo $factureConfiguration->getCoordonneesBancaire()['bic']; ?>}
\def\InterproIBAN{<?php echo $factureConfiguration->getCoordonneesBancaire()['iban']; ?>}

\def\RessortissantNom{<?php $nom = ($ressortissant->raison_sociale == '')? $ressortissant->nom : html_entity_decode($ressortissant->raison_sociale);
                            echo display_latex_string($nom,';',40);
                     ?>}
\def\RessortissantAdresse{<?php $adresse = ($ressortissant->adresse == '')? "~" : html_entity_decode($ressortissant->adresse);
                                                 echo display_latex_string($adresse,';',50,2); ?>}
\def\RessortissantAdresseComplementaire{<?php $adresseComplementaire = (!$ressortissant->adresse_complementaire)? "~" : "\\\\".html_entity_decode($ressortissant->adresse_complementaire);
                                          echo display_latex_string($adresseComplementaire,';',50,2);   ?>}
\def\RessortissantCP{<?php echo $ressortissant->code_postal; ?>}
\def\RessortissantVille{<?php echo $ressortissant->commune; ?>}

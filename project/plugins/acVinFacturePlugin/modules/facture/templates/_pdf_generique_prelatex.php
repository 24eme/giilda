<?php use_helper('Display');
$coordonneesBancaires = $facture->getCoordonneesBancaire();
 ?>
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
\setlength{\topmargin}{-4.5cm}
\addtolength{\textheight}{29.9cm}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}

\fancyhf{}
\def\NBPAGES{<?php echo $total_pages; ?>}

\def\PdfTitre{<?php echo $pdf_titre; ?>}
										}
\def\InterproSIRET{<?php echo $coordonneesBancaires->siret; ?>}
\def\InterproAPE{<?php echo $coordonneesBancaires->codeApe; ?>}
\def\InterproTVAIntracomm{<?php echo $coordonneesBancaires->tvaIntracom; ?>}
\def\InterproBANQUE{<?php echo $coordonneesBancaires->banque; ?>}
\def\InterproBIC{<?php echo $coordonneesBancaires->bic; ?>}
\def\InterproIBAN{<?php echo $coordonneesBancaires->iban; ?>}

\def\RessortissantNom{<?php $nom = ($ressortissant->raison_sociale == '')? $ressortissant->nom : html_entity_decode($ressortissant->raison_sociale);
                            echo display_latex_string($nom,';',40);
                     ?>}
\def\RessortissantAdresse{<?php $adresse = ($ressortissant->adresse == '')? "~" : html_entity_decode($ressortissant->adresse);
                                                 echo display_latex_string($adresse,';',50,2); ?>}
\def\RessortissantAdresseComplementaire{<?php $adresseComplementaire = ($ressortissant->exist("adresse_complementaire") && $ressortissant->adresse_complementaire)?  "\\\\".html_entity_decode($ressortissant->adresse_complementaire) : "~";
                                          echo display_latex_string($adresseComplementaire,';',50,2);   ?>}
\def\RessortissantCP{<?php echo $ressortissant->code_postal; ?>}
\def\RessortissantVille{<?php echo $ressortissant->commune; ?>}

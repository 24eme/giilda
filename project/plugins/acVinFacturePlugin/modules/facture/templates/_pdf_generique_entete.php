<?php
use_helper('Date');
use_helper('Display');

$coordonneesBancaires = $facture->getCoordonneesBancaire();
$infosInterpro = $facture->getInformationsInterpro();
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

\newcommand{\CutlnPapillon}{
  	\multicolumn{2}{|c|}{ ~~~~~~~~~~~~~~~~~~~~~~~ } &
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\
}

\newcommand{\CutlnPapillonEntete}{
      &  &  \multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\
}

\renewcommand{\familydefault}{\sfdefault}
\renewcommand{\TruncateMarker}{\small{...}}

\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{19cm}
\setlength{\headheight}{5.3cm}
\setlength{\headwidth}{19.5cm}
\setlength{\topmargin}{-4.5cm}
\addtolength{\textheight}{29.9cm}

\def\TVA{19.60}
\def\NomInterpro{<?php echo (FactureConfiguration::getInstance()->getNomInterproFacture())?FactureConfiguration::getInstance()->getNomInterproFacture() : "interprofession"; ?>}
\def\InterproAdresse{<?php echo $facture->emetteur->adresse; ?> \\
		       <?php echo $facture->emetteur->code_postal.' '.$facture->emetteur->ville; ?>}
\def\InterproFacturation{\\<?php echo $facture->emetteur->telephone;?>
                                             <?php if($facture->emetteur->exist('email')): ?>
                                                    \\ Email : <?php echo $facture->emetteur->email; ?>
                                              <?php endif;?>}

\def\InterproSIRET{<?php echo $infosInterpro->siret; ?>}
\def\InterproAPE{APE <?php echo $infosInterpro->ape; ?>}
\def\InterproTVAIntracomm{<?php echo $infosInterpro->tva_intracom; ?>}

\def\InterproBANQUE{<?php echo str_replace(" ", "~", $coordonneesBancaires->banque); ?>}
\def\InterproBIC{<?php echo $coordonneesBancaires->bic; ?>}
\def\InterproIBAN{<?php echo $coordonneesBancaires->iban; ?>}

\def\FactureNum{<?php echo $facture->numero_piece_comptable; ?>}
\def\FactureDate{<?php echo format_date($facture->date_facturation,'dd/MM/yyyy'); ?>}
\def\NomRefClient{<?php echo $facture->numero_adherent; ?>}
\def\FactureRefClient{<?php echo $facture->numero_adherent; ?>}
\def\FactureRefCodeComptableClient{<?php echo (FactureConfiguration::getInstance()->getPdfDiplayCodeComptable())? $facture->code_comptable_client : $facture->numero_adherent; ?>}
\def\FactureClientNom{<?php $nom = ($facture->declarant->raison_sociale == '')? $facture->declarant->nom : $facture->declarant->raison_sociale;
                            echo display_latex_string($nom,';',40);
                     ?>}
\def\FactureClientAdresse{<?php $adresse = ($facture->declarant->adresse == '')? "~" : $facture->declarant->adresse;
                                                 echo display_latex_string($adresse,';',50,2); ?>}
\def\FactureClientAdresseComplementaire{<?php $adresseComplementaire = (!$facture->declarant->adresse_complementaire)? "~" : "\\\\".$facture->declarant->adresse_complementaire;
                                          echo display_latex_string($adresseComplementaire,';',50,2);   ?>}
\def\FactureClientCP{<?php echo $facture->declarant->code_postal; ?>}
\def\FactureClientVille{<?php echo $facture->declarant->commune; ?>}
\def\FactureReglement{ <?php echo FactureConfiguration::getInstance()->getReglement(); ?> }

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}

\fancyhf{}

\lhead{
 \textbf{\NomInterpro} \\
 \InterproAdresse \\
 \begin{small} \textbf{\begin{footnotesize}\InterproFacturation\end{footnotesize}}\\ \end{small}
 \begin{tiny}
         RIB~:~\InterproBANQUE~(BIC:~\InterproBIC~IBAN:~\InterproIBAN)
 \end{tiny} \\
 \begin{tiny}
         SIRET~\InterproSIRET ~-~\InterproAPE ~- TVA~Intracommunutaire~\InterproTVAIntracomm
\end{tiny}
 }
\rhead{\includegraphics[scale=0.7]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/images")."/logo_". sfConfig::get('sf_app').".png"; ?>}}

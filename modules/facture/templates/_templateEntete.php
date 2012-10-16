<?php
use_helper('Date');
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



\usetikzlibrary{fit}

\renewcommand\sfdefault{phv}

\newcommand{\CutlnPapillon}{	
  	\multicolumn{4}{|c|}{ ~~~~~~~~~~~~~~~~~~~~~~~ } & 
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\    
}

\newcommand{\CutlnPapillonEntete}{	
      & \centering \small{\textbf{Code échéance}} &
    \centering \small{\textbf{Date d'échéance}} &
    \multicolumn{1}{r|}{\small{\textbf{Montant TTC}}}  
     & 
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\    
}

\renewcommand{\familydefault}{\sfdefault}


\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{19cm}
\setlength{\headheight}{5cm}
\setlength{\topmargin}{-4.5cm}
\addtolength{\textheight}{29.9cm} 

\def\TVA{19.60} 
\def\InterloireAdresse{<?php echo $facture->emetteur->adresse; ?> \\
		       <?php echo $facture->emetteur->code_postal.' '.$facture->emetteur->ville; ?> - France} 
\def\InterloireFacturation{\\Votre contact : <?php echo $facture->emetteur->service_facturation.' - '.$facture->emetteur->telephone; ?>} 
\def\InterloireSIRET{429 164 072 00077}
\def\InterloireAPE{APE 9499 Z} 
\def\InterloireTVAIntracomm{FR 73 429164072}
\def\InterloireBANQUE{Crédit Agricole Atlantique Vendée}
\def\InterloireBIC{AGRIFRPP847}
\def\InterloireIBAN{FR76~1470~6000~1400~0000~2200~028}

\def\FactureNum{<?php echo $facture->numero_facture; ?>}
\def\FactureNumREF{<?php echo substr($facture->numero_facture,6,2).' '.substr($facture->numero_facture,0,6); ?>}
\def\FactureDate{<?php echo format_date($facture->date_emission,'dd/MM/yyyy'); ?>}
\def\FactureRefClient{<?php echo $facture->identifiant; ?>}

\def\FactureClientNom{<?php echo ($facture->declarant->raison_sociale == '')? $facture->declarant->nom : $facture->declarant->raison_sociale; ?>}
\def\FactureClientAdresse{<?php echo ($facture->declarant->adresse == '')? 'Adresse' : $facture->declarant->adresse; ?>}
\def\FactureClientCP{<?php echo $facture->declarant->code_postal; ?>}
\def\FactureClientVille{<?php echo $facture->declarant->commune; ?>}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}

\fancyhf{}

\lhead{
 \textbf{InterLoire - Service facturation} \\  
 \InterloireAdresse \\
 \textbf{\begin{footnotesize}\InterloireFacturation\end{footnotesize}}\\
 \begin{tiny}
         RIB~:~\InterloireBANQUE~(BIC:~\InterloireBIC~IBAN:~\InterloireIBAN) 
 \end{tiny} \\
 \begin{tiny}
         SIRET~\InterloireSIRET ~-~\InterloireAPE ~- TVA~Intracommunutaire~\InterloireTVAIntracomm
\end{tiny}
 }
\rhead{\includegraphics[scale=1]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo.jpg"; ?>}}
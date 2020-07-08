<?php
use_helper('Date');
use_helper('DRM');
use_helper('Orthographe');
use_helper('DRMPdf');
use_helper('Display');
?>
\documentclass[a4paper,oneside, landscape, 10pt]{extarticle}

\usepackage[english]{babel}
\usepackage[utf8]{inputenc}
\usepackage{units}
\usepackage{graphicx}
\usepackage{fp}
\usepackage[table]{xcolor}
\usepackage{lscape}
\usepackage{eso-pic}
\usepackage{tikz}
\usepackage{array,multirow,makecell}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{marvosym}
\usepackage{lastpage}
\usepackage{truncate}
\usepackage{fancyhdr}
\usepackage{lastpage}
\usepackage{amssymb}
\usepackage{geometry}

\usetikzlibrary{fit}

\renewcommand\sfdefault{phv}
\newcommand{\squareChecked}{\makebox[0pt][l]{$\square$}\raisebox{.15ex}{\hspace{0.1em}$\checkmark$}}
\renewcommand{\familydefault}{\sfdefault}
\renewcommand{\TruncateMarker}{\small{...}}

\usepackage{array}
\newcolumntype{L}[1]{>{\raggedright\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}
\newcolumntype{C}[1]{>{\centering\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}
\newcolumntype{R}[1]{>{\raggedleft\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}

\newcommand\BackgroundPic{
\put(0,0){
\parbox[b][\paperheight]{\paperwidth}{%
\vfill
\centering
\vfill
}}}

\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{29.7cm}
\setlength{\textheight}{15.5cm}
\setlength{\headheight}{3.5cm>}
\setlength{\headwidth}{28.2cm}
\setlength{\topmargin}{-3.5cm}
\setlength{\footskip}{0cm}

\begin{document}


\def\DeclarantRaisonSociale{<?php echo escape_string_for_latex($subvention->declarant->raison_sociale); ?>}
\def\DeclarantNom{<?php echo escape_string_for_latex($subvention->declarant->nom); ?>}
\def\DeclarantSiret{<?php echo $subvention->declarant->siret; ?>}
\def\DeclarantAdresse{<?php echo $subvention->declarant->adresse . ' ' . $subvention->declarant->code_postal . ' ' . $subvention->declarant->commune; ?>}
\def\DeclarantCapital{1 500 000,00€}
\def\DeclarantCA{712 350,00€}
\def\DeclarantEffectif{35}

\def\ContactDossier{Mme Michu - ginettemichu@labonnepiche.fr - 04.00.10.20.30}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}
\fancyhf{}

\lhead{
\vspace{-2cm}
Entreprise : \textbf{\DeclarantNom} \\
Adresse  : \textbf{\DeclarantAdresse} \\
}

\rhead{
\vspace{-2cm}
 \begin{large}
\textbf{Opération <?php echo escape_string_for_latex($subvention->operation); ?>} \\
\textbf{<?php if($subvention->signature_date): ?>Signé électroniquement le <?php echo $subvention->signature_date; ?><?php endif; ?>}
\end{large}
}

\rfoot{page \thepage\ / 1}







\end{document}

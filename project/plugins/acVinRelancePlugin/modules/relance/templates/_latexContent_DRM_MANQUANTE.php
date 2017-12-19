<?php
use_helper('Date');
use_helper('Relance');
use_helper('Display');
use_helper('Orthographe');
?>
\documentclass[a4paper,10pt]{extarticle}
\usepackage{geometry} % paper=a4paper
\usepackage[english]{babel}
\usepackage[utf8]{inputenc}
\usepackage{units}
\usepackage{geometry}
\usepackage{graphicx}
\usepackage{fancyhdr}
\usepackage{fp}
\usepackage[table]{xcolor}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{marvosym}
\usepackage{lastpage}
\usepackage{truncate}
\usepackage{tabularx}

\renewcommand\sfdefault{phv}
\renewcommand{\familydefault}{\sfdefault}

\pagestyle{empty}

\setlength{\oddsidemargin}{-1cm}
\setlength{\evensidemargin}{-1cm}
\setlength{\textwidth}{18cm}
\setlength{\textheight}{24.5cm}
\setlength{\topmargin}{-2cm}

\def\RELANCECLIENTNOM{<?php echo $relance->declarant->nom; ?>}

\def\RELANCECLIENTADRESSE{<?php echo display_latex_string($relance->declarant->adresse,';',50,2); ?>}

\def\RELANCECLIENTCP{<?php echo $relance->declarant->code_postal; ?>}
\def\RELANCECLIENTVILLE{<?php echo $relance->declarant->commune; ?>}


\def\RELANCEREGION{<?php echo getRegion($relance->region); ?>}
\def\RELANCEDATE{le <?php echo format_date($relance->date_creation,'dd/MM/yyyy'); ?>}

\def\RELANCEOBJECT{\underline{\textbf{Objet : <?php echoTypeRelance($relance->type_relance); ?>}}}
\def\RELANCEREF{\underline{\textbf{N/Réf : <?php echo substr($relance->identifiant, 0, 6);?>}}}

\def\RELANCECONTACT{<?php printContact($relance);?>}
\def\RELANCEINTRO{Madame, Monsieur, \\ \\ <?php echoIntroRelance($relance->type_relance);?>}

\def\RELANCERAPPELLOI{<?php printRappelLoi($relance->type_relance); ?>}


\def\RELANCEInterloireCONTACT{<?php echo sfConfig::get('app_relance_responsable_economique') ?><?php echo getServicesOperateurs($relance); ?>}


\begin{document}
\begin{minipage}[t]{1\textwidth}
\begin{minipage}[t]{0.40\textwidth}
~
\end{minipage}
\hfill
\begin{minipage}[t]{0.4\textwidth}
\vspace{2.5cm}
\textbf{\RELANCECLIENTNOM \\}
\RELANCECLIENTADRESSE \\
\RELANCECLIENTCP ~ \RELANCECLIENTVILLE
\\ \\ \\ \RELANCEREGION~\RELANCEDATE
\end{minipage}
\bigskip
\end{minipage}
\begin{flushleft}
\RELANCEREF \\
\RELANCEOBJECT \\
1ère relance\\
\bigskip
\underline{\textbf{Contact :}}\\
\RELANCECONTACT

\end{flushleft}

\RELANCEINTRO
\begin{itemize}
 \setlength\itemsep{0mm}
<?php foreach($relance->verifications as $verification) : ?>
    <?php foreach($verification->lignes as $ligne): ?>
           \item Mois <?php echo elision("de",getLigne($ligne->explications)); ?>
    <?php  endforeach; ?>
    <?php echo $verification->description_fin; ?>
<?php
endforeach;
?>
\end{itemize}

\RELANCERAPPELLOI

\section*{}

\RELANCEInterloireCONTACT
\end{document}

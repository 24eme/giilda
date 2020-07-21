<?php
use_helper('Date');
use_helper('DRM');
use_helper('Orthographe');
use_helper('Float');
use_helper('Display');
?>
\documentclass[a4paper,oneside, portrait, 10pt]{extarticle}

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
\usepackage{amssymb}
\usepackage{geometry}
\usepackage{indentfirst}
\usetikzlibrary{fit}
\usepackage{enumitem}

\newlist{todolist}{itemize}{2}
\setlist[todolist]{label=$\square$}
\usepackage{pifont}
\newcommand{\cmark}{\ding{51}}%
\newcommand{\xmark}{\ding{55}}%
\newcommand{\done}{\rlap{$\square$}{\raisebox{2pt}{\large\hspace{1pt}\cmark}}%
\hspace{-2pt}}
\newcommand{\wontfix}{\rlap{$\square$}{\large\hspace{1pt}\xmark}}

\renewcommand\sfdefault{phv}
\newcommand{\squareChecked}{\makebox[0pt][l]{$\square$}\raisebox{.15ex}{\hspace{0.1em}$\checkmark$}}
\renewcommand{\familydefault}{\sfdefault}
\renewcommand{\TruncateMarker}{\small{...}}

\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{20cm}
\setlength{\textheight}{28.7cm}
\setlength{\topmargin}{-3.5cm}
\setlength{\footskip}{0cm}

\def\Plateforme{<?php echo escape_string_for_latex($subvention->getConfiguration()->getPlateforme()); ?>}
\def\Referent{<?php echo escape_string_for_latex($subvention->getConfiguration()->getReferent()); ?>}
\def\DeclarantRaisonSociale{<?php echo escape_string_for_latex($subvention->declarant->raison_sociale); ?>}
\def\DeclarantSiret{<?php echo $subvention->declarant->siret; ?>}
\def\DeclarantAdresse{<?php echo escape_string_for_latex($subvention->declarant->adresse); ?>}
\def\DeclarantCp{<?php echo $subvention->declarant->code_postal; ?>}
\def\DeclarantVille{<?php echo escape_string_for_latex($subvention->declarant->commune); ?>}
\def\DeclarantCapital{<?php echo escape_string_for_latex(sprintFloatFr($subvention->infos->economique->capital_social, "%01.02f", true). " €"); ?>}
\def\DeclarantEffectif{<?php echo escape_string_for_latex(str_replace(".", ",", $subvention->infos->economique->etp)); ?>}
\def\DeclarantPermanent{<?php echo escape_string_for_latex(str_replace(".", ",", $subvention->infos->economique->effectif_permanent)); ?>}
\def\ContactDossierNom{<?php echo escape_string_for_latex($subvention->infos->contacts->nom); ?>}
\def\ContactDossierTel{<?php echo escape_string_for_latex($subvention->infos->contacts->telephone); ?>}
\def\ContactDossierEmail{<?php echo escape_string_for_latex($subvention->infos->contacts->email); ?>}
\def\DateSignature{<?php echo $subvention->signature_date; ?>}
\def\logos{<?php echo sfConfig::get('sf_web_dir'); ?>/images/logos_region_occitanie.jpg}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}
\fancyhf{}

\title{\vspace{-1cm}\textbf{Contrat Relance Viti \\ Fiche de pré-qualification}}
\date{}

\def\arraystretch{3}
\setlength\extrarowheight{-3pt}
\newcolumntype{L}[1]{>{\raggedright\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}
\newcolumntype{C}[1]{>{\centering\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}
\newcolumntype{R}[1]{>{\raggedleft\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}

\setlength{\textfloatsep}{0pt}

\fancyfoot[R]{\thepage ~/ \pageref{LastPage}}

\fancypagestyle{plain}{
\fancyfoot[R]{\thepage ~/ \pageref{LastPage}}
}

\begin{document}


\begin{figure}[t]
  \centering
  \includegraphics[scale=1]{\logos}
\end{figure}

\maketitle

\vspace{-2cm}

\begin{tabular}{|L{3cm}|L{5.5cm}|L{3cm}|L{5.5cm}|}
\hline
\small{Portail} & \textbf{\Plateforme} & \small{Agent référent} & \textbf{\Referent} \\
\hline
\small{Date de réception de la demande} & \textbf{\DateSignature} & \small{Personne en charge du dossier au sein de l'entreprise} & \textbf{\ContactDossierNom} \\
\hline
\end{tabular} 

\vspace{0.5cm}

\section{IDENTIFICATION DU DEMANDEUR}

Qualité du demandeur : <?php if ($subvention->getEtablissement()->isNegociant()): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> Négociant \medbreak
Metteur en marché direct : <?php if ($subvention->getEtablissement()->isCaveCooperative()): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> Cave coopérative <?php if ($subvention->getEtablissement()->isViticulteur()): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> Vigneron indépendant \bigbreak
Raison sociale : \textbf{\DeclarantRaisonSociale} \medbreak
SIRET : \textbf{\DeclarantSiret} \bigbreak
Adresse : \textbf{\DeclarantAdresse} \medbreak
Code Postal : \textbf{\DeclarantCp} \hspace{0.5cm} Ville : \textbf{\DeclarantVille} \bigbreak
<?php foreach($subvention->infos as $categorie => $items): ?>
\textbf{<?php echo $items->getLibelle() ?>} \medbreak
<?php foreach($items as $key => $item): ?>
<?php echo $items->getInfosSchemaItem($key, "label") ?>~: \textbf{<?php echo $item ?>}~<?php echo $items->getInfosSchemaItem($key, "unite") ?> \medbreak
<?php endforeach; ?>
<?php endforeach; ?>

<?php if ($subvention->signature_date): ?>

\section{ENGAGEMENTS DU BÉNÉFICIAIRE}

\begin{todolist}[itemsep=7pt,parsep=7pt]
    \item Atteste le non commencement de ces opérations au 1er juillet 2020 (aucun devis signé antérieur au 1er juillet 2020)
    \item Respecte les conditions d’éligibilité dans le cadre des opérations à portée collective :
    \begin{todolist}[itemsep=7pt,parsep=7pt]
      \item Présence des logos envisagée
      \item Utilisation de la charte graphique
      \item Visibilité minimale du message collectif envisagée
    \end{todolist}
    \item Atteste avoir pris connaissance qu’une vérification au paiement sera réalisée
\end{todolist}

\newpage

~ \\ 

\vspace{0,6cm}


\section{CRITÈRES DE PRÉ-QUALIFICATION}

\begin{tabular}{|C{0.5cm}|L{10cm}|L{7cm}|}
\hline
  \multicolumn{2}{|c|}{\cellcolor{gray!25}CRITÈRES} & \cellcolor{gray!25} \hfill APPRÉCIATION\hfill\null  \\
\hline
$\square$ & Respect des accords interprofessionnels ou engagement & ~ \\
\hline
$\square$ & Opérations concernant les vins conditionnés sous signe de qualité issus des AOP et IGP de la Région : \begin{enumerate}[itemsep=1pt,parsep=1pt] \item Pays d’OC/Terres du Midi \item AOC du Languedoc/IGP Sud de France \item Vins du Sud-Ouest \item Vins de la Vallée du Rhône \item Vins du Roussillon (AOP/IGP) \end{enumerate} & ~ \\
\hline
$\square$ & \underline{Pour les négociants}, contractualisation : \begin{todolist}[itemsep=1pt,parsep=1pt] \item effective* \item engagement* \end{todolist} \small{* Préciser obligatoirement les volumes concernés} & ~ \\
\hline
$\square$ & Eligibilité et appréciation de la faisabilité et de la cohérence des opérations présentées (adéquation coût/action...) & ~ \\
\hline
\end{tabular}

\section{CONCLUSION}

\begin{tabular}{|L{8,75cm}|L{8,75cm}|}
\hline
$\square$ Favorable \begin{todolist}[itemsep=1pt,parsep=1pt] \item sur l'ensemble des actions \item sur les actions n° \end{todolist} Commentaires : \bigbreak ~ \bigbreak ~ \bigbreak & Numéro de dossier (facultatif): \bigbreak Version du dossier : \begin{todolist}[itemsep=1pt,parsep=1pt] \item initiale \item modifiée \end{todolist}  \\
\hline
\multicolumn{2}{|l|}{$\square$  Proposition de rejet} \\
\multicolumn{2}{|l|}{Motif : } \\
\multicolumn{2}{|l|}{~} \\
\multicolumn{2}{|l|}{~} \\
\hline
\end{tabular}

\bigbreak
Date : \textbf{\DateSignature} \bigbreak
Signature de l'agent référent ou de son représentant :

<?php endif; ?>

\end{document}
<?php
use_helper('Date');
use_helper('Relance');
use_helper('Display');
?>
\documentclass[a4paper,8pt]{extarticle}
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

\setlength{\oddsidemargin}{-1cm}
\setlength{\evensidemargin}{-1cm}
\setlength{\textwidth}{18cm}
\setlength{\textheight}{24.5cm}
\setlength{\topmargin}{-2cm}

\def\RELANCECLIENTNOM{<?php echo $relance->declarant->nom; ?>}

\def\RELANCECLIENTADRESSE{<?php display_latex_string($relance->declarant->adresse,';',50,2); ?>}

\def\RELANCECLIENTCP{<?php echo $relance->declarant->code_postal; ?>}
\def\RELANCECLIENTVILLE{<?php echo $relance->declarant->commune; ?>}


\def\RELANCEREGION{<?php echo getRegion($relance->region); ?>}
\def\RELANCEDATE{le <?php echo format_date($relance->date_creation,'dd/MM/yyyy'); ?>}

\def\RELANCEOBJECT{\underline{\textbf{Objet : <?php echoTypeRelance($relance->type_relance); ?>}}}
\def\RELANCEREF{\underline{\textbf{N/Réf : <?php echo $relance->identifiant;?>}}}
\def\RELANCEINTRO{Madame, Monsieur, \\~\\ <?php echoIntroRelance($relance->type_relance);?>}
\def\RELANCEFORMULE{<?php printRelanceFormule($relance); ?>}

\def\RELANCERAPPELLOI{Nous vous rappelons qu’en vertu de l’article V-4 de l’Accord interprofessionnel en vigueur, InterLoire a la possibilité~:\\
\begin{itemize}
	\item
	d’émettre une mise en demeure pour obtention des déclarations non déposées : `` Lorsque le professionnel concerné omet d’effectuer l’une des déclarations auxquelles il est assujetti, y compris en copie, en application du présent accord, InterLoire peut mettre en demeure le professionnel de déposer les dites déclarations. ''
	\item
	d’effectuer une évaluation d’office : `` La notification d’évaluation d’office fait référence à la procédure d’évaluation d’office de l’article L.632-6 du Code Rural et de la pêche maritime, porte mention de la période pour laquelle l’assiette de la cotisation est évaluée d’office, indique le mode de calcul de l’évaluation d’office, et le montant des cotisations dues en conséquence de cette évaluation. ''
\end{itemize}
Dans cette attente, nous vous prions d’agréer, Madame, Monsieur, l’expression de nos sincères salutations.}

\def\RELANCESIGNATURE{<?php echo $relance->responsable_financier; ?> \\ Responsable Administratif et Financier}

\def\RELANCEInterloireCONTACT{Le service Transactions de <?php echo $relance->region;?> : \\ \\<?php echo getServicesOperateurs($relance); ?>}


\begin{document}
\begin{minipage}[t]{1\textwidth}
\begin{minipage}[t]{0.40\textwidth}
\includegraphics[scale=0.8]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo.jpg"; ?>}
\end{minipage}
\hfill
\begin{minipage}[t]{0.4\textwidth}
\textbf{\RELANCECLIENTNOM \\}
\textbf{\RELANCECLIENTADRESSE} \\
\RELANCECLIENTCP ~ \RELANCECLIENTVILLE
\\ \\ \\ \RELANCEREGION~\RELANCEDATE
\end{minipage}
\bigskip
\end{minipage}
\begin{flushleft}
\RELANCEOBJECT \\
\RELANCEREF \\
\end{flushleft}

\hspace{1.5cm}

\begin{flushleft}
\RELANCEINTRO
\end{flushleft}



<?php foreach($relance->verifications as $verification) : ?>
    \section*{\small{$\bullet$ <?php echo $verification->titre; ?> \textit{(<?php echo $verification->refarticle; ?>)}}}
    <?php echo $verification->description; ?> \\

    <?php if($verification->multiple):  ?>
        <?php if(count($verification->lignes) < GenerationRelancePDF::MAX_LIGNE_TABLEAUX): ?>
            \begin{center}
            \begin{tabularx}{\textwidth}{<?php echo getTableFormatVerification($verification); ?>}
        <?php echo getTableRowHead($verification->liste_champs); ?> \\
        <?php foreach($verification->lignes as $ligne): ?>
            <?php echo getTableLigne($ligne->explications)?> \\
        <?php endforeach; ?>
        \end{tabularx}
        \end{center}

        <?php else:
            $nbPages = count($verification->lignes) / GenerationRelancePDF::MAX_LIGNE_TABLEAUX;
            for($i=0; $i< $nbPages;$i++):
            $start_row = $i* GenerationRelancePDF::MAX_LIGNE_TABLEAUX;
            $end_row = ($i < $nbPages-1)? (($i+1)* GenerationRelancePDF::MAX_LIGNE_TABLEAUX) : (count($verification->lignes));
            ?>
            \begin{center}
            \begin{tabularx}{\textwidth}{<?php echo getTableFormatVerification($verification); ?>}
            <?php echo getTableRowHead($verification->liste_champs); ?> \\
            <?php for($indice=$start_row;$indice<$end_row;$indice++):
                    $ligne = $verification->lignes[$indice];
                ?>
                <?php echo getTableLigne($ligne->explications)?> \\
            <?php endfor; ?>
            \end{tabularx}
            \end{center}
        <?php
            endfor;
            endif;
        else:
    ?>
    <?php foreach($verification->lignes as $ligne): ?>
            <?php echo getLigne($ligne->explications); ?> \\
    <?php  endforeach; ?>
    <?php
    endif;
    ?>
    <?php echo $verification->description_fin; ?>
<?php
endforeach;
?>

\section*{}

\begin{flushleft}
\RELANCERAPPELLOI
\end{flushleft}
\\
\RELANCESIGNATURE \\
\section*{\small{\underline{Votre contact si nécessaire:}}}

\RELANCEInterloireCONTACT
\end{document}

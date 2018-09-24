<?php
use_helper('Date');
use_helper('DRMPdf');
use_helper('Display');

$droitsDouane = $drm->droits->douane;
$paiement_douane_moyen = ($drm->societe->exist('paiement_douane_moyen'))? $drm->societe->paiement_douane_moyen : null;
$frequence_douane_moyen = ($drm->societe->exist('paiement_douane_frequence'))? $drm->societe->paiement_douane_frequence : null;

$hasAnnexes = $drm->hasAnnexes();
$hasNonApurement = $drm->exist('releve_non_apurement') && count($drm->releve_non_apurement);
$limitNonAppurement = 4;
if(!$hasAnnexes && $hasNonApurement){
    $limitNonAppurement = 12;
}
$douaneNewPage = ($hasNonApurement && (count($drm->releve_non_apurement) >= $limitNonAppurement))? "\\newpage" : "";

$hasObservations = $drm->exist('observations') && $drm->observations;
?>

<?php if ($hasAnnexes || $hasNonApurement) : ?>
    \vspace{0.5cm}
    \begin{center}
    \begin{large}
    \textbf{Documents douanier}
    \end{large}
    \end{center}
   <?php endif; ?>
  <?php if ($hasAnnexes): ?>
    \begin{tabular}{C{90mm} |C{90mm}|C{90mm}|}
    \multicolumn{3}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Documents d'accompagnement}}}}
    \\
    \hline
    \rowcolor{lightgray}
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Type de document}}} &
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Numéro début}}} &
    \multicolumn{1}{|C{90mm}|}{\small{\textbf{Numéro fin}}}
    \\
    \hline
    <?php foreach ($drm->documents_annexes as $typeDoc => $numsDoc): ?>

        \multicolumn{1}{|l}{\small{\textbf{<?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?>}}} &
        \multicolumn{1}{|r}{\small{\textbf{<?php echo $numsDoc->debut; ?>}}} &
        \multicolumn{1}{|r|}{\small{\textbf{<?php echo $numsDoc->fin; ?>}}}
        \\
        \hline

    <?php endforeach; ?>
    \end{tabular}
    \vspace{0.2cm}
<?php endif; ?>
<?php if ($hasNonApurement) : ?>
    \begin{tabular}{C{90mm} |C{90mm}|C{90mm}|}
    \multicolumn{3}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Relevé de non apurement}}}}
    \\
    \hline
    \rowcolor{lightgray}
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Numéro de  document}}} &
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Date d'expédition}}} &
    \multicolumn{1}{|C{90mm}|}{\small{\textbf{Numéro d'accise destinataire}}}
    \\
    \hline
    <?php foreach ($drm->releve_non_apurement as $num_non_apurement => $non_apurement): ?>

        \multicolumn{1}{|l}{\small{\textbf{<?php echo $non_apurement->numero_document; ?>}}} &
        \multicolumn{1}{|r}{\small{\textbf{<?php echo $non_apurement->date_emission; ?>}}} &
        \multicolumn{1}{|r|}{\small{\textbf{<?php echo $non_apurement->numero_accise; ?>}}}
        \\
        \hline

    <?php endforeach; ?>
    \end{tabular}
<?php endif; ?>

<?php echo $douaneNewPage; ?>
\vspace{0.5cm}
\begin{center}
\begin{large}
\textbf{Liquidation des droits}
\end{large}
\end{center}

\begin{tabular}{C{52mm}|C{52mm}|C{52mm}|C{52mm}|C{52mm}|}
\multicolumn{5}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Droits de circulation}}}}
\\
\hline
\rowcolor{lightgray}
\multicolumn{1}{|C{52mm}}{\small{\textbf{Code}}} &
\multicolumn{1}{|C{52mm}}{\small{\textbf{Libellé}}} &
\multicolumn{1}{|C{52mm}|}{\small{\textbf{Volume}}} &
\multicolumn{1}{|C{52mm}|}{\small{\textbf{Taux}}} &
\multicolumn{1}{|C{52mm}|}{\small{\textbf{Total du mois}}}
\\
\hline
<?php foreach ($droitsDouane as $droitDouane): ?>
    \multicolumn{1}{|l}{\small{\textbf{<?php echo $droitDouane->code; ?>}}} &
    \multicolumn{1}{|l}{\small{\textbf{<?php echo $droitDouane->libelle; ?>}}} &
    \multicolumn{1}{|r|}{\small{\textbf{<?php echo sprintFloat($droitDouane->getVolume()).' hl';  ?>}}} &
    \multicolumn{1}{|r|}{\small{\textbf{<?php echo $droitDouane->taux.' €/hl'; ?>}}} &
    \multicolumn{1}{|r|}{\small{\textbf{<?php echo sprintDroitDouane($droitDouane->total).' €'; ?>}}}

    \\
    \hline
<?php endforeach; ?>
\end{tabular}

\vspace{0.5cm}

<?php if($hasObservations): ?>
\begin{tabular}{|C{280mm}|}
\multicolumn{1}{|C{280mm}|}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Observations}}}}
\\
\hline
\multicolumn{1}{|C{280mm}|}{<?php echo $drm->observations; ?>} \\
\hline
\end{tabular}

 \vspace{0.5cm}

 <?php endif; ?>
\fcolorbox{white}{white}{
\hspace{-0.25cm}
\begin{minipage}[t]{0.6\textwidth}
\begin{tabular}{|C{170mm}|}
\multicolumn{1}{|c|}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{PARTIE RÉSERVÉE À L'ADMINISTRATION}}}}
\\
\hline
\multicolumn{1}{|l|}{\small{\underline{\textbf{RÉCEPTION}}}} \\
\multicolumn{1}{|L{170mm}|}{\small{Date :~~~\dotfill\dotfill\dotfill ~~~~ N° :~~~\dotfill\dotfill\dotfill }}
\\
\hline
\multicolumn{1}{|l|}{\small{\underline{\textbf{PRISE EN RECETTE}}}} \\
\multicolumn{1}{|L{170mm}|}{\small{Montant :~~~\dotfill\dotfill\dotfill\dotfill\dotfill\dotfill }}
\\
\multicolumn{1}{|L{170mm}|}{\small{Date :~~~\dotfill\dotfill\dotfill ~~~~ N° Caisse :~~~\dotfill\dotfill\dotfill }} \\
\multicolumn{1}{|L{170mm}|}{\small{Dispense~:~$\square$~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~CL~:~$\square$~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~CE~:~$\square$~~~~~~~~~~~~}}
\\
\hline
\multicolumn{1}{|L{170mm}|}{{\small{\underline{\textbf{VISA DU SERVICE DES DOUANES ET DROITS INDIRECTS}}}}} \\
~ \\ ~ \\ \hline
\end{tabular}
\end{minipage}

\begin{minipage}[t]{0.4\textwidth}
\begin{tabular}{|C{100mm}|}
\hline
~ \\
<?php if($drm->isValidee()): ?>
    \multicolumn{1}{|l|}{\small{\underline{\textbf{Fait}} : sur la plateforme <?php echo sfConfig::get('app_teledeclaration_url'); ?>}} \\	~ \\
    \multicolumn{1}{|l|}{\small{\underline{\textbf{Le}} : <?php echo $drm->getEuValideDate(); ?> }} \\	~ \\
<?php else: ?>
    \multicolumn{1}{|l|}{} \\	~ \\
    \multicolumn{1}{|l|}{} \\	~ \\
<?php endif; ?>
\hline
~ \\
~ \\
~ \\
~ \\
~ \\
\hline
\end{tabular}
\end{minipage}
}

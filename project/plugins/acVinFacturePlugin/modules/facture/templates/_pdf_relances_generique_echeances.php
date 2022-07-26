<?php
use_helper('Float');
use_helper('Date');
$chequesOrdre = FactureConfiguration::getInstance()->getOrdreCheques();
?>

\begin{center}

\begin{minipage}[b]{1\textwidth}

\begin{tabular}{|p{0mm} p{87mm} | p{36mm} p{36mm} p{36mm}|}
            \hline
	\multicolumn{2}{|>{\columncolor[rgb]{0.8,0.8,0.8}}c|}{\centering \small{\textbf{Modalités de règlement}}} &
	\multicolumn{3}{>{\columncolor[rgb]{0.8,0.8,0.8}}c}{\centering \small{\textbf{Références de facturation}}} \\

        \CutlnPapillonEntete
        &
   \centering \fontsize{7}{8}\selectfont Par chèque à l'ordre : <?php echo ($chequesOrdre)? $chequesOrdre : "Ordre chèque"; ?> \\ ~ &

    \centering \small{Echéance} &
    \centering \small{Client~/~Relance} &
    \multicolumn{1}{c}{\small{Cotisation due}} \\

                \centering \small{~} &
                \centering \fontsize{7}{8}\selectfont Par virement bancaire : \InterproBANQUE \\  \textbf{BIC~:}~\InterproBIC~\textbf{IBAN~:}~\InterproIBAN &

                \centering \small{\textbf{A reception}} &
                \centering \small{\FactureRefCodeComptableClient~/~\FactureNum} &
                \multicolumn{1}{r}{\small{\textbf{<?php echoArialFloat($papillon->montant_ttc); ?>~\texteuro{}}}}  \\


      \CutlnPapillon
\end{tabular}
\end{minipage}
\end{center}

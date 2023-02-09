<?php
use_helper('Float');
use_helper('Date');
$chequesOrdre = $factureConfiguration->getOrdreCheques();
$echeances = $facture->getEcheancesPapillon();
?>
\begin{center}

\begin{minipage}[b]{1\textwidth}

\begin{tabular}{|p{0mm} p{87mm} | p{36mm} p{36mm} p{36mm}|}
            \hline
	\multicolumn{2}{|>{\columncolor[rgb]{0.8,0.8,0.8}}c|}{\centering \small{\textbf{Modalités de règlement}}} &
	\multicolumn{3}{>{\columncolor[rgb]{0.8,0.8,0.8}}c}{\centering \small{\textbf{Références de facturation}}} \\

        \CutlnPapillonEntete
        <?php if($facture->getNbPaiementsAutomatique()): ?>
          &
          \centering \fontsize{7}{8}\selectfont Par chèque à l'ordre : <?php echo ($chequesOrdre)? $chequesOrdre : "Ordre chèque"; ?> \\ ~ &

           \centering \small{Echéance} &
           \centering \small{Client~/~Facture} &
           \multicolumn{1}{c}{\small{Montant TTC}} \\

                       \centering \small{~} &
                       \centering \fontsize{7}{8}\selectfont Par traite sur le compte \textbf{<?php echo $facture->getSociete()->getMandatSepa()->getBanqueNom() ?>} \\  \textbf{RIB~:<?php echo $facture->getSociete()->getMandatSepa()->getRibFormate() ?>}~ &

                  \centering \small{\textbf{<?php echo format_date($facture->date_echeance,'dd/MM/yyyy'); ?>}} &
                  \centering \small{\FactureRefCodeComptableClient~/~\FactureNum} &
                  \multicolumn{1}{r}{\small{\textbf{<?php echo echoArialFloat($facture->total_ttc); ?>~\texteuro{}}}}  \\


        <?php else: ?>
        <?php $nb = count($echeances) ; foreach ($echeances as $key => $papillon) : ?>
        &
   \centering \fontsize{7}{8}\selectfont Par chèque à l'ordre : <?php echo ($chequesOrdre)? $chequesOrdre : "Ordre chèque"; ?> \\ ~ &

    \centering \small{Echéance} &
    \centering \small{Client~/~Facture} &
    \multicolumn{1}{c}{\small{Montant TTC}} \\

                \centering \small{~} &
                \centering \fontsize{7}{8}\selectfont Par virement bancaire : \InterproBANQUE \\  \textbf{BIC~:}~\InterproBIC~\textbf{IBAN~:}~\InterproIBAN &

                \centering \small{\textbf{<?php echo format_date($papillon->echeance_date,'dd/MM/yyyy'); ?>}} &
                \centering \small{\FactureRefCodeComptableClient~/~\FactureNum} &
                \multicolumn{1}{r}{\small{\textbf{<?php echo echoArialFloat($papillon->montant_ttc); ?>~\texteuro{}}}}  \\


        <?php endforeach; ?>
      <?php endif; ?>
      \CutlnPapillon
\end{tabular}
\end{minipage}
\end{center}

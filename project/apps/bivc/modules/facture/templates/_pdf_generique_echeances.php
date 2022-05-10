<?php
use_helper('Float');
use_helper('Date');
$chequesOrdre = FactureConfiguration::getInstance()->getOrdreCheques();
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
     \centering \fontsize{7}{8}\selectfont Conformément à votre demande, le montant de cette facture sera prélevé \\ ~ &

      \centering \small{Echéance} &
      \centering \small{Client~/~Facture} &
      \multicolumn{1}{c}{\small{Montant TTC}} \\

                  \centering \small{~} &
                  \centering \fontsize{7}{8}\selectfont sur votre compte \textbf{<?php echo $facture->getSociete()->getMandatSepa()->getBanqueNom() ?>} n° \textbf{<?php echo $facture->getSociete()->getMandatSepa()->getNumeroCompte() ?>} le \textbf{<?php echo format_date($facture->paiements[0]->date,'dd/MM/yyyy'); ?>} &

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
                \multicolumn{1}{r}{\small{\textbf{<?php echo echoArialFloat($papillon->montant_ttc); ?>~\texteuro{}}}} \\

               \multicolumn{2}{|c|}{~} & \multicolumn{3}{c}{\centering\fontsize{7}{8}\selectfont Si paiement par chèque, ne pas agrafer.} \\
        <?php endforeach; ?>
      <?php endif; ?>
      \CutlnPapillon
\end{tabular}
\end{minipage}
\end{center}

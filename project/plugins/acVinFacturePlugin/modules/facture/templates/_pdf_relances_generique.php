<?php
use_helper('Float');
use_helper('Display');

include_partial('facture/pdf_generique_prelatex', array('pdf_titre' => 'Relance', 'ressortissant' => $infos));
include_partial('facture/pdf_relances_generique_entete', array('infos' => $infos));
?>
\centering
\fontsize{8}{10}\selectfont
    \begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
                        \renewcommand{\arraystretch}{1.2}
			\begin{tabular}{p{68mm} |p{60mm}|p{24mm}|p{24mm}p{0mm}}
  			\rowcolor{lightgray}
                        \centering \small{\textbf{N° d'appel}} &
   			            \centering \small{\textbf{Date d'appel}} &
                        \centering \small{\textbf{Montant total TTC \tiny{en \texteuro{}}}} &
   			            \centering \small{\textbf{Solde du \tiny{en \texteuro{}}}} &
   			 \\
  			\hline
                      ~ & ~ & ~ & ~ &\\

<?php foreach ($factures as $facture): ?>
    \multicolumn{1}{r|}{<?php echo $facture[12] ?>} &
    \multicolumn{1}{r|}{<?php echo $facture[13] ?>} &
    \multicolumn{1}{r|}{<?php echoArialFloat($facture[14]) ?>} &
    \multicolumn{1}{r}{<?php echoArialFloat($facture[15]) ?>}
    \\
<?php endforeach; ?>
    \end{tabular}
  };
    \node[draw=gray, inner sep=-2pt, rounded corners=3pt, line width=2pt, fit=(tab1.north west) (tab1.north east) (tab1.south east) (tab1.south west)] {};
\end{tikzpicture}
<?php
include_partial('facture/pdf_relances_generique_reglement');
//include_partial('facture/pdf_relances_generique_echeances', array('facture' => $facture));
?>
\end{document}

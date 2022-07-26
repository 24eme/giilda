<?php
use_helper('Float');
use_helper('Display');
use_helper('Date');

include_partial('facture/pdf_generique_prelatex', array('pdf_titre' => 'Relance', 'ressortissant' => $infos));
include_partial('facture/pdf_relances_generique_entete', array('infos' => $infos));
if ($infos->nb_relance > 1) {
  include_partial('facture/pdf_relances_pretexte_r2', array('date' => $infos->date_derniere_relance));
} else {
  include_partial('facture/pdf_relances_pretexte_r1');
}
?>

\centering
\fontsize{8}{10}\selectfont
    \begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
                        \renewcommand{\arraystretch}{1.2}
			\begin{tabular}{p{52mm} |p{52mm}|p{35mm}|p{35mm}p{0mm}}
  			\rowcolor{lightgray}
                        \centering \small{\textbf{N° d'appel}} &
   			            \centering \small{\textbf{Date d'appel}} &
                        \centering \small{\textbf{Montant total TTC \tiny{en \texteuro{}}}} &
   			            \centering \small{\textbf{Solde du \tiny{en \texteuro{}}}} &
   			 \\
  			\hline
                      ~ & ~ & ~ & ~ &\\

<?php $papillon = new stdClass(); $papillon->montant_ttc = 0; foreach ($factures as $facture): $papillon->montant_ttc += $facture[15]; ?>
    \multicolumn{1}{c|}{<?php echo $facture[12] ?>} &
    \multicolumn{1}{c|}{<?php echo format_date($facture[13],'dd/MM/yyyy'); ?>} &
    \multicolumn{1}{r|}{<?php echoArialFloat($facture[14]) ?>} &
    \multicolumn{1}{r}{<?php echoArialFloat($facture[15]) ?>}
    \\
<?php endforeach; ?>
    \end{tabular}
  };
    \node[draw=gray, inner sep=-2pt, rounded corners=3pt, line width=2pt, fit=(tab1.north west) (tab1.north east) (tab1.south east) (tab1.south west)] {};
\end{tikzpicture}
\begin{flushright}
\textbf{TOTAL DU : <?php echoArialFloat($papillon->montant_ttc) ?>~\texteuro{}}
\end{flushright}



<?php
if ($infos->nb_relance > 1) {
  include_partial('facture/pdf_relances_posttexte_r2');
} else {
  include_partial('facture/pdf_relances_posttexte_r1');
}
include_partial('facture/pdf_relances_generique_reglement');
include_partial('facture/pdf_relances_generique_echeances', array('papillon' => $papillon));
?>
\end{document}

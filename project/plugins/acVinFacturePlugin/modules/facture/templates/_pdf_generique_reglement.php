<?php
use_helper('Float');
?>


\noindent{
\begin{minipage}[b]{1\textwidth}
\noindent{
\begin{flushleft}

\begin{minipage}[b]{0.75\textwidth}
\begin{tiny}
<?php if ($facture->isFactureDRM()): ?>
  \FactureReglement
<?php else: ?>\vspace{4.1cm}<?php endif; ?>
\end{tiny}
\end{minipage}
\end{flushleft}
}
\vspace{-2.7cm}
\begin{flushright}
\begin{minipage}[b]{0.205\textwidth}
\vspace{-2.6cm}
\begin{tikzpicture}
\node[inner sep=1pt] (tab2){
\begin{tabular}{>{\columncolor{lightgray}} l | p{22mm}}

\centering \small{\textbf{Montant HT}} &
\multicolumn{1}{r}{\small{<?php echoArialFloat($facture->total_ht); ?>~\texteuro{}}} \\

\centering \small{} &
\multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\

                    \centering \small{\textbf{TVA <?php echo number_format($facture->getTauxTva(), 1, '.', ' ');?>~\%}} &
\multicolumn{1}{r}{\small{<?php echoArialFloat($facture->taxe); ?>~\texteuro{}}} \\

\centering \small{} &
\multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\
\hline
\centering \small{} &
\multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\

\centering \small{\textbf{Montant TTC}} &
\multicolumn{1}{r}{\small{<?php echoArialFloat($facture->total_ttc); ?>~\texteuro{}}}   \\
\end{tabular}
};
\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab2.north west) (tab2.north east) (tab2.south east) (tab2.south west)] {};
\end{tikzpicture}
\end{minipage}
\end{flushright}
\end{minipage}
}
\vspace{2.8cm}

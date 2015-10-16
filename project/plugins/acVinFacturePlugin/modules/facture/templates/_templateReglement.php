<?php
use_helper('Float');
?>

        
\noindent{
<?php if(!$avoir) : ?>
\begin{minipage}[b]{1\textwidth}
\noindent{
       \begin{flushleft}
       
       \begin{minipage}[b]{0.75\textwidth}
       \begin{tiny}
        \textbf{Dispositions Réglementaires issues de la loi du 10 juillet 1975 : } \\
         Extrait de l'article 3 de la loi du 10 juillet 1975 (modifiée par la loi d'orientation du 4 juillet 1980) \\
Les organisations interprofessionnelles reconnues, visées à l'article 1er, sont habilitées à prélever, sur tous les membres des professions les constituant, des cotisations résultant des accords étendus selon la procédure fixée à l'article précédent et qui, nonobstant leur caractère obligatoire, demeurent des créances de droit privé.
Extrait de l'article 4 de la loi du 10 juillet 1975 (modifiée par la loi d'orientation du 4 juillet 1980) \\
En cas de violation des règles résultant des accords étendus, il sera alloué par le juge d'instance, à la demande de l'organisation interprofessionnelle et à son profit, une indemnité dont les limites sont comprises entre 76 euros et la réparation intégrale du préjudice subi. \\
Extrait de l'article 4 bis de la loi du 10 juillet 1975 (modifiée par la loi d'orientation du 4 juillet 1980) \\
Lorsque, à l'expiration d'un délai de trois mois suivant leur date d'exigibilité, les cotisations prévues à l'article 3 ci-dessus ou une indemnité allouée en application de l'article 4 ci-dessus n'ont pas été acquittées, l'organisation interprofessionnelle peut, après avoir mis en demeure le redevable de régulariser sa situation, utiliser la procédure d'opposition prévue à l'alinéa 3° de l'article 1143-2 du code rural.\\
    \end{tiny}    
\end{minipage}
\end{flushleft}
}
\vspace{-2.7cm}
<?php endif; ?>
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
<?php if(!$avoir) : ?> \end{minipage} <?php endif; ?>
}
 \vspace{2.8cm}
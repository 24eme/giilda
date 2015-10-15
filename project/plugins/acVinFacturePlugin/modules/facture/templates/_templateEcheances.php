<?php
use_helper('Float');
use_helper('Date');
?>
\begin{center}

\begin{minipage}[b]{1\textwidth}

\begin{tabular}{|p{9mm} p{70mm} | p{36mm} p{36mm} p{36mm}}
            \hline
	\multicolumn{2}{|>{\columncolor[rgb]{0.8,0.8,0.8}}c|}{\centering \small{\textbf{N° de TVA intracommunautaire : <?php echo $societe->no_tva_intracommunautaire; ?>}}} &
	\multicolumn{3}{>{\columncolor[rgb]{0.8,0.8,0.8}}c}{\centering \small{\textbf{Partie à joindre au règlement}}} \\  	
	
        \CutlnPapillonEntete
        <?php $nb = count($echeances) ; foreach ($echeances as $key => $papillon) : ?>
        &
  \centering \small{<?php echo $societe->raison_sociale; ?>} &
   
    \centering \small{Echéance} &
    \centering \small{Ref. Client / Ref. Facture} &
    \multicolumn{1}{c}{\small{Montant TTC}} \\
                        
                \centering \small{~} & 
                \centering \small{<?php echo $societe->siege->adresse.' '.$societe->siege->code_postal.' '.$societe->siege->commune; ?>} &
      
                \centering \small{\textbf{<?php echo format_date($papillon->echeance_date,'dd/MM/yyyy'); ?>}} &
                \centering \small{\FactureRefClient~/~\FactureNum} &               
                \multicolumn{1}{r}{\small{\textbf{<?php echo echoArialFloat($papillon->montant_ttc); ?>~\texteuro{}}}}  \\

                \CutlnPapillon
        <?php endforeach; ?> 
                
\end{tabular}
\end{minipage}
\end{center}
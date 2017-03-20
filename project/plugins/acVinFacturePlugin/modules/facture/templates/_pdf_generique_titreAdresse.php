<?php
use_helper('Display');
?>
\noindent{
\begin{minipage}[t]{0.5\textwidth}
	\begin{flushleft}

	\textbf{<?php echo ($avoir)? 'AVOIR' : 'FACTURE'; ?>} <?php if($facture->numero_piece_comptable_origine): ?>\small{(Facture n°~<?php echo $facture->numero_piece_comptable_origine ?>)}<?php endif; ?> \\
	\vspace{0.2cm}
	\begin{tikzpicture}
		\node[inner sep=1pt] (tab0){
			\begin{tabular}{*{2}{c|}c}
  				\rowcolor{lightgray} \textbf{NUMERO} & \textbf{DATE} & \textbf{<?php echo FactureConfiguration::getInstance()->getNomRefClient(); ?>} \\
  				\hline
  				\FactureNum & \FactureDate & \FactureRefClient
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab0.north west) (tab0.north east) (tab0.south east) (tab0.south west)] {};
	\end{tikzpicture}
	\\
        <?php if($facture->hasMessageCommunication() && !$avoir): ?>
        \vspace{0.3cm}
			\begin{tikzpicture}
		\node[inner sep=1pt] (tab0){
                        \begin{tabular}{p{92mm}}
  				<?php display_latex_message_communication($facture->getMessageCommunicationWithDefault()); ?>
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=1pt, fit=(tab0.north west) (tab0.north east) (tab0.south east) (tab0.south west)] {};
	\end{tikzpicture}
        <?php else : ?>
        	\vspace{0.5cm}
        <?php endif; ?>
	\end{flushleft}
\end{minipage}
}
\hspace{2cm}
\begin{minipage}[t]{0.5\textwidth}
\vspace{1cm}
		\begin{flushleft}
			\textbf{\FactureClientNom \\}
				\FactureClientAdresse \FactureClientAdresseComplementaire \\
				\FactureClientCP ~\FactureClientVille \\
			\end{flushleft}
		\hspace{6cm}
\end{minipage}

\noindent{
\begin{minipage}[t]{0.5\textwidth}
	\begin{flushleft}
	
	\textbf{<?php echo ($facture->total_ht > 0)? 'FACTURE' : 'AVOIR'; ?>} \\
	\vspace{0.5cm}
	\begin{tikzpicture}
		\node[inner sep=1pt] (tab0){
			\begin{tabular}{*{2}{c|}c}
  				\rowcolor{lightgray} \textbf{NUMERO} & \textbf{DATE} & \textbf{REF CLIENT} \\
  				\hline
  				\FactureNum & \FactureDate & \FactureRefClient
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab0.north west) (tab0.north east) (tab0.south east) (tab0.south west)] {};	
	\end{tikzpicture}
	\\
	\begin{small}
	Cotisation selon Accord interprofessionnel en vigueur
	\end{small}		
	\end{flushleft}
\end{minipage}
}
\hspace{2cm}
\begin{minipage}[t]{0.5\textwidth}
\vspace{1cm}
		\begin{flushleft}		
			\textbf{\FactureClientNom \\}				
				\FactureClientAdresse \\
				\FactureClientCP ~\FactureClientVille \\
			\end{flushleft}
		\hspace{6cm}
\end{minipage}
<?php
use_helper('Display');
use_helper('Date');
?>

\def\FactureReglement{ <?php echo FactureConfiguration::getInstance()->getReglement(); ?> }
\def\TVA{20}
\def\FactureNum{<?php echo $infos->id_relance; ?>}
\def\FactureDate{<?php echo format_date($infos->date_relance,'dd/MM/yyyy'); ?>}
\def\FactureRefClient{<?php echo $infos->identifiant; ?>}
\def\FactureRefCodeComptableClient{<?php echo $infos->code_comptable; ?>}
\newcommand{\CutlnPapillon}{
  	\multicolumn{2}{|c|}{ ~~~~~~~~~~~~~~~~~~~~~~~ } &
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\
}

\newcommand{\CutlnPapillonEntete}{
      &  &  \multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\
}

\lhead{\includegraphics[scale=0.7]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/images")."/logo_". sfConfig::get('sf_app').".png"; ?>}}
\rhead{
 \textbf{\NomInterpro} \\
 \InterproAdresse \\
 \begin{small} \textbf{\begin{footnotesize}\InterproContact\end{footnotesize}}\\ \end{small}
 \begin{tiny}
         RIB~:~\InterproBANQUE~(BIC:~\InterproBIC~IBAN:~\InterproIBAN)
 \end{tiny} \\
 \begin{tiny}
         SIRET~\InterproSIRET ~-~\InterproAPE ~- TVA~Intracommunutaire~\InterproTVAIntracomm
\end{tiny}
 }
\begin{document}
\noindent{
\begin{minipage}[t]{0.5\textwidth}
	\begin{flushleft}

	\textbf{<?php echo ($infos->nb_relance > 1)? 'DERNIÈRE' : 'PREMIÈRE'; ?> RELANCE} \\
	\vspace{0.2cm}
	\begin{tikzpicture}
		\node[inner sep=1pt] (tab0){
			\begin{tabular}{*{2}{c|}c}
  				\rowcolor{lightgray} \textbf{NUMERO} & \textbf{DATE} & \textbf{<?php echo escape_string_for_latex(FactureConfiguration::getInstance()->getNomRefClient()); ?>} \\
  				\hline
  				\FactureNum & \FactureDate & \FactureRefClient
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab0.north west) (tab0.north east) (tab0.south east) (tab0.south west)] {};
	\end{tikzpicture}
	\\
        	\vspace{0.5cm}
	\end{flushleft}
\end{minipage}
}
\hspace{2cm}
\begin{minipage}[t]{0.5\textwidth}
\vspace{1cm}
		\begin{flushleft}
			\textbf{\RessortissantNom \\}
				\RessortissantAdresse \RessortissantAdresseComplementaire \\
				\RessortissantCP ~\RessortissantVille \\
			\end{flushleft}
		\hspace{6cm}
\end{minipage}
<?php
	if (!function_exists('pdf_newpage')) {
		function pdf_newpage() {
			echo "\\newpage\n";
			pdf_newpage_entete();
		}
		function pdf_newpage_entete() {
			echo "\\fontsize{8}{10}\\selectfont\n";
		  echo "\\begin{flushright}\n";
		  echo "page~\\thepage~/~\\pageref{LastPage}\n";
			echo "\\end{flushright}\n\n";
			echo "\\begin{center}\n";
		 	echo "\\large{\\textbf{\\PdfTitre}} \\\\";
			echo "\\end{center}\n\n";
	  }
	}
pdf_newpage_entete();

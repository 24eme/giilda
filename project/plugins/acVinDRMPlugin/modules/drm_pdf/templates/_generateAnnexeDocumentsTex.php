<?php ?>

<?php if ($drm->exist('documents_annexes') && count($drm->documents_annexes)) : ?>
    \begin{center}
    \begin{large}
    \textbf{Documents d'accompagnement}
    \end{large}
    \end{center}
    ~ \\ ~ \\
    \quad{\setlength{\extrarowheight}{1pt}
    \begin{tabular}{C{90mm} |C{90mm}|C{90mm}|}			 	 
    \multicolumn{3}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Documents d'accompagnement}}}}
    \\ 			 
    \hline   			
    \rowcolor{lightgray}		 
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Type de document}}} &
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Numéro début}}} &
    \multicolumn{1}{|C{90mm}|}{\small{\textbf{Numéro fin}}} 
    \\ 			 
    \hline   			
    <?php foreach ($drm->documents_annexes as $typeDoc => $numsDoc): ?>

        \multicolumn{1}{|l}{\small{\textbf{<?php echo DRMClient::$drm_documents_daccompagnement_libelle[$typeDoc]; ?>}}} &
        \multicolumn{1}{|r}{\small{\textbf{<?php echo $numsDoc->debut; ?>}}} &
        \multicolumn{1}{|r|}{\small{\textbf{<?php echo $numsDoc->fin; ?>}}} 
        \\ 			 
        \hline

    <?php endforeach; ?>  
    \end{tabular}

<?php endif; ?>  
    
    
<?php if ($drm->exist('releve_non_apurement') && count($drm->releve_non_apurement)) : ?>
    \vspace{3cm}
    \begin{center}
    \begin{large}
    \textbf{Relevé de non apurement}
    \end{large}
    \end{center}
    ~ \\ ~ \\
    \quad{\setlength{\extrarowheight}{1pt}
    \begin{tabular}{C{90mm} |C{90mm}|C{90mm}|}			 	 
    \multicolumn{3}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Relevé de non apurement}}}}
    \\ 			 
    \hline   			
    \rowcolor{lightgray}		 
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Numéro de  document}}} &
    \multicolumn{1}{|C{90mm}}{\small{\textbf{Date d'emission}}} &
    \multicolumn{1}{|C{90mm}|}{\small{\textbf{Numéro d'accise destinataire}}} 
    \\ 			 
    \hline   			
    <?php foreach ($drm->releve_non_apurement as $num_non_apurement => $non_apurement): ?>

        \multicolumn{1}{|l}{\small{\textbf{<?php echo $non_apurement->numero_document; ?>}}} &
        \multicolumn{1}{|r}{\small{\textbf{<?php echo $non_apurement->date_emission; ?>}}} &
        \multicolumn{1}{|r|}{\small{\textbf{<?php echo $non_apurement->numero_accise; ?>}}} 
        \\ 			 
        \hline

    <?php endforeach; ?>  
    \end{tabular}

<?php endif; ?>  
\newpage
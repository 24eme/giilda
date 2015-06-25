<?php ?>

<?php foreach ($drm->getAllCrdsByRegimeAndByGenre() as $regime_crd => $crdsByGenre) : ?>
\begin{center}
\begin{large}
\textbf{Compte Capsules}
\end{large}
\end{center}
\begin{large}
<?php foreach (EtablissementClient::$regimes_crds_libelles as $crd_regime_key => $libelle): ?>
<?php echo $libelle; ?>~:~<?php echo getCheckBoxe($crd_regime_key == $regime_crd); ?>~~~~~~~~~~~~
<?php endforeach; ?>
\end{large}
~ \\ ~ \\
\quad{\setlength{\extrarowheight}{1pt}
\begin{tabular}{C{40mm} |C{20mm}|C{26mm}|C{26mm}|C{26mm}|C{26mm}|C{26mm}|C{26mm}|C{20mm}|}

\cline{3-8}			 	 
\multicolumn{1}{c}{~} &
\multicolumn{1}{c}{~} &
\multicolumn{3}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Entrées}}}} &
\multicolumn{3}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Sorties}}}} &
\multicolumn{1}{|c}{~}
\\ 			 
\hline   			
\rowcolor{lightgray}		 
\multicolumn{1}{|C{40mm}}{\small{\textbf{CRD}}} &
\multicolumn{1}{|C{20mm}}{\small{\textbf{Stock}}} &

\multicolumn{1}{|C{26mm}}{\small{\textbf{Achats}}} &
\multicolumn{1}{|C{26mm}}{\small{\textbf{Retours}}} &
\multicolumn{1}{|C{26mm}}{\small{\textbf{Excédents}}} &
\multicolumn{1}{|C{26mm}}{\small{\textbf{Utilisés}}} &	 
\multicolumn{1}{|C{26mm}}{\small{\textbf{Destructions}}} &	 
\multicolumn{1}{|C{26mm}}{\small{\textbf{Manquants}}} &
\multicolumn{1}{|C{20mm}|}{\small{\textbf{Stock fin de mois}}}
\\ 			 
\hline   			

<?php foreach ($crdsByGenre as $genre_crd => $crds): ?>
<?php foreach ($crds as $key_crd => $crd): ?>
    \multicolumn{1}{|l}{\small{\textbf{<?php echo $genre_crd.' '.$crd->getLibelle(); ?>}}} &
    \multicolumn{1}{|r}{\small{\textbf{<?php echo $crd->stock_debut; ?>}}} &

    \multicolumn{1}{|r}{\small{\textbf{<?php echo $crd->entrees_achats; ?>}}} &
    \multicolumn{1}{|r}{\small{<?php echo $crd->entrees_retours; ?>}} &
    \multicolumn{1}{|r}{\small{<?php echo $crd->entrees_excedents; ?>}} &
    \multicolumn{1}{|r}{\small{\textbf{<?php echo $crd->sorties_utilisations; ?>}}} &	 
    \multicolumn{1}{|r}{\small{<?php echo $crd->sorties_destructions; ?>}} &	 
    \multicolumn{1}{|r}{\small{<?php echo $crd->sorties_manquants; ?>}} &
    \multicolumn{1}{|r|}{\small{\textbf{<?php echo $crd->stock_fin; ?>}}}

    \\ 			 
    \hline
    
<?php endforeach; ?> 
<?php endforeach; ?>    
  \end{tabular}
  \newpage
  
<?php endforeach; ?> 
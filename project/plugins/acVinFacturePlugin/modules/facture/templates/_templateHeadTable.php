<?php 
$prix_u_libelle = "Taux";
$titre_type_facture = "Cotisation interprofessionnelle";
if($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS)){
    $qt_libelle = "Quantité";
    $prix_u_libelle = "Prix U.";    
    $titre_type_facture = "";
} ?>

\begin{center}
 \large{\textbf{<?php echo $titre_type_facture; ?>}} \\
\end{center}

\centering
\fontsize{8}{10}\selectfont
    \begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
                        \renewcommand{\arraystretch}{1.2}
			\begin{tabular}{p{120mm} |p{20mm}|p{12mm}|p{24mm}p{0mm}}
  			\rowcolor{lightgray}
                        \centering \small{\textbf{Libellé}} &
   			\centering \small{\textbf{Quantité \tiny{en hl}}} &
                        \centering \small{\textbf{<?php echo $prix_u_libelle; ?>}} &
   			\centering \small{\textbf{Montant HT \tiny{en \texteuro{}}}} &   
   			 \\
  			\hline
                      ~ & ~ & ~ & ~ &\\
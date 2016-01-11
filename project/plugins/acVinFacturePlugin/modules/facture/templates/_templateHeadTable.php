<?php 
$qt_libelle = "Quantité en hl";
$prix_u_libelle = "Taux";
$titre_type_facture = "Facture de cotisation interprofessionnelle";
if($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS)){
    $qt_libelle = "Quantité";
    $prix_u_libelle = "Prix U.";    
    $titre_type_facture = "Facture libre";
} ?>

\begin{center}
 \large{\textbf{<?php echo $titre_type_facture; ?>}} \\
\end{center}

\centering
\fontsize{8}{10}\selectfont
    \begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
                        \renewcommand{\arraystretch}{1.2}
			\begin{tabular}{p{15mm}| p{115mm} |p{12mm}|p{12mm}|p{18mm}p{0mm}}
  			\rowcolor{lightgray}
   			\centering \small{\textbf{Date}} &
                        \centering \small{\textbf{Libellé}} &
   			\centering \small{\textbf{<?php echo $qt_libelle; ?>}} &
                        \centering \small{\textbf{<?php echo $prix_u_libelle; ?>}} &
   			\centering \small{\textbf{Montant HT en \texteuro{}}} &   
   			 \\
  			\hline
                        ~ & ~ & ~ & ~ & ~ &\\
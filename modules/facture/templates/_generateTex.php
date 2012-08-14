\documentclass[a4paper,10pt]{article}
\usepackage[english]{babel}
\usepackage[utf8]{inputenc}
\usepackage{units}
\usepackage{geometry}
\usepackage{graphicx}
\usepackage{fancyhdr}
\usepackage{fp}
\usepackage[table]{xcolor}
\usepackage{tikz}
\usepackage{array}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{marvosym}
\usepackage{lastpage}



\usetikzlibrary{fit}
\newcommand{\CutlnPapillon}{
  	\multicolumn{7}{c}{ \Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline }
\\   	  
}

\renewcommand{\familydefault}{\sfdefault}


\normalfont
\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{19cm}
\setlength{\headheight}{5cm}

\setlength{\topmargin}{-3.5cm}

\def\TVA{19.60} 
\def\InterloireAdresse{Chateau de la Frémoire \\
					  44120 VERTOU - France} 
\def\InterloireFacturation{Service facturation : Nelly ALBERT Tél. : 02.47.60.55.12} 
\def\InterloireSIRET{429164072020093}
\def\InterloireAPE{APE 9499 Z} 
\def\InterloireTVAIntracomm{FR73429164072}



\def\FactureNum{<?php echo $facture->identifiant; ?>}
\def\FactureDate{<?php echo $facture->date_emission; ?>}
\def\FactureRefClient{<?php echo $facture->client_reference; ?>}

\def\FactureClientDomaine{<?php echo "NomDomaine?"; ?>}
\def\FactureClientNom{<?php echo $facture->client->raison_sociale; ?>}
\def\FactureClientAdresse{<?php echo $facture->client->adresse; ?>}
\def\FactureClientCP{<?php echo $facture->client->code_postal; ?>}
\def\FactureClientVille{<?php echo $facture->client->ville; ?>}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}

\lhead{
 \textbf{InterLoire} \\
 \InterloireAdresse \\
 \InterloireFacturation \\
 \begin{tiny}
 SIRET~\InterloireSIRET ~-~\InterloireAPE ~- TVA~Intracommunutaire~\InterloireTVAIntracomm
 \end{tiny}
}
\rhead{\includegraphics[scale=0.6]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo.jpg"; ?>}}



\begin{document}
\noindent{


\begin{minipage}[t]{0.5\textwidth}
	\begin{flushleft}
	
	\textbf{FACTURE} \\
	\vspace{0.3cm}
	\begin{tikzpicture}
		\node[inner sep=1pt] (tab0){%
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
		\begin{flushleft}		
			\textbf{\FactureClientDomaine \\}
				\FactureClientNom \\
				\FactureClientAdresse \\
				\FactureClientCP ~\FactureClientVille \\
			\end{flushleft}
		\hspace{6cm}
		page \thepage / \pageref{LastPage} 
\end{minipage}

\centering
	\begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
			\begin{tabular}{p{85mm} |p{11mm}|p{19mm}|p{16mm}|p{22mm}|p{5mm}}

  			\rowcolor{lightgray}
                        \centering \small{\textbf{ \\ LIBELLE}} &
   			\centering \small{\textbf{Mois}} &
   			\centering \small{\textbf{VOLUMES en Hl}} &
                        \centering \small{\textbf{Cotisation hl}} &
   			\centering \small{\textbf{Montant H.T Euros}} &
   			\multicolumn{1}{c}{\rowcolor{lightgray} \small{\textbf{Code Echéance}}} \\
  
  			\hline
                
                <?php $propriete = $facture->getLignesPropriete(); if(count($propriete) > 0 ) : ?>
                \textbf{Sortie de propriété} & ~ & ~ & ~ & ~ & ~ \\
                <?php endif; ?>
                <?php foreach ($propriete as $ligneProp): ?>
                                
                            ~~~<?php echo $ligneProp->produit_libelle ?> & 
                            \multicolumn{1}{r|}{<?php echo $ligneProp->origine_date; ?>} & 
                            \multicolumn{1}{r|}{\small{<?php echo $ligneProp->volume; ?>}} &
                            \multicolumn{1}{r|}{\small{<?php echo $ligneProp->cotisation_taux ?>}} & 
                            \multicolumn{1}{r|}{\small{<?php echo $ligneProp->montant_ht ?>}\texteuro{}} & 
                            \multicolumn{1}{c}{<?php echo $ligneProp->echeance_code ?>} \\

                <?php endforeach; ?>
                <?php $contrat = $facture->getLignesContrat(); if(count($contrat) > 0 ) : ?>
                \textbf{Sortie de contrat} & ~ & ~ & ~ & ~ & ~ \\
                <?php endif; ?>              
                <?php foreach ($contrat as $ligneCont): ?>  
                            ~~~<?php echo $ligneCont->produit_libelle ?> & 
                            \multicolumn{1}{r|}{\small{<?php echo $ligneCont->origine_date; ?>}} & 
                            \multicolumn{1}{r|}{\small{<?php echo $ligneCont->volume; ?>}} &
                            \multicolumn{1}{r|}{\small{<?php echo $ligneCont->cotisation_taux ?>}} & 
                            \multicolumn{1}{r|}{\small{<?php echo $ligneCont->montant_ht ?>}\texteuro{}} & 
                            \multicolumn{1}{c}{<?php echo $ligneCont->echeance_code ?>} \\
                                
                <?php endforeach; ?>
	~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\        
        ~ & ~ & ~ & ~ & ~ & ~ \\

        ~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\

        ~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\

        ~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\
        ~ & ~ & ~ & ~ & ~ & ~ \\
        
        ~ & ~ & ~ & ~ & ~ & ~ \\
	 \multicolumn{6}{c}{\small{Aucun escompte n\'est prévu pour paiment anticipé. Pénalités de retard : 3 fois le taux d\'intér\^{e}t légal}} \\
	 ~ & ~ & ~ & ~ & ~ & ~ \\
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab1.north west) (tab1.north east) (tab1.south east) (tab1.south west)] {};	
	\end{tikzpicture}
	
   \begin{flushleft}
   \underline{\textbf{Règlement par virement ou par chèque établi à l\'ordre de : InterLoire}}
   \end{flushleft}
\hspace{113mm}
\begin{minipage}[t]{0.3\textwidth}
   \begin{flushright}
		\begin{tikzpicture}
		\node[inner sep=1pt] (tab2){
			\begin{tabular}{>{\columncolor{lightgray}} l | p{22mm}}

   			\centering \small{\textbf{Montant H.T.}} &
   			\multicolumn{1}{r}{\small{<?php echo $facture->total_ht; ?>\texteuro{}}} \\
  			
   			\centering \small{\textbf{TVA 19.6}} &
   			\multicolumn{1}{r}{\small{<?php echo ($facture->total_ttc - $facture->total_ht); ?>\texteuro{}}} \\
   			\hline
   			\centering \small{\textbf{Montant TTC}} &
   			\multicolumn{1}{r}{\small{<?php echo $facture->total_ttc; ?>\texteuro{}}}   \\
   			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab2.north west) (tab2.north east) (tab2.south east) (tab2.south west)] {};	
	\end{tikzpicture}
  \end{flushright}
\end{minipage}	
	
\vspace{1cm}

\begin{center}


\begin{minipage}[b]{1\textwidth}

\begin{tabular}{p{10mm} p{30mm} p{30mm} p{30mm} p{35mm} p{20mm} p{15mm}}

	\hline
	\multicolumn{7}{>{\columncolor[rgb]{0.8,0.8,0.8}}c}{\centering \small{\textbf{Papillon(s) à joindre au règlement}}}  \\
   	\CutlnPapillon
	
        <?php $nb = count($facture->echeances) ; foreach ($facture->echeances as $key => $papillon) : ?>
                & \centering \small{Code échéance} & \centering \small{\textbf{Date échéance}} & \centering \small{Réf. Client} & \centering \small{N$^\circ$ Facture} & & \\
                
                \centering \small{<?php echo $nb - $key; ?>} & 
                \centering \small{<?php echo $papillon->echeance_code ?>} &
                \centering \small{\textbf{<?php echo $papillon->echeance_date; ?>}} &
                \centering \small{\FactureRefClient} &
                \centering \small{\FactureNum} &
                \centering \small{\textbf{Net à payer :}}
                & \multicolumn{1}{r}{\small{\textbf{<?php echo $papillon->montant_ttc; ?>\texteuro{}}}}  \\
                \CutlnPapillon
        <?php endforeach; ?> 
                
\end{tabular}
\end{minipage}
\end{center}
\end{document}
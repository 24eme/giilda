<?php
use_helper('Float');
use_helper('Date');
$nb_ligne = 0;
?>
\documentclass[6pt]{article}
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

\renewcommand\sfdefault{phv}

\newcommand{\CutlnPapillon}{
  	\multicolumn{7}{c}{ \Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline }
\\   	  
}

\renewcommand{\familydefault}{\sfdefault}


\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{19cm}
\setlength{\headheight}{5cm}

\setlength{\topmargin}{-3.5cm}

\def\TVA{19.60} 
\def\InterloireAdresse{<?php echo $facture->emetteur->adresse; ?> \\
		       <?php echo $facture->emetteur->code_postal.' '.$facture->emetteur->ville; ?> - France} 
\def\InterloireFacturation{Service facturation : <?php echo $facture->emetteur->service_facturation; ?> Tél. : <?php echo $facture->emetteur->telephone; ?>} 
\def\InterloireSIRET{429 164 072 00077}
\def\InterloireAPE{APE 9499 Z} 
\def\InterloireTVAIntracomm{FR 73 429164072}
\def\InterloireBANQUE{Crédit agricole de la tourraine et du poitou}
\def\InterloireBIC{XXXXX}
\def\InterloireIBAN{XXXX XXXXX XXXX XXXXX XX}

\def\FactureNum{<?php echo $facture->identifiant; ?>}
\def\FactureDate{<?php echo $facture->date_emission; ?>}
\def\FactureRefClient{<?php echo $facture->client_reference; ?>}

\def\FactureClientNom{<?php echo ($facture->client->raison_sociale == '')? 'Raison Sociale' : $facture->client->raison_sociale; ?>}
\def\FactureClientAdresse{<?php echo ($facture->client->adresse == '')? 'Adresse' : $facture->client->adresse; ?>}
\def\FactureClientCP{<?php echo $facture->client->code_postal; ?>}
\def\FactureClientVille{<?php echo $facture->client->ville; ?>}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}

\fancyhf{}

\lhead{
 \textbf{InterLoire} \\
 \InterloireAdresse \\
 \InterloireFacturation \\
 \begin{tiny}
         RIB~:~\InterloireBANQUE~(BIC:~\InterloireBIC~IBAN:~\InterloireIBAN) 
 \end{tiny} \\
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
		\begin{flushleft}		
			\textbf{\FactureClientNom \\}				
				\FactureClientAdresse \\
				\FactureClientCP ~\FactureClientVille \\
			\end{flushleft}
		\hspace{6cm}
		page \thepage / <?php echo $facture->nb_page; ?>
\end{minipage}

\centering
\fontsize{8}{8}\selectfont
    \begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
			\begin{tabular}{p{120mm} |p{12mm}|p{14mm}|p{18mm}|p{15mm}p{0mm}}

  			\rowcolor{lightgray}
                        \centering \small{\textbf{Libellé}} &
   			\centering \small{\textbf{Volume en hl}} &
                        \centering \small{\textbf{Cotisation}} &
   			\centering \small{\textbf{Montant H.T Euros}} &
   			\centering \small{\textbf{Code \\ Echéance}} & 
                        \multicolumn{1}{c}{\small{}}\\
  
  			\hline
                <?php 
                $nb_ligne += count($facture->lignes);
                foreach ($facture->lignes as $type => $typeLignes) :
                ?>
                \textbf{Sortie de <?php echo FactureClient::getInstance()->getTypeLignePdfLibelle($type); ?>} & ~ & ~ & ~ & ~ &\\
            <?php 
                 $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);
                 $nb_ligne += count($produits);
                 
                 foreach ($produits as $prodHash => $p) :   
                     foreach ($p as $produit):
                            $produit = $produit->getRawValue();
                            $libelle = ($produit->contrat_libelle)? $produit->contrat_libelle : $produit->origine_libelle;
                            $libelle = ($produit->origine_type == FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_SV)?
                                $produit->origine_libelle.' '.$produit->contrat_libelle : $libelle;
                        ?>      
                ~~~~<?php echo $produit->produit_libelle.' \begin{tiny}'.$libelle.'\end{tiny}'; ?> &
                            \multicolumn{1}{r|}{<?php echoFloat($produit->volume); ?>} &
                            \multicolumn{1}{r|}{<?php echoFloat($produit->cotisation_taux); ?>} & 
                            \multicolumn{1}{r|}{<?php echoFloat($produit->montant_ht); ?>\texteuro{}} & 
                            \multicolumn{1}{c}{<?php echo $produit->echeance_code ?>} &\\

                <?php 
                    endforeach;
                    endforeach;
                endforeach;
                
               
                for($i=0; $i<($total_rows - $nb_ligne);$i++):
                ?>
        ~ & ~ & ~ & ~ & ~ & \\
                <?php 
                endfor;
                ?>
	 \multicolumn{6}{c}{Aucun escompte n\'est prévu pour paiment anticipé. Pénalités de retard : 3 fois le taux d\'intér\^{e}t légal} \\
	 ~ & ~ & ~ & ~ & ~ & \\
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab1.north west) (tab1.north east) (tab1.south east) (tab1.south west)] {};	
	\end{tikzpicture}
        
\noindent{
\begin{minipage}[b]{1\textwidth}
\noindent{
       \begin{flushleft}
       
       \begin{minipage}[b]{0.65\textwidth}
        \small{\textbf{Règlement : }}
        \begin{itemize}
            \item \small{\textbf{par virement (merci de mentionner les n° suivants : CCCCCC FF FFFFFF)}}
            \item \small{\textbf{par chèque en joignement le(s) papillon(s) ci-dessous : \\}}
        \end{itemize}
        \end{minipage}
        \end{flushleft}
}
\hspace{-1.35cm}
\vspace{-3cm}
    \begin{flushright}
    \begin{minipage}[b]{0.285\textwidth}
            \begin{tikzpicture}
            \node[inner sep=1pt] (tab2){
                    \begin{tabular}{>{\columncolor{lightgray}} l | p{22mm}}

                    \centering \small{\textbf{Montant H.T.}} &
                    \multicolumn{1}{r}{\small{<?php echoFloat($facture->total_ht); ?>\texteuro{}}} \\

                    \centering \small{\textbf{TVA 19.6}} &
                    \multicolumn{1}{r}{\small{<?php echoFloat($facture->total_ttc - $facture->total_ht); ?>\texteuro{}}} \\
                    \hline
                    \centering \small{\textbf{Montant TTC}} &
                    \multicolumn{1}{r}{\small{<?php echoFloat($facture->total_ttc); ?>\texteuro{}}}   \\
                    \end{tabular}
            };
            \node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab2.north west) (tab2.north east) (tab2.south east) (tab2.south west)] {};	
            \end{tikzpicture} 
 \end{minipage}
 \end{flushright}
\end{minipage}
}

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
                \centering \small{\textbf{<?php echo format_date($papillon->echeance_date,'dd/MM/yyyy'); ?>}} &
                \centering \small{\FactureRefClient} &
                \centering \small{\FactureNum} &
                \centering \small{\textbf{Net à payer :}}
                & \multicolumn{1}{r}{\small{\textbf{<?php echo echoFloat($papillon->montant_ttc); ?>\texteuro{}}}}  \\
                \CutlnPapillon
        <?php endforeach; ?> 
                
\end{tabular}
\end{minipage}
\end{center}
\end{document}
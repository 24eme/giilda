<?php
use_helper('Float');
use_helper('Date');
$nb_ligne = 0;

?>
\documentclass[a4paper,8pt]{article}
\usepackage{geometry} % paper=a4paper
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
  	\multicolumn{4}{|c|}{ ~~~~~~~~~~~~~~~~~~~~~~~ } & 
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\    
}

\newcommand{\CutlnPapillonEntete}{	
      & \centering \small{\textbf{Code échéance}} &
    \centering \small{\textbf{Date d'échéance}} &
    \multicolumn{1}{r|}{\small{\textbf{Montant TTC}}}  
     & 
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\    
}

\renewcommand{\familydefault}{\sfdefault}


\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{19cm}
\setlength{\headheight}{5cm}
\setlength{\topmargin}{-4.5cm}
\addtolength{\textheight}{29.9cm} 

\def\TVA{19.60} 
\def\InterloireAdresse{<?php echo $facture->emetteur->adresse; ?> \\
		       <?php echo $facture->emetteur->code_postal.' '.$facture->emetteur->ville; ?> - France} 
\def\InterloireFacturation{\\Votre contact : <?php echo $facture->emetteur->service_facturation.' - '.$facture->emetteur->telephone; ?>  \\ Email : <?php echo $facture->emetteur->email; ?>} 
\def\InterloireSIRET{429 164 072 00077}
\def\InterloireAPE{APE 9499 Z} 
\def\InterloireTVAIntracomm{FR 73 429164072}
\def\InterloireBANQUE{Crédit Agricole Atlantique Vendée}
\def\InterloireBIC{AGRIFRPP847}
\def\InterloireIBAN{FR76~1470~6000~1400~0000~2200~028}

\def\FactureNum{<?php echo $facture->numero_facture; ?>}
\def\FactureNumREF{<?php echo substr($facture->numero_facture,6,2).' '.substr($facture->numero_facture,0,6); ?>}
\def\FactureDate{<?php echo format_date($facture->date_emission,'dd/MM/yyyy'); ?>}
\def\FactureRefClient{<?php echo $facture->identifiant; ?>}

\def\FactureClientNom{<?php echo ($facture->declarant->raison_sociale == '')? $facture->declarant->nom : $facture->declarant->raison_sociale; ?>}
\def\FactureClientAdresse{<?php echo ($facture->declarant->adresse == '')? 'Adresse' : $facture->declarant->adresse; ?>}
\def\FactureClientCP{<?php echo $facture->declarant->code_postal; ?>}
\def\FactureClientVille{<?php echo $facture->declarant->commune; ?>}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}

\fancyhf{}

\lhead{
 \textbf{InterLoire} \\  
 \InterloireAdresse \\
 \textbf{\begin{footnotesize}\InterloireFacturation\end{footnotesize}}\\
 \begin{tiny}
         RIB~:~\InterloireBANQUE~(BIC:~\InterloireBIC~IBAN:~\InterloireIBAN) 
 \end{tiny} \\
 \begin{tiny}
         SIRET~\InterloireSIRET ~-~\InterloireAPE ~- TVA~Intracommunutaire~\InterloireTVAIntracomm
\end{tiny}
 }
\rhead{\includegraphics[scale=1]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo.jpg"; ?>}}



\begin{document}

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


\begin{flushright}
page \thepage / <?php echo $nb_page; ?>
\end{flushright}

\centering
\fontsize{8}{10}\selectfont
    \begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
			\begin{tabular}{p{116mm} |p{12mm}|p{14mm}|p{18mm}|p{13mm}p{0mm}}

  			\rowcolor{lightgray}
                        \centering \small{\textbf{Libellé}} &
   			\centering \small{\textbf{Volume en hl}} &
                        \centering \small{\textbf{Cotisation en \texteuro{}/hl}} &
   			\centering \small{\textbf{Montant HT en \texteuro{}}} &   			
   			\centering \small{\textbf{Code Echéance}} &
   			 \\
  			\hline
                        ~ & ~ & ~ & ~ & ~ &\\
                <?php 
                $nb_ligne += count($facture->lignes);
                foreach ($facture->lignes as $type => $typeLignes) :
                ?>
                \textbf{Sortie de <?php echo FactureClient::getInstance()->getTypeLignePdfLibelle($type); ?>} & ~ & ~ & ~ & ~ & \\
            <?php 
                 $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);
                 $nb_ligne += count($produits);
                 
                 foreach ($produits as $prodHash => $p) :   
                     foreach ($p as $produit):
                            $produit = $produit->getRawValue();
                        ?>      
                ~~~~<?php echo $produit->produit_libelle.' \textbf{\begin{tiny}'.$produit->origine_libelle.'\end{tiny}}'; ?> &
                            \multicolumn{1}{r|}{<?php echoArialFloat($produit->volume*-1); ?>} &
                            \multicolumn{1}{r|}{<?php echoArialFloat($produit->cotisation_taux); ?>} & 
                            \multicolumn{1}{r|}{<?php echoArialFloat($produit->montant_ht); ?>} & 
                            \multicolumn{2}{c}{<?php echo $produit->echeance_code; ?>}\\

                <?php 
                    endforeach;
                    endforeach;
                endforeach;
                
               
                for($i=0; $i<($max_rows - $nb_ligne);$i++):
                ?>
        ~ & ~ & ~ & ~ & ~ &\\
                <?php 
                endfor;
                ?>
	 \multicolumn{6}{c}{Aucun escompte n'est prévu pour paiement anticipé. Pénalités de retard : 3 fois le taux d'intér\^{e}t légal} \\
	 ~ & ~ & ~ & ~ & ~ &\\
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab1.north west) (tab1.north east) (tab1.south east) (tab1.south west)] {};	
	\end{tikzpicture}
        
\noindent{
\begin{minipage}[b]{1\textwidth}
\noindent{
       \begin{flushleft}
       
       \begin{minipage}[b]{0.60\textwidth}
        \small{\textbf{Règlement : }}
        \begin{itemize}
            \item \textbf{par virement} (merci de mentionner les n° suivants : \FactureRefClient~\FactureNumREF)
            \item \textbf{par chèque en joignant le(s) papillon(s) ci-dessous : \\}
        \end{itemize}
        \end{minipage}
        \end{flushleft}
}
\hspace{-1.35cm}
\vspace{-2.7cm}
    \begin{flushright}
    \begin{minipage}[b]{0.31\textwidth}
            \begin{tikzpicture}
            \node[inner sep=1pt] (tab2){
                    \begin{tabular}{>{\columncolor{lightgray}} l | p{22mm}}

                    \centering \small{\textbf{Montant HT}} &
                    \multicolumn{1}{r}{\small{<?php echoArialFloat($facture->total_ht); ?>~\texteuro{}}} \\
                    
                    \centering \small{} &
                    \multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\
                    
                    \centering \small{\textbf{TVA 19.6~\%}} &
                    \multicolumn{1}{r}{\small{<?php echoArialFloat($facture->taxe); ?>~\texteuro{}}} \\
                    
                    \centering \small{} &
                    \multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\
                    \hline
                    \centering \small{} &
                    \multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\
                    
                    \centering \small{\textbf{Montant TTC}} &
                    \multicolumn{1}{r}{\small{<?php echoArialFloat($facture->total_ttc); ?>~\texteuro{}}}   \\
                    \end{tabular}
            };
            \node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab2.north west) (tab2.north east) (tab2.south east) (tab2.south west)] {};	
            \end{tikzpicture} 
 \end{minipage}
 \end{flushright}
\end{minipage}
}

\begin{center}
Echéances (hors régularisation) : A = à 60 jours fin de mois B = au 31/03 et au 30/06, C = au 30/09

\begin{minipage}[b]{1\textwidth}

\begin{tabular}{|p{9mm} p{25mm} p{25mm} p{20mm} | p{36mm} p{36mm} p{36mm}}
            \hline
	\multicolumn{4}{|>{\columncolor[rgb]{0.8,0.8,0.8}}c|}{\centering \small{\textbf{Partie à conserver}}} &
	\multicolumn{3}{>{\columncolor[rgb]{0.8,0.8,0.8}}c}{\centering \small{\textbf{Partie à joindre au règlement}}} \\  	
	
        \CutlnPapillonEntete
        <?php $nb = count($facture->echeances) ; foreach ($facture->echeances as $key => $papillon) : ?>
        &
    &
    &
    &
    \centering \small{Echéance} &
    \centering \small{Ref. Client / Ref. Facture} &
    \multicolumn{1}{c}{\small{Montant TTC}} \\
                        
                \centering \small{<?php echo $nb - $key; ?>} & 
                \centering \small{<?php echo $papillon->echeance_code ?>} &
                \centering \small{\textbf{<?php echo format_date($papillon->echeance_date,'dd/MM/yyyy'); ?>}} &
                \multicolumn{1}{r|}{\centering \small{\textbf{<?php echo echoArialFloat($papillon->montant_ttc); ?>~\texteuro{}}}} &
                \centering \small{\textbf{<?php echo format_date($papillon->echeance_date,'dd/MM/yyyy'); ?>}} &
                \centering \small{\FactureRefClient/\FactureNum} &               
                \multicolumn{1}{r}{\small{\textbf{<?php echo echoArialFloat($papillon->montant_ttc); ?>~\texteuro{}}}}  \\

                \CutlnPapillon
        <?php endforeach; ?> 
                
\end{tabular}
\end{minipage}
\end{center}
\end{document}

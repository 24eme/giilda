<?php

class drm_pdfActions extends sfActions
{
    
    public function executeGeneratePdfFacture(sfWebRequest $request) {
        
        $this->init();
        $this->drm = $this->getRoute()->getDRM();
        
        
        $this->srcPdf = '\documentclass[a4paper,10pt]{article}
\usepackage[english]{babel}
\usepackage[utf8]{inputenc}
\usepackage{units}
\usepackage{geometry}
\usepackage{graphicx}
\usepackage{fancyhdr}
\usepackage{fp}
\usepackage{lastpage}
\usepackage[table]{xcolor}
\usepackage{tikz}
\usepackage{array}
\usepackage{multicol}
\usepackage{textcomp}

\usetikzlibrary{fit}

\renewcommand{\familydefault}{\sfdefault}


\normalfont
\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{19cm}
\setlength{\headheight}{-1cm}
\setlength{\topmargin}{-2.5cm}
\setlength{\headheight}{5cm}

% DEFINITION DES CONSTANTES
\def\TVA{19.60}	% Taux de la TVA
\def\InterloireAdresse{Chateau de la Frémoire \\
					  44120 VERTOU - France} % Adresse InterLoire
\def\InterloireFacturation{Service facturation : Nelly ALBERT Tél. : 02.47.60.55.12} % Facturation InterLoire
\def\InterloireSIRET{429164072020093} %SIRET InterLoire
\def\InterloireAPE{APE 9499 Z} %APE InterLoire
\def\InterloireTVAIntracomm{FR73429164072} %TVAIntracomm InterLoire



% DEFINITION DES VARIABLES
%facture
\def\FactureNum{12/2-01280}
\def\FactureDate{31/05/2012}
\def\FactureRefClient{510350}

%client
\def\FactureClientDomaine{EARL Domaine de la Grange}
\def\FactureClientNom{HARDY Dominique}
\def\FactureClientAdresse{La Grange}
\def\FactureClientCP{44330}
\def\FactureClientVille{MOUZILLON}

%head
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
\rhead{\includegraphics[scale=0.6]{logo.jpg}}


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
		\node[inner sep=1pt] (tab1){%
			\begin{tabular}{p{75mm} p{10mm}|p{11mm}|p{19mm}|p{16mm}|p{22mm}|p{15mm}}

  			\rowcolor{lightgray}
   			\centering \small{\textbf{LIBELLE}} &
   			\small{\textbf{Stock}} &
   			\centering \small{\textbf{Mois}} &
   			\centering \small{\textbf{VOLUMES en Hl}} &
 		    \centering \small{\textbf{Cotisation hl}} &
   			\centering \small{\textbf{Montant H.T Euros}} &
   			\small{\textbf{Code Echéance}} \\
  
  			\hline
  \textbf{Sortie de propriété n$^\circ$: 21853} & ~ & \centering \small{05/2012} & ~ & ~ & ~ & ~ \\ 
  ~~~GROS PLANT SUR LIE & \small{5.43} & ~ & \small{0.81} & \small{4.00} & \small{3.24} & A \\
  ~~~MUSCADET A C. & \small{265.92} & ~ & \small{22.24} & \small{4.50} & \small{100.08} & B \\
  ~~~MUSCADET SEVRE et MAINE & \small{58.68} & ~ & \small{0.32} & \small{4.50} & \small{1.44} & A \\
  ~~~MUSCADET SEVRE et MAINE / LIE & \small{1100.56} & ~ & \small{104.63} & \small{4.50} & \small{470.84} & C \\  
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\ 
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\ 
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\	
	~ & ~ & ~ & ~ & ~ & ~ & ~ \\
	 \multicolumn{7}{c}{\small{Aucun escompte n\'est prévu pour paiment anticipé. Pénalités de retard : 3 fois le taux d\'intér\^{e}t légal}} \\
	 ~ & ~ & ~ & ~ & ~ & ~ & ~ \\
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
		\node[inner sep=1pt] (tab2){%
			\begin{tabular}{>{\columncolor{lightgray}} l | p{22mm}}

   			\centering \small{\textbf{Montant H.T.}} &
   			\small{575.60} \\
  			
   			\centering \small{\textbf{TVA 19.6}} &
   			\small{112.82} \\
   			\hline
   			\centering \small{\textbf{Montant TTC}} &
   			\small{688.42}   \\
   			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab2.north west) (tab2.north east) (tab2.south east) (tab2.south west)] {};	
	\end{tikzpicture}
  \end{flushright}
\end{minipage}	
	
\vspace{1cm}

\begin{center}

\begin{minipage}[b]{1\textwidth}

\begin{tabular}{|p{10mm} p{30mm} p{30mm} p{30mm} p{35mm} p{20mm} p{15mm}|}

	\hline
	\multicolumn{7}{|>{\columncolor[rgb]{0.8,0.8,0.8}}c|}{\centering \small{\textbf{Papillon à joindre au règlement}}} \\
   	\hline   	
  	\small{1} & \small{Code échéance} & \small{\textbf{Date échéance}} & \small{Réf. Client} & \small{N$^\circ$ Facture} & & \\
  	 & \small{A} & \small{\textbf{15/05/2012}} & \small{\FactureRefClient} & \small{\FactureNum} & \small{\textbf{Net à payer :}} & \multicolumn{1}{r|}{\small{\textbf{3.88 \texteuro{}}}} \\
  	\hline
  	\small{2} & \small{Code échéance} & \small{\textbf{Date échéance}} & \small{Réf. Client} & \small{N$^\circ$ Facture} & & \\
  	 & \small{B} & \small{\textbf{31/05/2012}} & \small{\FactureRefClient} & \small{\FactureNum} & \small{\textbf{Net à payer :}} & \multicolumn{1}{r|}{\small{\textbf{119.70 \texteuro{}}}} \\
  	\hline
	\small{3} & \small{Code échéance} & \small{\textbf{Date échéance}} & \small{Réf. Client} & \small{N$^\circ$ Facture} & & \\
  	 & \small{A} & \small{\textbf{31/05/2012}} & \small{\FactureRefClient} & \small{\FactureNum} & \small{\textbf{Net à payer :}} & \multicolumn{1}{r|}{\small{\textbf{1.72 \texteuro{}}}} \\
  	\hline
  	 \small{4} & \small{Code échéance} & \small{\textbf{Date échéance}} & \small{Réf. Client} & \small{N$^\circ$ Facture} & & \\
  	& \small{A} & \small{\textbf{30/04/2012}} & \small{\FactureRefClient} & \small{\FactureNum} & \small{\textbf{Net à payer :}} & \multicolumn{1}{r|}{\small{\textbf{239.20 \texteuro{}}}} \\
  	\hline
  	\small{5}  & \small{Code échéance} & \small{\textbf{Date échéance}} & \small{Réf. Client} & \small{N$^\circ$ Facture} & & \\
  	& \small{A} & \small{\textbf{31/05/2012}} & \small{\FactureRefClient} & \small{\FactureNum} & \small{\textbf{Net à payer :}} & \multicolumn{1}{r|}{\small{\textbf{323.92 \texteuro{}}}} \\
  	\hline
\end{tabular}
\end{minipage}
\end{center}
\end{document}
';
        
}

    protected function init() {
        $this->form = null;
        $this->detail = null;
        $this->drm = $this->getRoute()->getDRM();
        $this->config = $this->drm->declaration->getConfig();
        $this->produits = $this->drm->declaration->getProduits();
        /*$this->previous = $this->drm_lieu->getPreviousSisterWithMouvementCheck();
        $this->next = $this->drm_lieu->getNextSisterWithMouvementCheck();
    	$this->previous_certif = $this->drm_lieu->getCertification()->getPreviousSisterWithMouvementCheck();
    	$this->next_certif = $this->drm_lieu->getCertification()->getNextSisterWithMouvementCheck();

    	$this->redirectIfNoMouvementCheck();*/
    }

}
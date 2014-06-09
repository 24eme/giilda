<?php use_helper('Vracpdf') ?>

\begin{document}

\begin{tabularx}{\textwidth}{|X|X|X|}
	\hline
	& ~ & ~ \\
	 \textbf{\INTERLOIRECOORDONNEESTITRE} & \multirow{7}{*}{ 
\includegraphics[scale=0.8]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo_vrac_pdf.jpg"; ?>} 
} & Numéro d'enregistement \\ 	
	 \INTERLOIRECOORDONNEESADRESSE & ~ & ~ \\ 
	 \INTERLOIRECOORDONNEESCPVILLE  & ~ & \multicolumn{1}{c|}{{\textbf{\CONTRATNUMENREGISTREMENT}}} \\
	 \textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONENANTES} & ~ & Visa : \\
	 \textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONEANJOU} & ~ & \multicolumn{1}{c|}{\textbf{\CONTRATVISA}} \\
	 \textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONETOURS} & ~ & Date : \\
	 ~~~\small{\INTERLOIRECOORDONNEESFAX} & ~ &  \multicolumn{1}{c|}{ \textbf{\CONTRATDATEENTETE}} \\
	 ~~~\small{\INTERLOIRECOORDONNEESEMAIL} & ~ & ~ \\
	\hline	
	
\end{tabularx}
\vspace{0.4cm}
  \begin{center}    
   	\begin{huge}
   		\CONTRAT_TITRE
	\end{huge}    
    \end{center}
    
     Entre les soussignés,     
\begin{multicols}{2}

\begin{minipage}[t]{0.485\textwidth}
\begin{tabularx}{\textwidth}{|Xr|}
	\hline 
         ~ & ~ \\
	 M. & \CONTRATVENDEURNOM \\
	 C.V.I. & \CONTRATVENDEURCVI \\ 
	 N° ACCISE & \CONTRATVENDEURACCISE \\
	  N° TVA Intracomm & \CONTRATVENDEURNUMTVA \\
	 Propriétaire à & \CONTRATVENDEURLIEU \\	
	 ~ & ~ \\
	 ~ & Ci après dénommé le vendeur, \\
	 
	\hline	
\end{tabularx} 
\end{minipage}

\begin{minipage}[t]{0.485\textwidth}
\begin{tabularx}{\textwidth}{|Xr|}
	\hline 
         ~ & ~ \\
	 Société ou M. & \CONTRATACHETEUREURNOM \\
         C.V.I. & \CONTRATACHETEURCVI \\ 
	 N° ACCISE & \CONTRATACHETEURACCISE \\
	 N° TVA Intracomm & \CONTRATACHETEURNUMTVA \\
	 Etablissement situé à & \CONTRATACHETEURLIEU \\
	 Département n° & \CONTRATACHETEURDEPT \\
	 ~ & Ci après dénommé l'acheteur,\\
	 \hline
\end{tabularx}
\end{minipage}
\end{multicols}  

Par l'entremise de \CONTRATCOURTIERNOM ~~~ Courtier en vins n° carte professionnelle: \CONTRATCOURTIERCARTEPRO

Mandaté pour signature par : ~~~ le vendeur ~  $\square$ ~~~ l'acheteur ~ \squareChecked
\\

A été conclu le marché suivant: \\

\begin{tabularx}{\textwidth}{|X|X|X|X|}
\hline
\textbf{Appellation / couleur / type} & \textbf{Millésime} & \textbf{Volume en \CONTRATTYPEUNITE} & \textbf{Prix} \\
\hline
~ & ~ & \cellcolor{gray!25}~ & ~ \\
\textbf{\CONTRATPRODUITLIBELLE} & \textbf{\CONTRATPRODUITMILLESIME} & \cellcolor{gray!25}~ & \textbf{\CONTRATTYPE} \\
~ & ~ & \cellcolor{gray!25}~ & ~ \\
\hline

\multicolumn{2}{|c|}{ ~ } & \multirow{6}{*}{ \centering \textbf{\CONTRATPRODUITQUANTITE} } & \multirow{6}{*}{ \centering \textbf{\CONTRATPRIX	} } \\ 

\multicolumn{2}{|c|}{ qénérique ~  $\square$ ~~~ ou domaine ~ \squareChecked } & ~ & ~ \\

\multicolumn{2}{|c|}{Nom de domaine utilisable par l'acheteur:} & ~ & ~ \\ 
\cline{1-2} 
\multicolumn{2}{|c|}{ \cellcolor{gray!25}~ } & ~ & ~ \\ 
\multicolumn{2}{|c|}{ \cellcolor{gray!25}~ } & ~ & ~ \\ 
\multicolumn{2}{|c|}{ \cellcolor{gray!25}~ } & ~ & ~ \\ 
\hline
             
            \end{tabularx}
            \\
\begin{flushright}
\textbf{Prix en toute lettres : } \framebox[1.05\width]{\textbf{<?php echo getPrixTouteLettre($vrac); ?>}} \\
\end{flushright}

\textbf{\underline{\CONTRATTYPEEXPLICATIONPRIX}}
\\

\textbf{L'achat rentre dans le cadre d'un contrat pluriannuel:}~~~~\textbf{OUI}~ $\square$ ~~~\textbf{NON}~ \squareChecked \textbf{, conforme à l'art. III-2 de l'accord Interprofessionnel}
\\

\textbf{Hors contrat pluriannuel, le prix est obligatoirement déterminé donc connu au jour de la transaction. Dans le cadre d'un contrat pluriannuel avec une partie de prix variable, le prix indiqué est la partie fixe du prix s'appliquant à 50\% minimum de la quantité.}
\\

La cotisation interprofessionnel est payée par moitié par le vendeur et l'acheteur. Toutefois la cotisation interprofessionnelle concernant la vente de vins à destination d'un acheteur hors du ressort d'InterLoire (1) est payée en totalité par le vendeur.
\\

\textbf{\underline{Délais de paiement} : conformes aux dispositions de l'Accord Interprofessionnel rappelées au verso.} \\
\small{Tout incident se produisant au paiement de l'une des échéances prévues rend immédiatement exigible la totalité des somme restant dues. De plus, tout règlement effectué après la date d'échéance entraîne le paiement d’intérêts de retard. Le montant de ce intérêts est égal à trois fois le taux d’intérêt général. Ces intérêts sont dus sans mis en demeure préalable et les frais de recouvrement sont à la charge de l’acheteur.}
\\
\begin{multicols}{2}

\begin{flushleft}
\textbf{\normalsize{\underline{Conditions d'enlèvement :}}} 
\end{flushleft}

\begin{flushright}
\framebox[1.05\width]{\textbf{\normalsize{\CONTRATDATEMAXENLEVEMENT} }}
\end{flushright}

\end{multicols}

\small{A défaut d'indication, l'enlèvement est effectué par l'acheteur dans les 30 jours à compter de la date de signature du présent contrat. Passé cette date, si l'enlèvement n'a pas été effectué, le vendeur peut, à sa convenance, résoudre le contrat par simple lettre recommandée ou facturer à l'acheteur les frais de garde qui sont fixés à}
\framebox[1.05\width]{\textbf{\CONTRATFRAISDEGARDE} }~ \small{par mois. L'émission de la facture ne peut en aucun cas être postérieur à la date stipulée pour l'enlèvement.}
\\

\underline{Sanction :} \small{Tout manquement grave au contrat (de type modification unilatérale de prix, résolution fautive du contrat) entraîne, de plein droit et après mise en demeure, le paiement, à titre de dommages et intérêts, de 15\% du prix stipulé au contrat.}
\\

\fbox{
\parbox{17.7cm}{
\underline{Clause de réserve de propriété :} \\ \small{Le transfert de propriété de la marchandise est subordonné au complet paiement du prix à l'échéance convenue. Toutefois, les risques sont transférés dès l'enlèvement. En cas de défaut de paiement à l'échéance, le vendeur reprend possession de la marchandise dont il reste propriétaire sans aucune formalité préalable et peut à son gré résoudre le contrat par simple lettre recommandée avec accusé de réception. L’acheteur ne peut en aucun cas donner les marchandises non encore intégralement payées, en gage, ni en transférer la propriété à titre de garantie.}
}
}
\\

\normalsize{\textbf{Les soussignés ont pris connaissance que toute fausse déclaration entraînera les sanctions prévues par l'article L.632-7 du Code rural et de la pêche maritime.}}
\\
\\
\\

\textbf{Fait à : \CONTRATLIEUCREATION ~~~~~ Le : \CONTRATDATECREATION}
\\

\begin{tabularx}{\textwidth}{|X|X|X|}
\hline
\cellcolor{gray!25}\textbf{Le courtier,} & \cellcolor{gray!25}\textbf{Le vendeur,} & \cellcolor{gray!25}\textbf{L'Acheteur} \\
\hline
~ & ~ & ~ \\
Le XX/XX/XX, & Le XX/XX/XX, & Le XX/XX/XX, \\
~ & ~ & ~ \\
\textbf{Signé électroniquement} & \textbf{Signé électroniquement} & \textbf{Signé électroniquement} \\
\hline
\end{tabularx}
 \begin{center}
\begin{tiny}
(1) Les régions du ressort d'InterLoire sont constituées par les zones de production des vins d'appellation d'origine dont la liste est annexée à l'Accord interprofessionnel Vin, moût ou raisins, loyaux et marchands, correspondant aux normes éditées par la réglementation en vigueur.
\end{tiny}
\end{center}
\end{document}
<?php use_helper('Vracpdf') ?>

\begin{document}

\begin{tabularx}{\textwidth}{|X|X|p{35mm}p{15mm}|}
	\hline
	~ & ~ & ~ & ~ \\
	 \textbf{\INTERLOIRECOORDONNEESTITRE} & \multirow{7}{*}{ 
\includegraphics[scale=0.8]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo_vrac_pdf.jpg"; ?>} 
} & \multicolumn{2}{c|}{Numéro d'enregistement} \\
	 \INTERLOIRECOORDONNEESADRESSE & ~ & ~ & ~ \\ 
	 \INTERLOIRECOORDONNEESCPVILLE  & ~  & \multicolumn{2}{c|}{\textbf{\CONTRATNUMENREGISTREMENT}} \\
	 \textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONENANTES} & ~ & ~ & ~ \\
	 \textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONEANJOU} & ~ & ~ & ~  \\
	 \textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONETOURS} & ~ & ~ & ~ \\
	 ~~~\small{\INTERLOIRECOORDONNEESFAX} & ~ &  \multicolumn{2}{c|}{Le \textbf{\CONTRATDATEENTETE ~ à 12h30}} \\
	 ~~~\small{\INTERLOIRECOORDONNEESEMAIL} & ~ & ~ & ~ \\
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
	 \multicolumn{2}{|c|}{\textbf{\CONTRATVENDEURNOM}} \\
         ~ & ~ \\
	 C.V.I. & \textbf{\CONTRATVENDEURCVI} \\ 
	 SIRET & \textbf{\CONTRATVENDEURSIRET} \\
	 Adresse & \textbf{\CONTRATVENDEURADRESSE} \\
     Commune & \textbf{\CONTRATVENDEURCOMMUNE} \\
	 ~ & ~ \\
	 \multicolumn{2}{|r|}{Ci après dénommé le vendeur,} \\
	 
	\hline	
\end{tabularx} 
\end{minipage}

\begin{minipage}[t]{0.485\textwidth}
\begin{tabularx}{\textwidth}{|Xr|}
	\hline 
         ~ & ~ \\
	 \multicolumn{2}{|c|}{\textbf{\CONTRATACHETEUREURNOM}} \\
         ~ & ~ \\
         C.V.I. & \textbf{\CONTRATACHETEURCVI} \\ 
	     SIRET & \textbf{\CONTRATACHETEURSIRET} \\
	     Adresse & \textbf{\CONTRATACHETEURADRESSE} \\
         Commune & \textbf{\CONTRATACHETEURCOMMUNE} \\
         ~ & ~ \\
	 \multicolumn{2}{|r|}{Ci après dénommé l'acheteur,}\\
	 \hline
\end{tabularx}
\end{minipage}
\end{multicols}  

<?php if($vrac->exist('mandataire_exist') && $vrac->mandataire_exist): ?> 
Par l'entremise de \CONTRATCOURTIERNOM, Courtier en vins n° carte professionnelle: \CONTRATCOURTIERCARTEPRO \\
<?php endif; ?>

A été conclu le marché suivant: \\

\begin{tabularx}{\textwidth}{|X|p{12mm}|p{24mm}|p{24mm}|p{24mm}|}
\hline
~ & ~ & ~ & ~ & ~ \\
\textbf{Appellation / couleur / type} & \multicolumn{1}{c|}{\textbf{Millésime}} & \multicolumn{1}{c|}{\textbf{Type de Contrat}} & \multicolumn{1}{c|}{\textbf{Volume en \CONTRATTYPEUNITE}} & \multicolumn{1}{c|}{\textbf{Prix en \euro ~ par \CONTRATTYPEUNITE}} \\
~ & ~ & ~ & ~ & ~ \\
\hline
~ & ~ & ~ & ~ & ~ 
\\

\textbf{\CONTRATPRODUITLIBELLE} & \multicolumn{1}{c|}{\textbf{2014}} & \multicolumn{1}{c|}{\textbf{\CONTRATTYPE}} & \multicolumn{1}{r|}{ \textbf{\CONTRATPRODUITQUANTITE~\CONTRATTYPEUNITE}} & \multicolumn{1}{r|}{\textbf{\CONTRATPRIXUNITAIRE~\euro/\CONTRATTYPEUNITE}} \\
\multicolumn{1}{|l|}{\textit{\CONTRATGENERIQUEDOMAINE}} & ~ & ~ & ~ & ~ \\
~ & ~ & ~ & ~ & ~
\\
\hline
\end{tabularx}
\\
\\
\\
\textbf{\normalsize{\underline{Prix :}}} \CONTRATTYPEEXPLICATIONPRIX
\\

\textbf{L'achat rentre dans le cadre d'un contrat pluriannuel:}~~~~\textbf{OUI}~ <?php echo getCheckBoxe($vrac->isPluriannuel())?> ~~~\textbf{NON}~ <?php echo getCheckBoxe(!$vrac->isPluriannuel())?> \textbf{, conforme à l'art. III-2 de l'accord Interprofessionnel}
\\

La cotisation interprofessionnel est payée par moitié par le vendeur et l'acheteur. Toutefois la cotisation interprofessionnelle concernant la vente de vins à destination d'un acheteur hors du ressort d'InterLoire (1) est payée en totalité par le vendeur.
\\

\textbf{\underline{Délais de paiement :} conformes aux dispositions de l'Accord Interprofessionnel rappelées au verso.} \\
\\
Tout incident se produisant au paiement de l'une des échéances prévues rend immédiatement exigible la totalité des somme restant dues. De plus, tout règlement effectué après la date d'échéance entraîne le paiement d’intérêts de retard. Le montant de ce intérêts est égal à trois fois le taux d’intérêt général. Ces intérêts sont dus sans mis en demeure préalable et les frais de recouvrement sont à la charge de l’acheteur.

\begin{multicols}{2}
\begin{flushleft}
\textbf{\normalsize{\underline{Conditions d'enlèvement :}}} 
\end{flushleft}
\begin{flushright}
\framebox[1.05\width]{\textbf{\normalsize{\CONTRATDATEMAXENLEVEMENT}}}
\end{flushright}
\end{multicols}

A défaut d'indication, l'enlèvement est effectué par l'acheteur dans les 30 jours à compter de la date de signature du présent contrat. Passé cette date, si l'enlèvement n'a pas été effectué, le vendeur peut, à sa convenance, résoudre le contrat par simple lettre recommandée ou facturer à l'acheteur les frais de garde qui sont fixés à
\framebox[1.05\width]{\textbf{\CONTRATFRAISDEGARDE} }~ par mois. L'émission de la facture ne peut en aucun cas être postérieur à la date stipulée pour l'enlèvement.
\\

\textbf{\underline{Sanction :}} Tout manquement grave au contrat (de type modification unilatérale de prix, résolution fautive du contrat) entraîne, de plein droit et après mise en demeure, le paiement, à titre de dommages et intérêts, de 15\% du prix stipulé au contrat.
\\

\fbox{
\parbox{17.7cm}{
\underline{\small{Clause de réserve de propriété :}} \\\\ \small{Le transfert de propriété de la marchandise est subordonné au complet paiement du prix à l'échéance convenue. Toutefois, les risques sont transférés dès l'enlèvement. En cas de défaut de paiement à l'échéance, le vendeur reprend possession de la marchandise dont il reste propriétaire sans aucune formalité préalable et peut à son gré résoudre le contrat par simple lettre recommandée avec accusé de réception. L’acheteur ne peut en aucun cas donner les marchandises non encore intégralement payées, en gage, ni en transférer la propriété à titre de garantie.}
}
}
\\
\\

\normalsize{\textbf{Les soussignés ont pris connaissance que toute fausse déclaration entraînera les sanctions prévues par l'article L.632-7 du Code rural et de la pêche maritime.}}
\\

\textbf{Fait à : \CONTRATLIEUCREATION ~~~~~ Le : \CONTRATDATECREATION~ à 12h30}
\\
<?php if($vrac->exist('mandataire_exist') && $vrac->mandataire_exist): ?> 
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
<?php else: ?>
\\
\begin{tabularx}{\textwidth}{|X|X|}
\hline
\cellcolor{gray!25}\textbf{Le vendeur,} & \cellcolor{gray!25}\textbf{L'Acheteur} \\
\hline
~ & ~ \\
Le XX/XX/XX, & Le XX/XX/XX, \\
~ & ~ \\
\textbf{Signé électroniquement} & \textbf{Signé électroniquement} \\
\hline
\end{tabularx}
<?php endif; ?>
 \begin{center}
\begin{tiny}
(1) Les régions du ressort d'InterLoire sont constituées par les zones de production des vins d'appellation d'origine dont la liste est annexée à l'Accord interprofessionnel Vin, moût ou raisins, loyaux et marchands, correspondant aux normes éditées par la réglementation en vigueur.\\
Vin, moûts ou raisins, loyaux et marchands, correspondant aux normes édictées par la réglementation en vigueur.
\end{tiny}
\end{center}
\end{document}
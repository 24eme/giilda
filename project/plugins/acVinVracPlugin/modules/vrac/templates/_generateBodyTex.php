<?php use_helper('Vracpdf');
$isIGP = $vrac->isProduitIGP(); ?>
\begin{tabularx}{\textwidth}{|X|X|p{35mm}p{15mm}|}
	\hline
	~ & ~ & ~ & ~ \\
\textbf{\INTERLOIRECOORDONNEESTITRE}& \multirow{7}{*}{
\includegraphics[scale=0.8]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo_vrac_pdf.jpg"; ?>}} & \multicolumn{2}{c|}{Numéro d'enregistrement} \\
\INTERLOIRECOORDONNEESADRESSE  & ~ & ~ & ~ \\
\INTERLOIRECOORDONNEESCPVILLE   & ~  & \multicolumn{2}{c|}{\textbf{\LARGE{\CONTRATNUMENREGISTREMENT}}} \\
\textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONENANTES} & ~ & ~ & ~ \\
\textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONEANJOU}  & ~ & ~ & ~  \\
\textbullet ~ \small{\INTERLOIRECOORDONNEESTELEPHONETOURS}  & ~ & ~ & ~ \\
\small{\INTERLOIRECOORDONNEESFAX}  & ~ &  \multicolumn{2}{c|}{Le \textbf{\CONTRATDATEENTETE}} \\
\small{\INTERLOIRECOORDONNEESEMAIL}  & ~ & ~ & ~ \\
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
         N° d'ACCISE & \textbf{\CONTRATVENDEURACCISES} \\
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
             N° d'ACCISE & \textbf{\CONTRATACHETEURACCISES} \\
	     Adresse & \textbf{\CONTRATACHETEURADRESSE} \\
         Commune & \textbf{\CONTRATACHETEURCOMMUNE} \\
         ~ & ~ \\
	 \multicolumn{2}{|r|}{Ci après dénommé l'acheteur,}\\
	 \hline
\end{tabularx}
\end{minipage}
\end{multicols}
Les entreprises sont liées au sens de l’art. III-1 de l’accord interprofessionnel :~~~~OUI~ <?php echo getCheckBoxe($vrac->interne)?> ~~~NON~ <?php echo getCheckBoxe(!$vrac->interne)?>
~\\
<?php if($vrac->exist('mandataire_exist') && $vrac->mandataire_exist): ?>
~\\
Par l'entremise de \CONTRATCOURTIERNOM, Courtier en vins\CONTRATCOURTIERCARTEPRO \\
<?php else: ?>
~\\
~\\
<?php endif; ?>

A été conclu le marché suivant: \\

\begin{tabularx}{\textwidth}{|X|p{12mm}|p{24mm}|p{24mm}|p{24mm}|}
\hline
~ & ~ & ~ & ~ & ~ \\
\textbf{<?php if($isIGP): ?>IGP / couleur / cépage<?php else : ?>Appellation / couleur / type<?php endif; ?>} & \multicolumn{1}{c|}{\textbf{<?php echo $vrac->getMillesimeLabel(); ?>}} & \multicolumn{1}{c|}{\textbf{Type de Transaction}} & \multicolumn{1}{c|}{\textbf{Quantité Proposée}} & \multicolumn{1}{c|}{\textbf{Prix}} \\
~ & ~ & ~ & ~ & ~ \\
\hline
~ & ~ & ~ & ~ & ~
\\

\large{\CONTRATPRODUITLIBELLE} & \multicolumn{1}{c|}{\large{\CONTRATPRODUITMILLESIME}} & \multicolumn{1}{c|}{\large{\CONTRATTYPE}} & \multicolumn{1}{c|}{ \large{\CONTRATPRODUITQUANTITE~\normalsize{\CONTRATTYPEUNITE}}} & \multicolumn{1}{c|}{\large{\CONTRATPRIXUNITAIRE~\normalsize{\euro/\CONTRATTYPEUNITE}}} \\
~ & ~ & ~ & ~ \multicolumn{1}{c|}{\small{\textit{<?php echo getContenancePdf($vrac); ?>}}} & ~ \\
<?php if($vrac->isBio()): ?>
\cline{1-1}
~ & ~ & ~ & ~ & ~ \\
\multicolumn{1}{|l|}{\CONTRATBIO} & ~ & ~ & ~ & ~ \\
~ & ~ & ~ & ~ & ~ \\
<?php endif; ?>
<?php if($vrac->isDomaine()): ?>
	\cline{1-1}
	~ & ~ & ~ & ~ & ~ \\
	\multicolumn{1}{|l|}{\textbf{Avec nom de domaine} utilisable par l’acheteur : } & ~ & ~ & ~ & ~ \\
	\multicolumn{1}{|l|}{\CONTRATGENERIQUEDOMAINE} & ~ & ~ & ~ & ~ \\
	~ & ~ & ~ & ~ & ~ \\
<?php endif; ?>
\hline
\end{tabularx}
<?php if(!$vrac->isDomaine() && !$vrac->isBio()): ?>
\\
\\
<?php endif; ?>
\\
\textbf{\normalsize{\underline{Prix} :}} \CONTRATTYPEEXPLICATIONPRIX
\\
<?php if(!$vrac->isDomaine() && !$vrac->isBio()): ?>
\\
<?php endif; ?>
\textbf{L'achat rentre dans le cadre d'un contrat pluriannuel:}~~~~\textbf{OUI}~ <?php echo getCheckBoxe($vrac->isPluriannuel())?> ~~~\textbf{NON}~ <?php echo getCheckBoxe(!$vrac->isPluriannuel())?> \textbf{, <?php if($isIGP): ?>conforme à l'Accord Interprofessionnel du C.I.V.D.L.<?php else : ?>conforme à l'art. III-2 de l'Accord Interprofessionnel<?php endif; ?>}
\\
\\
<?php if($isIGP): ?>
\\
\textbf{\normalsize{\underline{Délais de paiement} :} conformes aux dispositions de l'Accord Interprofessionnel rappelées au verso.} \\
\\
\\
\\
\\
\\
\\
\\
\\
<?php else : ?>
La cotisation interprofessionnelle est payée en totalité par l'acheteur. Toutefois, la cotisation interprofessionnelle concernant une vente à destination d'un acheteur hors du ressort d'InterLoire (1) est payée en totalité par le vendeur ; et la cotisation interprofessionnelle concernant la vente de vins en vrac, bouteilles ou BIB®, hors CRD, est payée par moitié par l’acheteur et par moitié par le vendeur, pour les enlèvements survenant entre le 1er août 2015 et le 31 décembre 2015.

\\

\textbf{\normalsize{\underline{Délais de paiement} :} conformes aux dispositions de l'Accord Interprofessionnel rappelées au verso.} \\
\\
Tout incident se produisant au paiement de l'une des échéances prévues rend immédiatement exigible la totalité des sommes restant dues. De plus, tout règlement effectué après la date d'échéance entraîne le paiement d'intérêts de retard. Le montant de ces intérêts est  égal à trois fois le taux d'intérêt général. Ces intérêts de retard sont dus sans mise en demeure préalable et les frais de recouvrement sont à la charge de l'acheteur.
<?php endif; ?>
\begin{multicols}{2}
\begin{flushleft}
\normalsize{\underline{Conditions d'enlèvement} :}
\end{flushleft}
\begin{flushright}
\framebox[1.05\width]{\normalsize{Au plus tard le~\textbf{\CONTRATDATEMAXENLEVEMENT}}}
\end{flushright}
\end{multicols}
A défaut d'indication, l'enlèvement est effectué par l'acheteur dans les 30 jours à compter de la date de signature du présent contrat. Passé cette date, si l'enlèvement n'a pas été effectué, le vendeur peut, à sa convenance, résoudre le contrat par simple lettre recommandée ou facturer à l'acheteur les frais de garde qui sont fixés à \framebox[1.05\width]{\CONTRATFRAISDEGARDE} par mois. L'émission de la facture ne peut  en aucun cas être postérieure à la date stipulée pour l'enlèvement.
\\
<?php if($isIGP): ?>
\\
\\
<?php else : ?>
\underline{Sanction} : Tout manquement grave au contrat (de type modification unilatérale de prix, résolution fautive du contrat) entraine, de plein droit et après mise en demeure, le paiement, à titre de dommages et intérêts, de 15\% du prix stipulé au contrat.
<?php endif; ?>
\\

\fbox{
\parbox{17.7cm}{
\underline{Clause de réserve de propriété} :\\
Le transfert de propriété de la marchandise est subordonné au complet paiement du prix à l'échéance convenue. Toutefois, les risques sont  transférés dès l'enlèvement. En cas de défaut de paiement à l'échéance, le vendeur reprend possession de la marchandise dont il est resté propriétaire sans aucune formalité préalable et peut à son gré résoudre le contrat par simple lettre recommandée avec accusé de réception. L'acheteur ne peut en aucun cas donner les marchandises non encore intégralement payées, en gage, ni en transférer la propriété à titre de garantie.}

}
\\
\\

\normalsize{\textbf{Les soussignés ont pris connaissance que toute fausse déclaration entraînera les sanctions prévues par l’article L.632-7 du Code rural et de la pêche maritime.}}
\\
<?php if($vrac->exist('mandataire_exist') && $vrac->mandataire_exist): ?>
\begin{tabularx}{\textwidth}{|X|X|X|}
\hline
\cellcolor{gray!25}\textbf{Le courtier,} & \cellcolor{gray!25}\textbf{Le vendeur,} & \cellcolor{gray!25}\textbf{L'acheteur} \\
\hline
~ & ~ & ~ \\
Le \CONTRATMANDATAIREVISA, & Le \CONTRATDATESIGNATUREVENDEUR, & Le \CONTRATDATESIGNATUREACHETEUR, \\
~ & ~ & ~ \\
\textbf{Signé électroniquement} & \textbf{Signé électroniquement} & \textbf{Signé électroniquement} \\
\hline
\end{tabularx}
<?php else: ?>
\\
\begin{tabularx}{\textwidth}{|X|X|}
\hline
\cellcolor{gray!25}\textbf{Le vendeur,} & \cellcolor{gray!25}\textbf{L'acheteur} \\
\hline
~ & ~ \\
Le \CONTRATDATESIGNATUREVENDEUR, & Le \CONTRATDATESIGNATUREACHETEUR, \\
~ & ~ \\
\textbf{Signé électroniquement} & \textbf{Signé électroniquement} \\
\hline
\end{tabularx}
<?php endif; ?>
 \begin{center}
\begin{tiny}
<?php if($isIGP): ?>
\\
\\
<?php else: ?>
Un extrait de l'accord interprofessionel est disponible sur le portail https://teledeclaration.vinsvaldeloire.pro \\
(1) Les régions du ressort d’InterLoire sont constituées par les zones de production des vins d’appellation d’origine dont la liste est annexée à l’Accord Interprofessionnel.\\
<?php endif; ?>
Vin, moûts ou raisins, loyaux et marchands, correspondant aux normes édictées par la réglementation en vigueur.
\end{tiny}
\end{center}

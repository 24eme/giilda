\documentclass[a4paper,8pt]{extarticle}
\usepackage{geometry} % paper=a4paper
\usepackage[french]{babel}
\usepackage[utf8x]{inputenc}
\usepackage{geometry}
\usepackage{graphicx}
\usepackage[table]{xcolor}
\usepackage{multicol}
\usepackage{tabularx}
\usepackage{amssymb}
\usepackage{tikz}
\usepackage{textcomp}

\usepackage[explicit]{titlesec}
\usepackage{lipsum}

\newcommand*\circled[1]{\tikz[baseline=(char.base)]{
            \node[shape=circle,draw,inner sep=2pt] (char) {#1};}}

\titleformat{\section}
  {\normalfont\bfseries}{\circled\thesection}{1em}{#1}

\renewcommand{\familydefault}{\sfdefault}
\newcommand{\euro}{\EUR\xspace}

\newcommand{\squareChecked}{\makebox[0pt][l]{$\square$}\raisebox{.15ex}{\hspace{0.1em}$\checkmark$}}

\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{20cm}
\setlength{\textheight}{27.9cm}
\setlength{\topmargin}{-3.5cm}
\setlength{\parindent}{0pt}

\def\tabularxcolumn#1{m{#1}}

\def\IVBDCOORDONNEESTITRE{Interprofession des Vins de Bergerac et Duras}
\def\IVBDCOORDONNEESADRESSE{1, rue des Récollets - BP 426 - 24104 BERGERAC Cedex - Tél. 01 01 01 01 01 - Fax: 02 02 02 02 02}


\def\CONTRATNUMENREGISTREMENT{<?php echo substr($vrac->numero_contrat, -6)?>}
\def\CONTRATVISA{Pas de visa}
\def\CONTRATDATEENTETE{}

\def\CONTRAT_TITRE{CONTRAT D'ACHAT EN PROPRIETE}


\def\CONTRATVENDEURNOM{<?php echo $vrac->vendeur->raison_sociale ?><?php if ($vrac->responsable == 'vendeur'): ?> (responsable)<?php endif; ?>}
\def\CONTRATVENDEURCVI{<?php echo $vrac->vendeur->cvi ?>}
\def\CONTRATVENDEURADRESSE{<?php echo $vrac->vendeur->adresse.' '.$vrac->vendeur->code_postal.' '.$vrac->vendeur->commune ?>}
\def\CONTRATVENDEURTELEPHONE{<?php echo $vrac->getVendeurObject()->telephone ?>}
\def\CONTRATVENDEURPAYEUR{??}

\def\CONTRATACHETEURNOM{<?php echo $vrac->acheteur->raison_sociale ?><?php if ($vrac->responsable == 'acheteur'): ?> (responsable)<?php endif; ?>}
\def\CONTRATACHETEURCVI{<?php echo $vrac->acheteur->cvi ?>}
\def\CONTRATACHETEURADRESSE{<?php echo $vrac->acheteur->adresse.' '.$vrac->acheteur->code_postal.' '.$vrac->acheteur->commune ?>}
\def\CONTRATACHETEURTELEPHONE{<?php echo $vrac->getAcheteurObject()->telephone ?>}

\def\CONTRATCOURTIERNOM{<?php echo $vrac->mandataire->raison_sociale ?><?php if ($vrac->responsable == 'mandataire'): ?> (responsable)<?php endif; ?>}
\def\CONTRATCOURTIERCARTEPRO{<?php echo $vrac->mandataire->carte_pro ?>}
\def\CONTRATCOURTIERADRESSE{<?php echo $vrac->mandataire->adresse.' '.$vrac->mandataire->code_postal.' '.$vrac->mandataire->commune ?>}
\def\CONTRATCOURTIERTELEPHONE{<?php echo $vrac->getMandataireObject()->telephone ?>}


\def\CONTRATVOLUMEENTOUTELETTRE{huit mille sept cents vingt trois}
\def\CONTRATVOLUME{<?php echo ($vrac->jus_quantite)? $vrac->jus_quantite : $vrac->raisin_quantite ?>}
\def\CONTRATAPPELLATIONPRODUIT{<?php echo $vrac->produit_libelle ?>}
\def\CONTRATCOULEURPRODUIT{??}
\def\CONTRATMILLESIMEPRODUIT{<?php echo $vrac->millesime ?>}
\def\CONTRATLIEUPRODUIT{<?php echo ($vrac->logement)? $vrac->logement : $vrac->vendeur->commune ?>}
\def\CONTRATNOMPRODUIT{??}

\def\CONTRATBORDEREUPOURCENTAGEANNEEUN{??}
\def\CONTRATSEUILDECLENCHEMENT{??}
\def\CONTRATNUMEROENREGISTREMENTANNEEUN{<?php echo substr($vrac->numero_contrat, -6)?>}

\def\CONTRATPRIXTOUTELETTRE{cinq mille deux cents trente}
\def\CONTRATPRIX{<?php echo $vrac->prix_initial_unitaire ?>}
\def\CONTRATMOYENPAIEMENT{<?php echo $vrac->moyen_paiement ?>}
\def\CONTRATDELAIPAIEMENT{<?php echo $vrac->delai_paiement ?>}

\def\CONTRATPOURCENTAGECOURTAGE{15}
\def\CONTRATPOURCENTAGEACHETEURCOURTAGE{70}
\def\CONTRATPOURCENTAGEVENDEURCOURTAGE{30}

\begin{document}
\begin{minipage}[t]{0.6\textwidth}
\begin{center}
\begin{large}
\IVBDCOORDONNEESTITRE\\
\end{large}
~ \\
	\small{\IVBDCOORDONNEESADRESSE} \\ 
	~  \\
	\begin{large}
       \textbf{BORDEREAU DE CONFIRMATION D'ACHAT EN VRAC}\\
    \end{large}
    \textbf{- AVEC RETIRAISON EN VRAC -}\\
    ~  \\
    n° IV - 15 - \begin{large}\textbf{\CONTRATNUMENREGISTREMENT} \end{large} \\ ~ \\ La liasse complète doit être adressée à l'IVBD pour enregistrement
    \\ dans un délai maximal de 10 jours après signature du présent bordereau
\end{center}	
\end{minipage}
\hspace{2cm}
  \begin{minipage}[t]{0.3\textwidth}
  \vspace{-0.5cm}
\begin{tabularx}{\textwidth}{|X|}
\hline
~ \\
	 \textbf{CACHET DE L'IVBD} \\ ~ \\ ~ \\ ~ \\ ~ \\ ~ \\ ~ \\ ~ \\ ~ \\ N° \begin{Large}
	  \CONTRATNUMENREGISTREMENT 
\end{Large}	 \\ ~ \\ 
\hline
\end{tabularx}
\end{minipage}

%PARTIE 1%
\circled{1}~~\textbf{Désignation des parties:} \\
\normalsize
\begin{minipage}[t]{0.6\textwidth}
\hspace*{0.5cm}
\textbf{A) VENDEUR} : \CONTRATVENDEURNOM \\
\hspace*{0.5cm}
Adresse : \CONTRATVENDEURADRESSE \\
\hspace*{0.5cm}
Pour le compte de : \CONTRATVENDEURPAYEUR \\ ~ \\
\hspace*{0.5cm}
\textbf{B) ACHETEUR} : \CONTRATACHETEURNOM \\
\hspace*{0.5cm}
Adresse : \CONTRATACHETEURADRESSE \\ ~ \\
\hspace*{0.5cm}
\textbf{C) COURTIER} : \CONTRATCOURTIERNOM \\
\hspace*{0.5cm}
Adresse : \CONTRATCOURTIERADRESSE 
\end{minipage}
\hspace{2cm}
\begin{minipage}[t]{0.3\textwidth}
N° CVI : \CONTRATVENDEURCVI \\
Tél. : \CONTRATVENDEURTELEPHONE \\ ~ \\ ~ \\
N° CVI : \CONTRATACHETEURCVI \\
Tél. : \CONTRATACHETEURTELEPHONE \\ ~ \\
N° CIP : \CONTRATCOURTIERCARTEPRO \\
Tél. : \CONTRATCOURTIERTELEPHONE 
\end{minipage}
 ~ \\ ~ \\
%PARTIE 2%
\circled{2}~~\textbf{Désignation des produits :} \\
\normalsize
\hspace*{0.5cm}
\textbf{Volume} : \textbf{\CONTRATVOLUME} hl \\
\hspace*{0.5cm}
\textbf{Produit} : \CONTRATAPPELLATIONPRODUIT ~~ de la récolte : \CONTRATMILLESIMEPRODUIT  \\
\hspace*{0.5cm}
Ce vins droit de goût, loyal et marchand est garanti conforme aux prescriptions légales et à l'échantillon fourni pour la conclusion de cette transaction. \\
\hspace*{0.5cm}
Ce vin est logé dans la commune de : \CONTRATLIEUPRODUIT
 ~ \\   ~ \\ 
%PARTIE 3%
\circled{3}~~\textbf{Nom de l'exploitation:}
\normalsize Ce vin porte le nom de : \textbf{\CONTRATNOMPRODUIT} \\
\hspace*{0.5cm}
dont le vendeur certifie l'existence, conformément aux règlentations communautaire (OCM viticole) et nationale, et dont il autorise l'utilisation dans le cadre\\
\hspace*{0.5cm}
du présent contrat. \\
\hspace*{0.5cm}
Pour toute utilisation du nom de l'exploitation (Château, Domaine...), l'étiquette devra obligatoirement mentionner le nom et l'adresse du négociant, ainsi\\
\hspace*{0.5cm}
que le nom viticulteur.
 ~ \\   ~ \\
%PARTIE 4%
\circled{4}~~\textbf{Nom du producteur:} \normalsize Pour le cas où aucun nom d'exploitation n'est précisé, le vendeur autorise l'utilisation par l'acheteur, dans le cadre du présent\\
\hspace*{0.5cm}
contrat, de son nom patronymique ou de sa raison sociale, ainsi que de son adresse pour la présentation du vin.<?php if ($vrac->autorisation_nom_producteur): ?>~Oui~\squareChecked~Non~$\square$<?php else : ?>~Oui~$\square$~Non~\squareChecked<?php endif; ?>
 ~ \\   ~ \\  
%PARTIE 5%
\circled{5}~~\textbf{Bordereau s'inscrivant dans le cadre d'un contrat d'achat pluriannuel:}<?php if ($vrac->pluriannuel): ?>~Oui~\squareChecked~Non~$\square$<?php else : ?>~Oui~$\square$~Non~\squareChecked<?php endif; ?> $\rightarrow$ Préciser l'année d'application : Année 1 $\square$ Année 2 $\square$ Année 3 $\square$ \\
\hspace*{0.5cm}
Le volume et le prix indiqués sur ce bordereau concernent l'année d'application cochée, sous réserve du respect des règles précisées au verso. \\\hspace*{0.5cm}
Année 1, préciser :\small ~- si une révision est envisagée pour les années suivante :~Non~\squareChecked Oui $\square$ $\rightarrow$ Préciser le seuil de déclenchement de révision de prix du contrat $\pm$ \CONTRATSEUILDECLENCHEMENT\% \\
\hspace*{2.92cm}
- le pourcentage de variabilité maximale du volume en année 2 ou 3 par rapport au volume prévu en année 1 est de $\pm$ \CONTRATBORDEREUPOURCENTAGEANNEEUN\% \\
\hspace*{0.5cm}
\normalsize
En années 2 ou 3, préciser le n° d'enregistrement à l'IVBD du contrat initial déposé en année 1 : \CONTRATNUMEROENREGISTREMENTANNEEUN
 ~ \\   ~ \\ 
%PARTIE 6-a%
\circled{6a}~~\textbf{Prix et conditions de paiement:} \\
\hspace*{0.5cm}
Le prix convenu est de ~\CONTRATPRIX~\texteuro / T \\
\hspace*{0.5cm}
Moyen de paiement : \CONTRATMOYENPAIEMENT \\
\hspace*{0.5cm}
Délais de paiement : \CONTRATDELAIPAIEMENT \\
\hspace*{0.5cm}
\tiny{Rappel : Les Accords Interprofessionnel de l'IVBD encadrent strictement, dans leur article 11, les delais de paiement maximaux. Lorsque les bordereaux prévoient des dates de retiraison, les délais de paiement ne peuvent excéder 60 jours calendaires\\
\hspace*{0.5cm}
après chacune des dates de retiraison prévues. Lorsque les bordereaux sont signés dans le cadre d'un contrat pluriannuel, les delais de paiement ne peuvent excéder 150 jours calendaires après chacune des dates de retiraison prévues. Dans tous les\\
\hspace*{0.5cm}
autres cas, les délais de paiement son ceux prévus à l'article L 443-1 du Code de Commerce.\\
\hspace*{0.5cm}
Des sanction financières conséquentes sont prévues par l'article L 632-7 du Code Rural et l'article L 443-1 du Code de Commerce (amende de 75 000 ) en cas de non respect de ces dispositions.} 
  ~ \\   ~ \\ 
%PARTIE 6-b%
\normalsize
\circled{6b}~~\textbf{Conditions de paiement particulières:}~Quelle que soient les dates réelles de retiraison et de factures, le paiementdevra être effectid au plus tard\\
\hspace*{0.5cm}
60 jours (ou 150 jours dans le cadre d'un contrat pluriannuel) calendaire après la date de retiaison prévue au présent contrat.\\
\hspace*{0.5cm}
Le courtage de \CONTRATPOURCENTAGECOURTAGE \% est à la charge de \CONTRATPOURCENTAGEACHETEURCOURTAGE \% pour l'acheteur et de  \CONTRATPOURCENTAGEVENDEURCOURTAGE \% pour le vendeur.\\
\hspace*{0.5cm}
La cotisation interprofessionnelle est pour moitié à la charge de l'acheteur et pour moitié à la charge du vendeur, au taux en vigueur au moment de son\\
\hspace*{0.5cm}
éxigibilité.\\
\hspace*{0.5cm}
Le vendeur est assujetti à la TVA ~Oui~\squareChecked Non $\square$ ~~~~~ La facturation se fera : hors TVA \squareChecked ~~ avec TVA $\square$
  ~ \\   ~ \\ 
%PARTIE 7%
\circled{7}~~\textbf{Retiraison, Délivrance et Réserve de propriété:}\\
\hspace*{0.5cm}
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus pharetra pulvinar lorem, in commodo elit auctor sed. Sed turpis tortor, mollis non auctor\\
\hspace*{0.5cm}
id, sollicitudin vitae erat. Suspendisse mauris erat, interdum in tristique et, pulvinar et arcu. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam\\
\hspace*{0.5cm}
hendrerit, odio at pretium feugiat, nunc ipsum pretium justo, eget convallis enim risus cursus dui. Sed quis metus sollicitudin, pretium neque vel, gravida\\
\hspace*{0.5cm}
urna. Maecenas ante libero, faucibus sed risus ac, ornare imperdiet sem. Nunc quis condimentum felis, at iaculis tellus. Nunc non mi tellus. Nam fringilla\\
\hspace*{0.5cm}
accumsan tellus a maximus. Duis eu auctor augue. Proin metus neque, iaculis vel ante venenatis, laoreet blandit libero. 
  ~ \\   ~ \\ 
%PARTIE 8%
\circled{8}~~\textbf{Enregistrement à l'IVBD:}\\
\hspace*{0.5cm}
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus pharetra pulvinar lorem, in commodo elit auctor sed. Sed turpis tortor, mollis non auctor\\
\hspace*{0.5cm}
id, sollicitudin vitae erat. Suspendisse mauris erat, interdum in tristique et, pulvinar et arcu. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam\\
\hspace*{0.5cm}
hendrerit, odio at pretium feugiat, nunc ipsum pretium justo, eget convallis enim risus cursus dui. Sed quis metus sollicitudin, pretium neque vel, gravida\\
\hspace*{0.5cm}
urna. Maecenas ante libero, faucibus sed risus ac, ornare imperdiet sem. Nunc quis condimentum felis, at iaculis tellus. Nunc non mi tellus. Nam fringilla\\
\hspace*{0.5cm}
accumsan tellus a maximus. Duis eu auctor augue. Proin metus neque, iaculis vel ante venenatis, laoreet blandit libero. 

\end{document}

<?php
use_helper('Date');
use_helper('Display');
$moyensDePaiements = VracConfiguration::getInstance()->getMoyensPaiement();
$delaisDePaiements = VracConfiguration::getInstance()->getDelaisPaiement();
$contratRepartitions = VracConfiguration::getInstance()->getRepartitionCourtage();
$vendeur_raison_sociale = ($vrac->vendeur->raison_sociale) ?
        $vrac->vendeur->raison_sociale : $vrac->getVendeurObject()->getSociete()->raison_sociale;

$acheteur_raison_sociale = ($vrac->acheteur->raison_sociale) ?
        $vrac->acheteur->raison_sociale : $vrac->getAcheteurObject()->getSociete()->raison_sociale;

$mandataire_raison_sociale = "";
if ($vrac->mandataire_exist) {
    $mandataire_raison_sociale = ($vrac->mandataire->raison_sociale) ?
            $vrac->mandataire->raison_sociale : $vrac->getMandataireObject()->getSociete()->raison_sociale;
}
?>
\documentclass[a4paper,8pt]{extarticle}
\usepackage{geometry} % paper=a4paper
\usepackage[frenchb]{babel}
\usepackage[utf8]{inputenc}
\usepackage{units}
\usepackage{geometry}
\usepackage{graphicx}
\usepackage{fp}
\usepackage[table]{xcolor}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{marvosym}
\usepackage{truncate}
\usepackage{tabularx}
\usepackage{multirow}
\usepackage{amssymb}
\usepackage{ulem}
\usepackage{fmtcount}
\usepackage{eso-pic}

\makeatletter
\newlength\@tempdim@x
\newlength\@tempdim@y

\newcommand\AtLowerLeftCorner[3]{%
\begingroup
\@tempdim@x=0cm
\@tempdim@y=0cm
\advance\@tempdim@x#1
\advance\@tempdim@y#2
\put(\LenToUnit{\@tempdim@x},\LenToUnit{\@tempdim@y}){#3}%
\endgroup
}


\makeatother


\pagestyle{empty}

\renewcommand\sfdefault{phv}
\renewcommand{\familydefault}{\sfdefault}
\renewcommand{\TruncateMarker}{\small{...}}

\newcommand{\euro}{\EUR\xspace}

\newcommand{\squareChecked}{\makebox[0pt][l]{$\square$}\raisebox{.15ex}{\hspace{0.1em}$\checkmark$}}

\setlength{\oddsidemargin}{-1cm}
\setlength{\evensidemargin}{-1cm}
\setlength{\textwidth}{18cm}
\setlength{\textheight}{27.9cm}
\setlength{\topmargin}{-3cm}
\setlength{\parindent}{0pt}



\def\CONTRATNUMENREGISTREMENT{<?php echo substr($vrac->numero_contrat, -6)?>}
\def\CONTRATVISA{<?php echo $vrac->numero_archive ?>}
\def\CONTRATDATEENTETE{<?php echo format_date($vrac->valide->date_saisie) ?>}

\def\CONTRAT_TITRE{}
\def\CONTRATSOUSTITRE{<?php if($vrac->type_transaction == VracClient::TYPE_TRANSACTION_VIN_VRAC): ?>produits dans le Sud-Ouest<?php elseif($vrac->type_transaction == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE): ?>produits dans le Sud-Ouest retiraison bouteille<?php else: ?>destinés à l'élaboration d'AOP ou d'IGP du Sud-Ouest<?php endif; ?>}


\def\CONTRATVENDEURNOM{<?php echo display_latex_string(strtoupper($vendeur_raison_sociale)); ?><?php if ($vrac->responsable == 'vendeur'): ?> (responsable)<?php endif; ?>}
\def\CONTRATVENDEURCVI{<?php echo $vrac->vendeur->cvi ?>}
\def\CONTRATVENDEURSIRET{<?php echo $vrac->vendeur->siret ?>}
\def\CONTRATVENDEURACCISES{<?php echo $vrac->vendeur->no_accises ?>}
\def\CONTRATVENDEURADRESSE{<?php echo display_latex_string($vrac->vendeur->adresse); ?>}
\def\CONTRATVENDEURCOMMUNE{<?php  echo display_latex_string($vrac->vendeur->code_postal.' '.$vrac->vendeur->commune) ?>}
\def\CONTRATVENDEURTELEPHONE{<?php echo $vrac->getVendeurObject()->telephone ?>}
\def\CONTRATVENDEUREMAIL{<?php echo $vrac->getVendeurObject()->email ?>}


\def\CONTRATACHETEUREURNOM{<?php  echo display_latex_string(strtoupper($acheteur_raison_sociale)); ?><?php if ($vrac->responsable == 'acheteur'): ?> (responsable)<?php endif; ?>}
\def\CONTRATACHETEURCVI{<?php echo $vrac->acheteur->cvi ?>}
\def\CONTRATACHETEURSIRET{<?php echo $vrac->acheteur->siret ?>}
\def\CONTRATACHETEURACCISES{<?php echo $vrac->acheteur->no_accises ?>}
\def\CONTRATACHETEURADRESSE{<?php echo display_latex_string($vrac->acheteur->adresse); ?>}
\def\CONTRATACHETEURCOMMUNE{<?php echo display_latex_string($vrac->acheteur->code_postal.' '.$vrac->acheteur->commune); ?>}
\def\CONTRATACHETEURTELEPHONE{<?php echo $vrac->getAcheteurObject()->telephone ?>}
\def\CONTRATACHETEUREMAIL{<?php echo $vrac->getAcheteurObject()->email ?>}

\def\CONTRATCOURTIERNOM{<?php echo display_latex_string(strtoupper($mandataire_raison_sociale)) ?><?php if ($vrac->responsable == 'mandataire'): ?> (responsable)<?php endif; ?>}
\def\CONTRATCOURTIERADRESSE{<?php echo display_latex_string($vrac->mandataire->adresse); ?>}
\def\CONTRATCOURTIERCOMMUNE{<?php echo display_latex_string($vrac->mandataire->code_postal.' '.$vrac->mandataire->commune); ?>}
\def\CONTRATCOURTIERTELEPHONE{<?php echo $vrac->getMandataireObject()->telephone ?>}
\def\CONTRATCOURTIEREMAIL{<?php echo $vrac->getMandataireObject()->email ?>}

\def\CONTRATTYPE{Moûts}
\def\CONTRATTYPEUNITE{<?php if ($vrac->type_transaction == VracClient::TYPE_TRANSACTION_RAISINS): ?>kg<?php else: ?>hl<?php endif; ?>}
\def\CONTRATPRODUITNATURE{<?php if ($vrac->type_transaction == VracClient::TYPE_TRANSACTION_MOUTS): ?>Moûts<?php else: ?><?php if (preg_match('/AOC/', $vrac->produit)): ?>AOP<?php elseif (preg_match('/IGP/', $vrac->produit)): ?>IGP<?php endif;?><?php endif;?>}
\def\CONTRATPRODUITLIBELLE{<?php echo $vrac->produit_libelle ?>}
\def\CONTRATPRODUITCEPAGE{<?php echo $vrac->cepage_libelle ?>}
\def\CONTRATPRODUITMILLESIME{<?php echo $vrac->millesime ?>}
\def\CONTRATPRODUITDEGRE{<?php echo ($vrac->degre)? $vrac->degre.'°' : ''; ?>}
\def\CONTRATPRODUITLOT{<?php echo ($vrac->lot)? $vrac->lot : ''; ?>}
\def\CONTRATPRODUITQUANTITE{<?php echo ($vrac->jus_quantite)? $vrac->jus_quantite : $vrac->raisin_quantite ?>}
\def\CONTRATPRIXUNITAIRE{<?php echo $vrac->prix_initial_unitaire ?>}
\def\CONTRATTYPEEXPLICATIONPRIX{Le prix payé est exprimé en euros par hectolitre}

\def\CONTRATDATEMAXENLEVEMENT{<?php echo format_date($vrac->date_limite_retiraison) ?>}
\def\CONTRATDATEMINENLEVEMENT{<?php echo format_date($vrac->date_debut_retiraison) ?>}
\def\CONTRATOBSERVATIONS{<?php echo display_latex_string($vrac->conditions_particulieres); ?>}
\def\CONTRATFRAISDEGARDE{ ~~~~~\euro/hl}

\def\CONTRATMOYENPAIEMENT{<?php echo ($vrac->moyen_paiement) ? VracConfiguration::getInstance()->getMoyensPaiement()[$vrac->moyen_paiement] : '' ; ?>}
\def\CONTRATDELAIPAIEMENT{<?php echo ($vrac->delai_paiement) ? VracConfiguration::getInstance()->getDelaisPaiement()[$vrac->delai_paiement] : '' ; ?>}
\def\CONTRATACOMPTE{<?php echo $vrac->acompte ?>}
\def\CONTRATLIEUPRODUIT{<?php echo ($vrac->logement)? $vrac->logement : $vrac->vendeur->commune ?>}
\def\CONTRATVINIFICATIONPRODUIT{<?php echo ($vrac->vinification)? $vrac->vinification : $vrac->vendeur->commune ?>}

\def\CONTRATLIEUCREATION{LES VERCHERS SUR LAYON}
\def\CONTRATDATECREATION{02/09/2015}

\def\CONTRATMANDATAIREVISA{<?php echo format_date($vrac->date_signature) ?>}
\def\CONTRATDATESIGNATUREVENDEUR{<?php echo format_date($vrac->date_signature) ?>}
\def\CONTRATDATESIGNATUREACHETEUR{<?php echo format_date($vrac->date_signature) ?>}

\def\CONTRATGENERIQUEDOMAINE{<?php echo $vrac->renderLabels(); ?>}
\def\CONTRATCONDITIONNEMENT{<?php if ($vrac->conditionnement_crd == 'NEGOCE_ACHEMINE'): ?>\textbf{P} : Vin préparé pour la mise en bouteille<?php elseif ($vrac->conditionnement_crd == 'ACHAT_TIRE_BOUCHE'): ?>\textbf{TB} : Tiré Bouché<?php endif; ?>}
\def\CONTRATTAUXCOURTAGE{<?php echo $vrac->courtage_taux ?>}

\newcolumntype{C}[1]{>{\centering\arraybackslash}p{#1}}
\newcolumntype{R}[1]{>{\raggedleft\arraybackslash}p{#1}}

\begin{document}

{
\renewcommand{\arraystretch}{1.5}
\begin{tabularx}{\textwidth}{
    >{\raggedright\arraybackslash}p{86mm}
    >{\raggedright\arraybackslash}p{86mm}
}

	 \textbf{\large{\uppercase{Union Interprofessionnelle du Vin de Cahors et des côtes du lot}}} &
     \multicolumn{1}{C{86mm}}{%{
     \textbf{\Large{
   <?php if($vrac->type_transaction == VracClient::TYPE_TRANSACTION_VIN_VRAC): ?>
      		CONTRAT D'ACHAT DE VIN EN PROPRIETE
   <?php elseif($vrac->type_transaction == VracClient::TYPE_TRANSACTION_MOUTS): ?>
           CONTRAT D'ACHAT DE MOÛT EN PROPRIETE
   <?php elseif($vrac->type_transaction == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE): ?>
           CONTRAT D'ACHAT DE BOUTEILLE EN PROPRIETE
   <?php else: ?>
       CONTRAT D'ACHAT DE VENDANGE EN PROPRIETE
   <?php endif; ?>
   	    }}
    }%}
     \\
	 \textbf{\small{Villa Cahors Malbec - Place François Mitterand - 46000 CAHORS \newline Tél.: 05 65 23 82 35 - contact@vinsdecahors.fr - site : www.vindecahors.fr}} &
     \multicolumn{1}{C{86mm}}{%{
     \footnotesize{
         établi suivant accord interprogessionnel du Vin de Cahors Appellation d'Origine Protégée et Côtes du Lot Indication Géographique Protégée pour les ventes sous DAA/DAC à destination du marché intérieur.
     }
     }%}
     \\
	 ~  &
    \multicolumn{1}{R{86mm}}{%{
        \textbf{\Large{N° \CONTRATVISA}}
    }%}
     \\

\end{tabularx}
}

\textbf{1 - Désignation des parties} \\

\begin{multicols}{2}

\begin{minipage}[t]{0.485\textwidth}
\begin{tabularx}{\textwidth}{|Xl|}
	\hline
    ~ & ~ \\
	\multicolumn{2}{|l|}{\textbf{VENDEUR : \CONTRATVENDEURNOM}} \\
    ~ & ~ \\
	N° CVI : & \textbf{\CONTRATVENDEURCVI} \\
	N° SIRET : & \textbf{\CONTRATVENDEURSIRET} \\
    N° d'Accises : & \textbf{\CONTRATVENDEURACCISES} \\
    ~ & ~ \\
	Adresse : & \textbf{\CONTRATVENDEURADRESSE} \\
    Commune : & \textbf{\CONTRATVENDEURCOMMUNE} \\
    ~ & ~ \\
	Tél : & \textbf{\CONTRATVENDEURTELEPHONE} \\
	Courriel : & \textbf{\CONTRATVENDEUREMAIL} \\
    ~ & ~ \\
	\hline
\end{tabularx}
\end{minipage}

\begin{minipage}[t]{0.485\textwidth}
\begin{tabularx}{\textwidth}{|Xl|}
	\hline
    ~ & ~ \\
	\multicolumn{2}{|l|}{\textbf{ACHETEUR : \CONTRATACHETEUREURNOM}} \\
    ~ & ~ \\
    N° CVI : & \textbf{\CONTRATACHETEURCVI} \\
	N° SIRET : & \textbf{\CONTRATACHETEURSIRET} \\
    ~ & ~ \\
    ~ & ~ \\
	Adresse : & \textbf{\CONTRATACHETEURADRESSE} \\
    Commune : & \textbf{\CONTRATACHETEURCOMMUNE} \\
    ~ & ~ \\
	Tél : & \textbf{\CONTRATACHETEURTELEPHONE} \\
	Courriel : & \textbf{\CONTRATACHETEUREMAIL} \\
    ~ & ~ \\
	 \hline
\end{tabularx}
\end{minipage}

\end{multicols}

<?php if ($vrac->mandataire_identifiant): ?>

\begin{tabularx}{\textwidth}{|X X X X|}
	\hline
    ~ & ~ & ~ & ~ \\
    \multicolumn{4}{|p{160mm}|}{\textbf{COURTIER : \CONTRATCOURTIERNOM}} \\
	Adresse : & \textbf{\CONTRATCOURTIERADRESSE} & Tél : & \textbf{\CONTRATACHETEURTELEPHONE} \\
    Commune : & \textbf{\CONTRATCOURTIERCOMMUNE} & Courriel : & \textbf{\CONTRATACHETEUREMAIL} \\
    ~ & ~ & ~ & ~ \\
	\hline
\end{tabularx}

<?php endif; ?>

\bigskip

\textbf{Relations précontractuelles : Initiative du producteur} \\
\small{
    Le présent contrat doit être précédé d'une proposition préalable du vendeur. Au titre des critères et modalité de révision ou de détermination du prix,  elle prend en compte un ou plusieurs indicateurs relatifs aux couts pertinents de production en agriculture et à l'évolution de ces couts. Elle constitue le socle de la négociation entre le vendeur et l'acheteur.\\
    Tout refus ou réserve de l'acheteur portant sur la proposition doit être faite par écrit, motivé et dans un délai raisonnable.\\
    Le vendeur peut mandater son courtier pour qu'il fasse la proposition préalable en son nom et pour son compte. Dans ce cas, le mandat doit être écrit.\\
    La proposition préalable du vendeur ou son mandat au courtier accompagné de la proposition préalable fait en son nom est annexé au présent contrat.\\
    Le vendeur peut exiger par écrit de l'acheteur une offre de contrat écrit.\\
}

\textbf{2(*) - Nom du vin : } \textbf{\CONTRATPRODUITLIBELLE} \\
dont le vendeur autorise l'utilisation dans le cadre du présent contrat~~~~\textbf{OUI}~ <?php if ($vrac->autorisation_nom_vin): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> ~~~\textbf{NON}~ <?php if (!$vrac->autorisation_nom_vin): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> \\

\textbf{3(*) - Nom du producteur : } \textbf{\CONTRATVENDEURNOM} \\
le vendeur autorise l'utilisation par l'acheteur, dans le cadre du présent contrat, de son nom patronymique ou raison sociale et adresse pour la présentation du lot du vin concerné~~~~\textbf{OUI}~ <?php if ($vrac->autorisation_nom_producteur): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> ~~~\textbf{NON}~ <?php if (!$vrac->autorisation_nom_producteur): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> \\

\textbf{4 - Désignation du vin acheté en vrac : } \\
\textbf{retiré en citerne}~ <?php if ($vrac->type_transaction != VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> ~~~\textbf{retiré en bouteilles}~ <?php if ($vrac->type_transaction == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> \\
Volume : \textbf{\CONTRATPRODUITQUANTITE} hl AOP Cahors - Millésime \textbf{\CONTRATPRODUITMILLESIME} \\

\textbf{5(*) - Préparation du vin et embouteillage : } \\
Commune de vinification : \textbf{\CONTRATLIEUPRODUIT} \\
Commune de logement : \textbf{\CONTRATVINIFICATIONPRODUIT} \\

\textbf{9 - Clause de réserve de proriété:}~~~~\textbf{OUI}~ <?php if ($vrac->clause_reserve_propriete): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> ~~~\textbf{NON}~ <?php if (!$vrac->clause_reserve_propriete): ?>\squareChecked<?php else: ?>$\square$<?php endif; ?> \\
\textbf{Les parties entendent placer le présent contrat sous le régime de la réserve de propriété, dans le respect des dispositions prévues aux articles 2367 à 2372 du Code Civil ; le vendeur se réserve la propriété des vins vendus jusqu'à parfait paiement de ceux-ci. } \\

\textbf{10 - Clause attributive de compétence} \\
En cas de litige

\begin{multicols}{2}

\begin{minipage}[t]{0.485\textwidth}

\textbf{\normalsize{\underline{Condition de paiement} :}} \\ ~ \\
<?php if ($vrac->acompte): ?>
Acompte à la signature : \textbf{\CONTRATACOMPTE}~\euro \\
<?php endif; ?>
Moyen de paiement : \textbf{\CONTRATMOYENPAIEMENT}\\
Delai de paiement : \textbf{\CONTRATDELAIPAIEMENT}\\
\end{minipage}

\begin{minipage}[t]{0.485\textwidth}

\textbf{\normalsize{\underline{Conditions} :}} \\ ~ \\
<?php if ($vrac->autorisation_nom_vin && $vrac->autorisation_nom_producteur): ?>
Autorisation d'utilisation du nom du vin et du producteur.\\
<?php else: ?>
<?php if ($vrac->autorisation_nom_vin): ?>
Autorisation d'utilisation du nom du vin.\\
<?php endif; ?>
<?php if ($vrac->autorisation_nom_producteur): ?>
Autorisation d'utilisation du nom du producteur.\\
<?php endif; ?>
<?php endif; ?>
<?php if ($vrac->courtage_taux): ?>
Taux de courtage : \textbf{\CONTRATTAUXCOURTAGE}\% <?php if ($vrac->courtage_repartition): ?>(\textbf{<?php echo (array_key_exists($vrac->courtage_repartition, $contratRepartitions))? str_replace('%', '\%', $contratRepartitions[$vrac->courtage_repartition]) : '' ?>})<?php endif; ?> \\
<?php endif; ?>
Date de début de retiraison : \textbf{\CONTRATDATEMINENLEVEMENT}\\
Date de fin de retiraison : \textbf{\CONTRATDATEMAXENLEVEMENT}\\

\end{minipage}
\end{multicols}

 ~ \\

\textbf{OBSERVATIONS:} \\
\fbox{
\parbox[c][3cm]{\textwidth}{ ~ \\ \CONTRATOBSERVATIONS \\ }
}

 ~ \\ ~ \\

\begin{tabularx}{\textwidth}{<?php if ($vrac->mandataire_identifiant): ?>|X<?php endif; ?>|X|X|}
\hline
<?php if ($vrac->mandataire_identifiant): ?>\cellcolor{gray!25}\textbf{Le courtier} & <?php endif; ?>\cellcolor{gray!25}\textbf{Le vendeur} & \cellcolor{gray!25}\textbf{L'acheteur} \\
\hline
<?php if ($vrac->mandataire_identifiant): ?>~ & <?php endif; ?>~ & ~ \\
<?php if ($vrac->mandataire_identifiant): ?>Le \CONTRATMANDATAIREVISA, & <?php endif; ?>Le \CONTRATDATESIGNATUREVENDEUR, & Le \CONTRATDATESIGNATUREACHETEUR, \\
<?php if ($vrac->mandataire_identifiant): ?>~ & <?php endif; ?>~ & ~ \\
<?php if ($vrac->mandataire_identifiant): ?>\textbf{Signé électroniquement} & <?php endif; ?>\textbf{Signé électroniquement} & \textbf{Signé électroniquement} \\
\hline
\end{tabularx}

\newpage

\includegraphics[scale=0.85]{<?php echo sfConfig::get('sf_web_dir'); ?>/pdf/_annexe_ivso.pdf}

\end{document}

<?php
use_helper('Date');
use_helper('Display');
use_helper('Compte');
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
\def\IVBDCOORDONNEESADRESSE{1, rue des Récollets - BP 426 - 24104 BERGERAC Cedex - Tél. 05 53 63 57 57 - Fax: 05 53 63 01 30}

\def\CONTRATNUMARCHIVE{<?php echo $vrac->numero_archive ?>}
\def\CONTRATNUMENREGISTREMENT{<?php echo substr($vrac->numero_contrat, -6)?>}
\def\CONTRATANNEEENREGISTREMENT{<?php echo substr($vrac->numero_contrat, 2, 2)?>}
\def\CONTRATVISA{Pas de visa}
\def\CONTRATDATEENTETE{<?php echo date("d/m/Y", strtotime($vrac->date_visa)); ?>}

\def\CONTRAT_TITRE{CONTRAT D'ACHAT EN PROPRIETE}

\def\VILLEVENDEUR{<?php echo $vrac->vendeur->commune ?>}
\def\VILLEACHETEUR{<?php echo $vrac->acheteur->commune ?>}
\def\RSACHETEUR{<?php echo display_latex_string($vrac->acheteur->raison_sociale); ?>}

\def\CONTRATVENDEURNOM{<?php echo display_latex_string($vendeur_raison_sociale); ?><?php if ($vrac->responsable == 'vendeur'): ?> (responsable)<?php endif; ?>}
\def\CONTRATVENDEURCVI{<?php display_cvi_formatted($vrac->vendeur->cvi) ?>}
\def\CONTRATVENDEURSIRET{<?php formatSIRET($vrac->vendeur->siret) ?>}
\def\CONTRATVENDEURADRESSE{<?php echo display_latex_string($vrac->vendeur->adresse.' '.$vrac->vendeur->code_postal.' '.$vrac->vendeur->commune) ?>}
\def\CONTRATVENDEURTELEPHONE{<?php echo $vrac->getVendeurObject()->telephone ?>}
\def\CONTRATVENDEURPAYEUR{<?php echo display_latex_string($vrac->representant->raison_sociale); ?>}

\def\CONTRATACHETEURNOM{<?php echo display_latex_string($acheteur_raison_sociale); ?><?php if ($vrac->responsable == 'acheteur'): ?> (responsable)<?php endif; ?>}
\def\CONTRATACHETEURCVI{<?php display_cvi_formatted($vrac->acheteur->cvi) ?>}
\def\CONTRATACHETEURSIRET{<?php formatSIRET($vrac->acheteur->siret) ?>}
\def\CONTRATACHETEURADRESSE{<?php echo display_latex_string($vrac->acheteur->adresse.' '.$vrac->acheteur->code_postal.' '.$vrac->acheteur->commune); ?>}
\def\CONTRATACHETEURTELEPHONE{<?php echo $vrac->getAcheteurObject()->telephone ?>}

\def\CONTRATCOURTIERNOM{<?php echo display_latex_string($mandataire_raison_sociale); ?><?php if ($vrac->responsable == 'mandataire'): ?> (responsable)<?php endif; ?>}
\def\CONTRATCOURTIERCARTEPRO{<?php echo $vrac->mandataire->carte_pro ?>}
\def\CONTRATCOURTIERSIRET{<?php formatSIRET($vrac->mandataire->siret) ?>}
\def\CONTRATCOURTIERADRESSE{<?php echo display_latex_string($vrac->mandataire->adresse.' '.$vrac->mandataire->code_postal.' '.$vrac->mandataire->commune); ?>}
\def\CONTRATCOURTIERTELEPHONE{<?php echo ($vrac->mandataire_identifiant)? $vrac->getMandataireObject()->telephone : null; ?>}

\def\CONTRATVOLUME{<?php echo ($vrac->jus_quantite)? $vrac->jus_quantite : $vrac->raisin_quantite ?>}
\def\CONTRATAPPELLATIONPRODUIT{<?php echo $vrac->produit_libelle ?>}
\def\CONTRATLABELSPRODUIT {<?php echo $vrac->renderLabels() ?>}
\def\CONTRATCOULEURPRODUIT{??}
\def\CONTRATMILLESIMEPRODUIT{<?php echo ($vrac->millesime) ? $vrac->millesime : 'NM';  if ($vrac->millesime_85_15) { echo " (85/15)"; } ?>}
\def\CONTRATLIEUPRODUIT{<?php echo ($vrac->logement)? $vrac->logement : $vrac->vendeur->commune ?>}
\def\CONTRATNOMPRODUIT{<?php echo ($vrac->autorisation_nom_vin)? VracConfiguration::getInstance()->getCategories()[$vrac->categorie_vin].' '.$vrac->domaine : ''; ?>}

\def\CONTRATBORDEREUPOURCENTAGEANNEEUN{<?php echo $vrac->pourcentage_variation ?>}
\def\CONTRATSEUILDECLENCHEMENT{<?php echo $vrac->seuil_revision ?>}
\def\CONTRATNUMEROENREGISTREMENTANNEEUN{<?php echo $vrac->reference_contrat ?>}

\def\CONTRATPRIXTOUTELETTRE{cinq mille deux cents trente}
\def\CONTRATPRIX{<?php echo $vrac->prix_initial_unitaire ?>}
\def\CONTRATMOYENPAIEMENT{<?php echo (array_key_exists($vrac->moyen_paiement, $moyensDePaiements))? $moyensDePaiements[$vrac->moyen_paiement] : ''; ?>}
\def\CONTRATDELAIPAIEMENT{<?php echo (array_key_exists($vrac->delai_paiement, $delaisDePaiements))? $delaisDePaiements[$vrac->delai_paiement] : '' ?>}

\def\CONTRATREPARTITION{<?php echo (array_key_exists($vrac->courtage_repartition, $contratRepartitions))? str_replace('%', '\%', $contratRepartitions[$vrac->courtage_repartition]) : '' ?>}

\def\DATELIMITERETIRAISON{<?php echo date("d/m/Y", strtotime($vrac->date_limite_retiraison)); ?>}

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
    \textbf{- AVEC RETIRAISON EN BOUTEILLES -}\\
    ~  \\
    n° IB - \CONTRATANNEEENREGISTREMENT ~- \begin{large}\textbf{\CONTRATNUMENREGISTREMENT} \end{large} \\
\end{center}
\end{minipage}
\hspace{2cm}
  \begin{minipage}[t]{0.3\textwidth}
  \vspace{-0.5cm}
\begin{tabularx}{\textwidth}{|X|}
\hline
~ \\
	 \includegraphics[scale=0.85]{<?php echo sfConfig::get('sf_web_dir'); ?>/images/cachet_ivbd.png} \\ N° \begin{Large}
	  \CONTRATNUMARCHIVE
\end{Large} ~~~~~~~~~~~~~~~~~~~~~~~ \CONTRATDATEENTETE \\
\hline
\end{tabularx}
\end{minipage}
\\
\\
\\
\textbf{Relations précontractuelles : Initiative du producteur} \\
\small{Le présent contrat doit être précédé d'une proposition préalable du vendeur. Au titre des critères et modalité de révision ou de détermination du prix,  elle prend en compte un ou plusieurs indicateurs relatifs aux coûts pertinents de production en agriculture et à l'évolution de ces coûts. Elle constitue le socle de la négociation entre le vendeur et l'acheteur.}\\
\small{Tout refus ou réserve de l'acheteur portant sur la proposition doit être faite par écrit, motivé et dans un délai raisonnable.}\\
\small{Le vendeur peut mandater son courtier pour qu'il fasse la proposition préalable en son nom et pour son compte. Dans ce cas, le mandat doit être écrit.}\\
\small{La proposition préalable du vendeur ou son mandat au courtier accompagné de la proposition préalable fait en son nom est annexé au présent contrat.}\\
\small{Le vendeur peut exiger par écrit de l'acheteur une offre de contrat écrit.}\\
\\
%PARTIE 1%
\circled{1}~~\textbf{Désignation des parties:} \\
\normalsize
\begin{minipage}[t]{0.6\textwidth}
\hspace*{0.5cm}
\textbf{A)} VENDEUR : \textbf{\CONTRATVENDEURNOM} \\
\hspace*{0.5cm}
Adresse : \textbf{\CONTRATVENDEURADRESSE} \\
<?php if ($vrac->vendeur_identifiant != $vrac->representant_identifiant): ?>
\hspace*{0.5cm}
Pour le compte de : \textbf{\CONTRATVENDEURPAYEUR}
<?php endif; ?>
\\ ~
\hspace*{0.5cm}
\textbf{B)} ACHETEUR : \textbf{\CONTRATACHETEURNOM} \\
\hspace*{0.5cm}
Adresse : \textbf{\CONTRATACHETEURADRESSE} \\ ~ \\
<?php if($vrac->mandataire_identifiant): ?>
\hspace*{0.5cm}
\textbf{C)} COURTIER : \textbf{\CONTRATCOURTIERNOM} \\
\hspace*{0.5cm}
Adresse : \textbf{\CONTRATCOURTIERADRESSE}
<?php endif; ?>
\end{minipage}
\hspace{2cm}
\begin{minipage}[t]{0.3\textwidth}
<?php if ($vrac->vendeur->cvi): ?>
N° CVI : \textbf{\CONTRATVENDEURCVI} \\
<?php else: ?>
\\ ~ \\
<?php endif; ?>
<?php if ($vrac->vendeur->siret): ?>
N° SIRET : \textbf{\CONTRATVENDEURSIRET} \\
<?php else: ?>
\\ ~ \\
<?php endif; ?>
Tél. : \textbf{\CONTRATVENDEURTELEPHONE} \\ ~ \\
<?php if ($vrac->acheteur->cvi): ?>
N° CVI : \textbf{\CONTRATACHETEURCVI} \\
<?php else: ?>
\\ ~ \\
<?php endif; ?>
<?php if ($vrac->acheteur->siret): ?>
N° SIRET : \textbf{\CONTRATACHETEURSIRET} \\
<?php else: ?>
\\ ~ \\
<?php endif; ?>
Tél. : \textbf{\CONTRATACHETEURTELEPHONE} \\ ~ \\
<?php if($vrac->mandataire_identifiant): ?>
N° CIP : \textbf{\CONTRATCOURTIERCARTEPRO} \\
N° SIRET : \textbf{\CONTRATCOURTIERSIRET} \\
Tél. : \textbf{\CONTRATCOURTIERTELEPHONE}
<?php endif; ?>
\end{minipage}
%PARTIE 2%
\circled{2}~~\textbf{Désignation du produit :} \normalsize \textbf{\CONTRATAPPELLATIONPRODUIT} <?php if ($vrac->cepage_libelle) { echo display_latex_string(" - ".$vrac->cepage_libelle); } if ($vrac->cepage_85_15) { echo display_latex_string(" - 85/15"); } ?> \small {\CONTRATLABELSPRODUIT} de la récolte : \textbf{\CONTRATMILLESIMEPRODUIT}\\
\hspace*{0.5cm}
( \textbf{Volume} : \textbf{\CONTRATVOLUME}~hl ) Ce vins droit de goût, loyal et marchand est garanti conforme aux prescriptions légales et à l'échantillon fourni pour la conclusion de cette transaction. \\
\hspace*{0.5cm}
Ce vin est logé dans la commune de : \textbf{\CONTRATLIEUPRODUIT}
~ \\
%PARTIE 3%
\circled{3}~~\textbf{Nom de l'exploitation et étiquetage:}
\normalsize Ce vin porte le nom de : \textbf{\CONTRATNOMPRODUIT} \\
\hspace*{0.5cm}
dont le vendeur certifie l'existence, conformément aux règlementations communautaire et nationale, et dont il autorise l'utilisation dans le cadre du présent \\
\hspace*{0.5cm}
contrat. Pour toute utilisation du nom de l'exploitation (Château, Domaine...), l'étiquette devra obligatoirement mentionner le nom et l'adresse du négociant,\\
\hspace*{0.5cm}
ainsi que le nom du viticulteur. En outre, l'acheteur s'engage à faire figurer sur l'étiquette principale fournie par ses soins (en clair et en caractères de taille\\
\hspace*{0.5cm}
correspondant au minimum aux deux tiers de ceux identifiant le producteur) son nom, sa qualité et son adresse sous la forme : \\
\hspace*{0.5cm}
"mis en bouteilles au château (ou à la propriété) à \textbf{\VILLEVENDEUR}~par \textbf{\RSACHETEUR}~négociant à \textbf{\VILLEACHETEUR}"
~ \\
%PARTIE 4%
\circled{4}~~\textbf{Nom du producteur:} \normalsize Pour le cas où aucun nom d'exploitation n'est précisé, le vendeur autorise l'utilisation par l'acheteur, dans le cadre du présent\\
\hspace*{0.5cm}
contrat, de son nom patronymique ou de sa raison sociale, ainsi que de son adresse pour la présentation du vin.<?php if ($vrac->autorisation_nom_producteur): ?>~Oui~\squareChecked~Non~$\square$<?php else : ?>~Oui~$\square$~Non~\squareChecked<?php endif; ?>
~ \\
%PARTIE 5%
\circled{5}~~\textbf{Préparation du vin et embouteillage:} \normalsize \underline{Dans tous les cas l'acheteur assume la responsabilité de la mise en bouteilles}. Cependant, préciser l'option retenue :\\
\hspace*{0.5cm}
Les opérations techniques de préparation du vin à la mise sont effectuées par : <?php if ($vrac->preparation_vin == 'VENDEUR'): ?>~le vendeur~\squareChecked~l'acheteur~$\square$<?php else : ?>~le vendeur~$\square$~l'acheteur~\squareChecked<?php endif; ?> \\
\hspace*{0.5cm}
Les opérations techniques de mise en bouteilles sont effectuées par : <?php if ($vrac->embouteillage == 'VENDEUR'): ?>~le vendeur~\squareChecked~l'acheteur~$\square$<?php else : ?>~le vendeur~$\square$~l'acheteur~\squareChecked<?php endif; ?> \\
\hspace*{0.5cm}
Lorsque l'acheteur effectue les opérations techniques, le vendeur met à la disposition de l'acheteur ses installations ainsi que les branchements \\
\hspace*{0.5cm}
et la consommation d'eau et d'électricité.
~ \\
 %PARTIE 6%
\circled{6}~~\textbf{Mode de conditionnement:} \normalsize \underline{Dans tous les cas, les CRD utilisées sont les CRD du négociant}. Cependant, préciser l'option retenue :\\
\hspace*{0.5cm}
<?php if ($vrac->conditionnement_crd == 'NEGOCE_ACHEMINE'): ?>\squareChecked<?php else : ?>$\square$<?php endif; ?>~CRD Négoce acheminées sur la propriété du récoltant pour être apposées lors de la mise. \\
\hspace*{0.5cm}
<?php if ($vrac->conditionnement_crd == 'ACHAT_TIRE_BOUCHE'): ?>\squareChecked<?php else : ?>$\square$<?php endif; ?>~Achat en Tiré Bouché Repéré. Les bouteilles seront transportées sans étiquette et non capsulées. Les CRD Négoce seront apposées dans \\
\hspace*{0.5cm}
les chais du négociant. Les n° de lot et d'embouteilleur devront figurer sur les bouteilles; l'appellation et le nom du récoltant sur les bouchons. \\
\hspace*{0.5cm}
Le cas échéant, le millésime devra également figurer sur les bouchons.
~ \\
%PARTIE 7%
\circled{7}~~\textbf{Bordereau s'inscrivant dans le cadre d'un contrat d'achat pluriannuel:}<?php if ($vrac->pluriannuel): ?>~Non~$\square$~Oui~\squareChecked<?php else : ?>~Non~\squareChecked~Oui~$\square$<?php endif; ?>\\
\hspace*{0.5cm}
Les critères et modalités de révision et de détermination du prix sont librement définis par les partis. \\
\hspace*{0.5cm}
Ils doivent comporter au moins trois indicateurs que sont : \\
\hspace*{0.5cm}
-~~~Les indicateurs de la proposition socle \\
\hspace*{0.5cm}
-~~~Les mercuriales des vins de Bergerac et Duras \\
\hspace*{0.5cm}
-~~~Un ou plusieurs indicateurs relatifs aux quantités, à la composition, à la qualité, à l'origine et à la traçabilité des produits ou au respect d'un cahier des \hspace*{0.5cm} charges. \\
 ~ \\
%PARTIE 8%
\circled{8}~~\textbf{Prix et conditions de paiement:}
Le prix convenu est de ~\textbf{\CONTRATPRIX}~\texteuro / T ( Moyen de paiement : \textbf{\CONTRATMOYENPAIEMENT} , Délais de paiement : \textbf{\CONTRATDELAIPAIEMENT} ) \\
\hspace*{0.5cm}
\colorbox{lightgray}{Il est rappelé que les délais de paiement du présent contrat sont ceux prévus par la loi.}\\
\hspace*{0.5cm}
\normalsize
<?php if($vrac->courtage_taux): ?>
Le courtage de \textbf{<?php echo $vrac->courtage_taux ?>} \% est à la charge de \textbf{\CONTRATREPARTITION}.\\
\hspace*{0.5cm}
<?php endif; ?>
La cotisation interprofessionnelle est pour moitié à la charge de l'acheteur et pour moitié à la charge du vendeur, au taux en vigueur au moment de son\\
\hspace*{0.5cm}
exigibilité. Le vendeur est assujetti à la TVA <?php if ($vrac->vendeur_tva): ?>~Oui~\squareChecked Non~$\square$<?php else: ?>~Oui~$\square$ Non~\squareChecked<?php endif;?>~La facturation se fera : <?php if ($vrac->tva == 'SANS'): ?>avec TVA $\square$ ~~ hors TVA \squareChecked<?php else : ?>avec TVA \squareChecked ~~ hors TVA $\square$<?php endif; ?> \tiny{(dans ce cas, attestation d'achat en franchise à fournir)}
~ \\
\normalsize
%PARTIE 9%
\circled{9}~~\textbf{Retiraison, Délivrance :}\\
\hspace*{0.5cm}
\underline{La retiraison devra s'effectuer dans un délai maximal de 90 jours après signature du présent contrat sauf mention particulière précisée ci-dessous.}\\
\hspace*{0.5cm}
\underline{Mention particulière} : La retiraison intégrale devra s'effectuer au plus tard le : \textbf{\DATELIMITERETIRAISON} et en fonction du calendrier précisé au verso du présent contrat.\\
\hspace*{0.5cm}
Pour tout différé de retiraison, un avenant au présent contrat devra être établi et signé par chacune des parties.\\
\hspace*{0.5cm}
De convention expresse entre les parties, la délivrance au sens de l'article 1604 du Code Civil se réalisera à la date figurant sur le titre de mouvement.
\hspace*{0.5cm}
~ \\
%PARTIE 9bis%
\circled{9}~~\textbf{bis - Résiliation du contrat :}\\
\hspace*{0.5cm}
En cas de non-respect par l'acheteur des dates de retiraison ci-dessus mentionnées, le vendeur pourra invoquer l'article 1657 du code civil :\\
\hspace*{0.5cm}
 "annulation de droit de la vente pour non enlèvement des vins à la date prévue". En cas de non-agrément motivé du produit (vin non loyal et\\
\hspace*{0.5cm}
marchand), dans le délai de retiraison prévu au contrat, l'acheteur pourra demander la résiliation du contrat.
~ \\
%PARTIE 9ter%
\circled{9}~~\textbf{ter - Cas de Force Majeure :}\\
\hspace*{0.5cm}
Les parties ne sauraient être tenues responsables de l’inexécution de leurs obligations respectives si cette inexécution est due à un cas de force majeure,\\
\hspace*{0.5cm}
conformément aux dispositions de l’article 1218 du code civil.\\
\hspace*{0.5cm}
L’exécution des obligations est suspendue pendant la durée de la force majeure, et est reprise si les effets de la cause de non-exécution prennent fin.
~ \\
%PARTIE 10%
\circled{10}~~\textbf{Réserve de propriété :}\\
\hspace*{0.5cm}
Les parties entendent placer le présent contrat sous le régime de la réserve de propriété prévu par la loi du 12 mai 1980.\\
\hspace*{0.5cm}
En application de cette loi, le vendeur se réserve la propriété des vins vendus jusqu'à parfait paiement de ceux-ci. <?php if ($vrac->clause_reserve_propriete): ?>~Oui~\squareChecked Non~$\square$<?php else: ?>~Oui~$\square$ Non~\squareChecked<?php endif;?>
~ \\
%PARTIE 11%
\circled{11}~~\textbf{Enregistrement à l'IVBD:}\\
\hspace*{0.5cm}
En vertu de l'article 4 des Accords Interprofessionnels étendus de l'IVBD conclus pour la première fois le 21 août 1981, le présent contrat\\
\hspace*{0.5cm}
est soumis à enregistrement auprès des services de l'IVBD. Pour toute annulation conjointe du présent contrat, chaque partie devra manifester\\
\hspace*{0.5cm}
son accord écrit à l'IVBD par courrier signé. Le courtier signataire du présent contrat pouvant\\
\hspace*{0.5cm}
agir au nom de chacune des parties. En cas d'annulation du contrat pour cause de non retiraison du vin dans les délais prévus, le vendeur devra\\
\hspace*{0.5cm}
en avertir l'IVBD par courrier signé et circonstancié.\\
\hspace*{0.5cm}
\textit{Les signataires attestent avoir pris connaissance de la page 2 du présent bordereau, et s'engagent à respecter les conditions particulières et règles}\\
\hspace*{0.5cm}
\textit{d'utilisation spécifiées. En l'absence de signature du vendeur et de l'acheteur, le courtier signataire du présent contrat garantit l'exactitude de}\\
\hspace*{0.5cm}
\textit{l'ensemble des informations portées sur ce document}.

\vspace*{0.3cm}

\begin{minipage}[t]{0.3\textwidth}
\begin{center}
Le Vendeur,\\
Signé électroniquement, le \textbf{<?php echo ($vrac->valide->date_signature_vendeur)? date("d/m/Y", strtotime($vrac->valide->date_signature_vendeur)) : date("d/m/Y", strtotime($vrac->date_signature));  ?>}
\end{center}
\end{minipage}
\begin{minipage}[t]{0.3\textwidth}
\begin{center}
L'Acheteur,\\
Signé électroniquement, le \textbf{<?php echo ($vrac->valide->date_signature_acheteur)? date("d/m/Y", strtotime($vrac->valide->date_signature_acheteur)) : date("d/m/Y", strtotime($vrac->date_signature));  ?>}
\end{center}
\end{minipage}
\begin{minipage}[t]{0.3\textwidth}
<?php if ($vrac->mandataire_identifiant): ?>
\begin{center}
Le Courtier,\\
Signé électroniquement, le \textbf{<?php echo ($vrac->valide->date_signature_courtier)? date("d/m/Y", strtotime($vrac->valide->date_signature_courtier)) : date("d/m/Y", strtotime($vrac->date_signature)); ?>}
\end{center}
<?php else: ?>
~ \\
<?php endif; ?>
\end{minipage}

\newpage

\includegraphics[scale=0.95]{<?php echo sfConfig::get('sf_web_dir'); ?>/pdf/_annexe_bouteille.pdf}

\end{document}

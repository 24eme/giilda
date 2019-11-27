<?php
use_helper('Display');
use_helper('DRMPdf');
 ?>
\documentclass[a4paper, 10pt]{letter}
\usepackage[utf8]{inputenc}
\usepackage[T1]{fontenc}
\usepackage[francais]{babel}
\usepackage[top=1cm, bottom=0.5cm, left=1cm, right=1cm, headheight=2cm, headsep=0mm, marginparwidth=0cm]{geometry}
\usepackage{fancyhdr}
\usepackage{graphicx}
\usepackage[table]{xcolor}
\usepackage{units}
\usepackage{fp}
\usepackage{tikz}
\usepackage{array}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{multicol}\usepackage{truncate}
\usepackage{colortbl}
\usepackage{tabularx}
\usepackage{multirow}
\usepackage{amssymb}
\usepackage[framemethod=tikz]{mdframed}

\newcommand{\squareChecked}{\makebox[0pt][l]{$\square$}\raisebox{.15ex}{\hspace{0.1em}$\checkmark$}}

\newcommand{\emptybox}[2][\textwidth]{%
  \begingroup
  \setlength{\fboxsep}{-\fboxrule}%
  \noindent\framebox[#1]{\rule{0pt}{#2}}%
  \endgroup
}
<?php
$sepa = $societe->getSepaSociete();
$countries = ConfigurationClient::getInstance()->getCountryList();
$adresse_interloire = getAdresseInterloire();
$ics_interloire = sfConfig::get('app_teledeclaration_ics',"");
$m_adresse = array();
preg_match("/^(.+) - (.+)/",$adresse_interloire,$m_adresse);

 ?>
\def\NomInterpro{Interprofession des Vins du Val de Loire}
\def\RUM{<?php echo $societe->getRum(); ?>}
\def\NomBancaire{<?php echo $sf_user->getAttribute('nom_bancaire','~'); ?>}
\def\Adresse{\small{<?php $adresse = ($societe->getSiegeAdresses() == '')? "~" : html_entity_decode(str_replace(";", "",$societe->getSiegeAdresses()));
                                                 echo $adresse;
                   $adresseComplementaire = ($societe->siege->exist("adresse_complementaire") && $societe->siege->adresse_complementaire)?  "~-~".html_entity_decode($societe->siege->adresse_complementaire) : "~";
                                                  echo $adresseComplementaire;   ?>}}
\def\CodePostal{<?php echo $societe->siege->code_postal; ?>}
\def\Ville{<?php echo $societe->siege->commune; ?>}
\def\Pays{<?php echo $countries[$societe->siege->pays]; ?>}
\def\IBAN{<?php echo formatIban($sf_user->getAttribute('iban','~')); ?>}
\def\BIC{<?php echo $sf_user->getAttribute('bic','~'); ?>}


\def\ICSCreancier{<?php echo $ics_interloire; ?>}
\def\AdresseCreancier{<?php echo $m_adresse[1]; ?>}
\def\CPVilleCreancier{<?php echo $m_adresse[2]." - France"; ?>}


\begin{document}

Madame, Monsieur, \\

Nous vous prions de trouver ci-dessous un mandat de prélèvement SEPA que nous vous prions de compléter et renvoyer signé accompagné d'un relevé d'identité bancaire.\\

Nous vous prions d'agréer Madame, Monsieur, nos salutations distinguées.\\

\begin{tabularx}{\textwidth}{|XXX|}
	\hline
	~ & ~ & ~ \\
	~ & \multicolumn{2}{c|}{
		\multirow{1}{*}{\includegraphics[scale=0.25]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo_vrac_pdf2.jpg"; ?>}
		}
	} \\
	\multicolumn{1}{|c}{
		\multirow{1}{*}{
			\textbf{Mandat de prélèvement SEPA}
		}
	} & ~ & ~
	 \\
	\multicolumn{1}{|c}{
		\multirow{2}{*}{
			\textbf{~}
			}
		} & ~  & ~ \\
	~ & ~  & ~\\
	~ & ~  & ~\\
	\hline
	~ & ~  & ~\\
	\underline{\textbf{Référence Unique du Mandat :}} & \multicolumn{2}{l|}{ \textbf{\RUM} } \\
	~ & ~  & ~ \\
	\hline
	~ & ~  & ~\\
	\multicolumn{3}{|l|}{
	\parbox{18.5cm}{En signant ce formulaire de mandat, vous autorisez (A) l'\NomInterpro à envoyer des instructions à votre banque pour
débiter votre compte, et (B) votre banque à débiter votre compte conformément aux instructions de l'\NomInterpro.} }\\
~ & ~  & ~\\
\multicolumn{3}{|l|}{
\parbox{18.5cm}{
Vous bénéficiez du droit d’être remboursé par votre banque suivant les conditions décrites dans la convention que vous avez passée
avec elle. \\~\\
Une demande de remboursement doit être présentée :\\
~~~~- dans les 8 semaines suivant la date de débit de votre compte pour un prélèvement autorisé.\\
~~~~- sans tarder et au plus tard dans les 13 mois en cas de prélèvements non autorisé.
}}
\\
~ & ~  & ~\\
\multicolumn{3}{|l|}{
\parbox{18.5cm}{\underline{Après réception et saisie de ces informations dans notre logiciel, nous vous avertirons par e-mail de l’activation de}\\ \underline{votre mandat de prélèvement et de votre prochaine date d’échéance.}} }\\
~ & ~  & ~\\
\hline
~ & ~  & ~\\
\textbf{Nom ou raison sociale} & \multicolumn{2}{l|}{ \textbf{\NomBancaire} } \\
\textbf{Adresse} & \multicolumn{2}{l|}{ \Adresse } \\
~ & \multicolumn{2}{l|}{\CodePostal~~~~\Ville} \\
\textbf{Pays} & \multicolumn{2}{l|}{ \Pays } \\
~ & ~  & ~\\
\textbf{Iban} & \multicolumn{2}{l|}{
		\multirow{1}{*}{
      \textbf{\IBAN}
		}
	} \\
\textbf{Bic} & \multicolumn{2}{l|}{
		\multirow{1}{*}{
      \textbf{\BIC}
		}
	} \\
~ & \multicolumn{2}{l|}{ ~ } \\
~ & ~ & ~ \\
\textbf{Identification du créancier} & Interloire & ~ \\
\textbf{I.C.S} & \ICSCreancier & ~ \\
\textbf{Adresse} & \AdresseCreancier & ~ \\
~ & \multicolumn{2}{l|}{\CPVilleCreancier} \\
~ & ~ & ~ \\
\textbf{Type de paiement :} & SEPA récurrent & ~ \\
~ & ~ & ~ \\
\textbf{Fait à} & \dotfill & \multicolumn{1}{l|}{
		\textbf{le}~~~\multirow{1}{*}{\includegraphics[scale=0.25]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/images/pictos")."/date_field.png" ?>}
		}} \\
~ & $~~~^{\textrm{\textcolor{darkgray}{\tiny{Lieu}}}}$  & $~~~^{\textrm{\textcolor{darkgray}{\tiny{Date JJ MM AAAA}}}}$ \\
~ & ~ & ~ \\
\textbf{Signature :}  & \multicolumn{2}{l|}{
\multirow{1}{*}{
\emptybox[11.5cm]{2.0cm}
}
} \\
~ & ~ & ~ \\
~ & ~ & ~ \\
~ & ~ & ~ \\
~ & $~~~^{\textrm{\textcolor{darkgray}{\tiny{Veuillez signer ici}}}}$ & ~ \\
\multicolumn{3}{|l|}{\footnotesize{Note : Vos droits concernant le présent mandat sont expliqués dans un document que vous pouvez obtenir auprès de votre banque.}} \\
\hline
~ & ~ & ~ \\
~ & ~ & ~ \\
~ & ~ & ~ \\
\hline
\multicolumn{1}{|l|}{ \textbf{A retourner à :} } & \multicolumn{2}{|c|}{ \textbf{Zone réservée à l'usage exclusif du créancier} } \\
\multicolumn{1}{|l|}{Interloire} & \multicolumn{2}{|c|}{} \\
\multicolumn{1}{|l|}{Service Recouvrement} & \multicolumn{2}{|c|}{} \\
\multicolumn{1}{|l|}{\AdresseCreancier} & \multicolumn{2}{|c|}{} \\
\multicolumn{1}{|l|}{\CPVilleCreancier} & \multicolumn{2}{|c|}{} \\
\hline
\end{tabularx}
\end{document}

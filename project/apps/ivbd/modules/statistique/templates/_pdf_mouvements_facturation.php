<?php
setlocale(LC_TIME, 'fr_FR');
$items = explode(PHP_EOL, $csv);
$headers = array_shift($items);
// $nbPage = 0;
// foreach ($items as $item) {
// $values = explode(';', $item);
// if (!$values[0] || preg_match('/total/i', $values[0])) { continue; }
// $headers[$values[0]] = $values[0];
// }
// $options = $options->getRawValue();
// $periode = (isset($options['periode']) && isset($options['periode'][0]) && isset($options['periode'][1]))? $options['periode'] : null;
?>
\documentclass[a4paper, landscape, 10pt]{article}
\usepackage[utf8]{inputenc}
\usepackage[top=2.3cm, bottom=1.8cm, left=0.5cm, right=0.5cm, headheight=2cm, headsep=0.5cm, marginparwidth=0cm]{geometry}
\usepackage{fancyhdr}
\usepackage{graphicx}
\usepackage{multicol}
\usepackage{colortbl}
\usepackage{tabularx}
\usepackage{multirow}
\usepackage[framemethod=tikz]{mdframed}
\usepackage{lastpage}

\def\LOGO{<?php echo sfConfig::get('sf_web_dir'); ?>/images/logo_ivbd.png}
\renewcommand{\arraystretch}{1.2}
\makeatletter
\setlength{\@fptop}{5pt}
\makeatother


\fancyhf{}
\renewcommand{\headrulewidth}{0cm}
\renewcommand\sfdefault{phv}
\renewcommand{\familydefault}{\sfdefault}
\fancyfoot[R]{\thepage~/~\pageref{LastPage}}
\fancyfoot[L]{<?php echo strftime("%e %B %Y", time()) ?>}
\fancyhead[L]{\includegraphics[scale=0.6]{\LOGO}}


\begin{document}

\pagestyle{fstyle_0}

\begin{table}[ht!]
\begin{tabularx}{\linewidth}{ | X | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | >{\raggedleft}p{0.05\linewidth} | }
\hline
\rowcolor{gray!40} ~ & \multicolumn{6}{c|}{Sorties (hl)} & \multicolumn{1}{c|}{Sorties} & \multicolumn{1}{c|}{Mouvements} & \multicolumn{1}{c|}{Total} \tabularnewline
\cline{2-7}
\rowcolor{gray!40} \multicolumn{1}{|c@{}|}{ Produits } & \multicolumn{1}{c|}{Contrats} & \multicolumn{5}{c|}{Hors contrats} & \multicolumn{1}{c|}{réelles pour} & \multicolumn{1}{c|}{exonérées} & \multicolumn{1}{c|}{mouvements (hl)} \tabularnewline
\cline{2-7}
\rowcolor{gray!40}  & \multicolumn{1}{c|}{01. Vrac} & \multicolumn{1}{c|}{Total sorties} & \multicolumn{3}{c|}{Entrées} & \multicolumn{1}{c|}{Facturation} & \multicolumn{1}{c|}{facturation (hl)} &  \multicolumn{1}{c|}{de cvo (hl)} & ~  \tabularnewline
\cline{4-6}
\rowcolor{gray!40}  & \multicolumn{1}{c|}{sous contrat} & \multicolumn{1}{c|}{hors contrat} & \multicolumn{1}{c|}{06. Retour} & \multicolumn{1}{c|}{08. Retour} & \multicolumn{1}{c|}{09. Retour} & \multicolumn{1}{c|}{cvo hors} & ~ & ~ & ~ \tabularnewline
\rowcolor{gray!40}  &  &  & \multicolumn{1}{c|}{logement} & \multicolumn{1}{c|}{de vin CRD} & \multicolumn{1}{c|}{de vin hors} & \multicolumn{1}{c|}{contrat} & ~ & ~ & ~ \tabularnewline
\rowcolor{gray!40}  &  &  & \multicolumn{1}{c|}{ext} &  & \multicolumn{1}{c|}{CRD} &  &  &  &  \tabularnewline
<?php
	$i = 1;
	foreach ($items as $item):
    $values = explode(';', $item);
		$isTotal = preg_match('/total/i', $item);?>
		<?php if ($isTotal): ?>\rowcolor{gray!40} <?php endif; echo implode(' & ', $values); ?> <?php if (!$isTotal): ?>\tabularnewline <?php endif; ?>
<?php  endforeach;?>
\end{tabularx}
\end{table}

\end{document}

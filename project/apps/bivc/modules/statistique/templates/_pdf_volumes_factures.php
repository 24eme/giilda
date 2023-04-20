<?php
setlocale(LC_TIME, 'fr_FR');
$items = explode(PHP_EOL, $csv);
array_shift($items);
$maxTableRowsPerPage = 30;
$nbPage = 0;
$options = $options->getRawValue();
$periode = (isset($options['periode']) && isset($options['periode'][0]) && isset($options['periode'][1]))? $options['periode'] : null;
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
\fancyhead[L]{\includegraphics[scale=0.3]{<?php echo sfConfig::get('sf_web_dir'); ?>/images/logo_bivc.png}}
\fancypagestyle{fstyle_0}{
\fancyhead[C]{Volumes facturés<?php if ($periode): ?>\\Période du \textbf{<?php echo $periode[0] ?>} au \textbf{<?php echo $periode[1] ?>}<?php endif; ?>}
}

\begin{document}

\pagestyle{fstyle_0}

\begin{table}[ht!]
\begin{tabularx}{\linewidth}{ | X | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | }
\hline
\rowcolor{gray!40} Produit & \multicolumn{1}{c |}{CVO} & \multicolumn{1}{c |}{Volume} & \multicolumn{1}{c |}{Total} \tabularnewline \hline
<?php
	$i = ($compare)? 2 : 1;
	foreach ($items as $item):
		$item = sfOutputEscaper::unescape($item);
		$values = explode(';', $item);
?>
<?php 
	if ($i == $maxTableRowsPerPage): 
	$newSection = true;
?>
\end{tabularx}
\end{table}
\clearpage
\pagestyle{fstyle_0}
\begin{table}[ht!]
\begin{tabularx}{\linewidth}{ | X | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | }
<?php if ($newSection): ?>
\hline
\rowcolor{gray!40} Produit & \multicolumn{1}{c |}{CVO} & \multicolumn{1}{c |}{Volume} & \multicolumn{1}{c |}{Total} \tabularnewline \hline
<?php endif; ?>
<?php $i=0; else: $i++;endif; ?>
<?php if ($values[0] == 'TOTAL'): ?>
\hline
\rowcolor{gray!40}
<?php endif; ?>
<?php echo implode(' & ', $values); ?> \tabularnewline \hline
<?php  endforeach;?>
\end{tabularx}
\end{table}

\end{document}
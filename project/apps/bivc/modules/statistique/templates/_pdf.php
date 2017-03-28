<?php
setlocale(LC_ALL, 'fr_FR');
$items = explode(PHP_EOL, $csv);
array_shift($items);
$headers = array();
$maxTableRowsPerPage = 30;
$nbPage = 0;
foreach ($items as $item) {
$values = explode(';', $item);
if (!$values[0] || preg_match('/total/i', $values[0])) { continue; }
$headers[$values[0]] = $values[0];
}
$periode = (isset($options['periode']))? $options['periode'] : null;
$compare = (isset($options['compare']))? $options['compare'] : false;
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

\def\LOGO{<?php echo sfConfig::get('sf_web_dir'); ?>/images/logo_bivc.png}
\renewcommand{\arraystretch}{1.2}
\makeatletter
\setlength{\@fptop}{5pt}
\makeatother


\fancyhf{}
\renewcommand{\headrulewidth}{0cm}
\renewcommand\sfdefault{phv}
\renewcommand{\familydefault}{\sfdefault}
\fancyfoot[R]{\thepage /  \pageref{LastPage}}
\fancyfoot[L]{<?php echo strftime("%e %B %Y", mktime()) ?>}
\fancyhead[L]{\includegraphics[scale=0.6]{\LOGO}}

<?php $i=0; foreach ($headers as $header): ?>
\fancypagestyle{fstyle_<?php echo $i ?>}{
\fancyhead[C]{Volume exporté par pays et par couleur pour l'appellation \textbf{<?php echo $header ?>}<?php if ($periode): ?>\\Période du \textbf{<?php echo $periode[0] ?>} au \textbf{<?php echo $periode[1] ?>}<?php endif; ?>}
}
<?php $i++; endforeach; ?>

\begin{document}

<?php include_partial('statistique/pdf_'.$type, array('items' => $items, 'compare' => $compare, 'maxTableRowsPerPage' => $maxTableRowsPerPage)); ?>
 
\end{document} 


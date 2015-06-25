\documentclass[a4paper,oneside, landscape, 9pt]{article}

\usepackage[english]{babel}
\usepackage[utf8]{inputenc}
\usepackage{units}
\usepackage{graphicx}
\usepackage{fp}
\usepackage[table]{xcolor}
\usepackage{lscape}
\usepackage{tikz}
\usepackage{array,multirow,makecell}
\usepackage{multicol}
\usepackage{textcomp}
\usepackage{marvosym}
\usepackage{lastpage}
\usepackage{truncate}
\usepackage{fancyhdr}
\usepackage{lastpage}

\usetikzlibrary{fit}

\renewcommand\sfdefault{phv}

\renewcommand{\familydefault}{\sfdefault}
\renewcommand{\TruncateMarker}{\small{...}}

\usepackage{array}
\newcolumntype{L}[1]{>{\raggedright\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}
\newcolumntype{C}[1]{>{\centering\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}
\newcolumntype{R}[1]{>{\raggedleft\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}


\setlength{\oddsidemargin}{-2cm}
\setlength{\evensidemargin}{-2cm}
\setlength{\textwidth}{29.7cm}
\setlength{\textheight}{21cm}
\setlength{\headheight}{4cm}
\setlength{\headwidth}{28.2cm}
\setlength{\topmargin}{-4cm}


<?php include_partial('drm_pdf/generateEnteteTex', array('drm' => $drm)); ?>
\begin{document}
<?php include_partial('drm_pdf/generateRecapMvtTex', array('drm' => $drm)); ?>
<?php include_partial('drm_pdf/generateAnnexeCRDTex', array('drm' => $drm)); ?>
\end{document}


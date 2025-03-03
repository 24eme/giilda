<?php
use_helper('Date');
use_helper('DRMPdf');
use_helper('Display');

$recapCvos = DRMClient::getInstance()->getRecapCvosFromView($drm);

?>

~ \\
\hspace{-0.5cm}
\vspace{0.5cm}
\begin{tabular}{C{43mm}|C{43mm}|C{43mm}|C{43mm}|C{43mm}|<?php if($recapCvos["TOTAL"]->totalVolumeReintegration): ?>C{43mm}|<?php endif; ?>}
\multicolumn{<?php if($recapCvos["TOTAL"]->totalVolumeReintegration): ?>6<?php else: ?>5<?php endif; ?>}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{CVO}}}}
\\
\hline
\rowcolor{lightgray}
\multicolumn{1}{|C{43mm}}{\small{\textbf{Libellé}}} &
\multicolumn{1}{|C{43mm}|}{\small{\textbf{Volume}}} &
<?php if($recapCvos["TOTAL"]->totalVolumeReintegration) : ?>
\multicolumn{1}{|C{43mm}|}{\small{\textbf{Réintégré}}} &
<?php endif; ?>
\multicolumn{1}{|C{43mm}|}{\small{\textbf{Taux}}} &
\multicolumn{1}{|C{43mm}|}{\small{\textbf{Total~HT}}} &
\multicolumn{1}{|C{43mm}|}{\small{\textbf{Total~TTC}}}
\\
\hline
\multicolumn{1}{|l}{\small{\textbf{CVO Totale}}} &
\multicolumn{1}{|r|}{\small{\textbf{<?php echo sprintFloat($recapCvos["TOTAL"]->totalVolumeDroitsCvo, "%01.04f").' hl';  ?>}}} &
<?php if($recapCvos["TOTAL"]->totalVolumeReintegration) : ?>
\multicolumn{1}{|r|}{\small{\textbf{<?php echo sprintFloat($recapCvos["TOTAL"]->totalVolumeReintegration, "%01.04f").' hl';  ?>}}} &
<?php endif; ?>
\multicolumn{1}{|r|}{\small{\textbf{<?php echo sprintFloat($recapCvos["TOTAL"]->totalCvo / $recapCvos["TOTAL"]->nbMvt).' hl';  ?>}}} &
\multicolumn{1}{|r|}{\small{\textbf{<?php echo sprintFloat($recapCvos["TOTAL"]->totalPrixDroitCvo).' €'; ?>}}} &
\multicolumn{1}{|r|}{\small{\textbf{<?php echo sprintFloat($recapCvos["TOTAL"]->totalPrixDroitCvoTTC).' €'; ?>}}}
\\
\hline
\end{tabular}
~ \\
~ \\

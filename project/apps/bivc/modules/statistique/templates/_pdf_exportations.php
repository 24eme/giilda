<?php $fstyle = 0; ?>

\pagestyle{fstyle_<?php echo $fstyle ?>}

\begin{table}[ht!]
<?php if ($compare): ?>
\begin{tabularx}{\linewidth}{ | X | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | }
\hline
\rowcolor{gray!40} & \multicolumn{3}{c |}{Blanc} & \multicolumn{3}{c |}{Rosé} & \multicolumn{3}{c |}{Rouge} & \multicolumn{3}{c |}{Total} \tabularnewline
\rowcolor{gray!40} Pays & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} \tabularnewline \hline
<?php else: ?>
\begin{tabularx}{\linewidth}{ | X | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | }
\hline
\rowcolor{gray!40} Pays & \multicolumn{1}{c |}{Blanc} & \multicolumn{1}{c |}{Rosé} & \multicolumn{1}{c |}{Rouge} & \multicolumn{1}{c |}{Total} \tabularnewline \hline
<?php endif; ?>
<?php 
	$i = ($compare)? 2 : 1;
	$page = null;
	foreach ($items as $item):
		$values = explode(';', $item);
		if (!$values[0]) {
			continue;
		}
		$isTotal = preg_match('/total/i', $item);
		$current = $values[0];
		if (!$page) {
			$page = $values[0];
		}
		unset($values[0]);
?>
<?php 
	if ($i == 30 || ($page != $current && !preg_match('/total/i', $current))): 
	$newSection = false;
	if ($page != $current) {
		$fstyle++;
		$page = $current;
		$newSection = true;
	}
?>
\end{tabularx}
\end{table}
\newpage
\pagestyle{fstyle_<?php echo $fstyle ?>}
\begin{table}[ht!]
<?php if ($compare): ?>
\begin{tabularx}{\linewidth}{ | X | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.061\linewidth} | >{\raggedleft}p{0.028\linewidth} | }
<?php if ($newSection): ?>
\hline
\rowcolor{gray!40} & \multicolumn{3}{c |}{Blanc} & \multicolumn{3}{c |}{Rosé} & \multicolumn{3}{c |}{Rouge} & \multicolumn{3}{c |}{Total} \tabularnewline
\rowcolor{gray!40} Pays & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} & \multicolumn{1}{c |}{N} & \multicolumn{1}{c |}{N-1} & \multicolumn{1}{c |}{\%} \tabularnewline
<?php endif; ?>
<?php else: ?>
\begin{tabularx}{\linewidth}{ | X | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | >{\raggedleft}p{0.1\linewidth} | }
<?php if ($newSection): ?>
\hline
\rowcolor{gray!40} Pays & \multicolumn{1}{c |}{Blanc} & \multicolumn{1}{c |}{Rosé} & \multicolumn{1}{c |}{Rouge} & \multicolumn{1}{c |}{Total} \tabularnewline
<?php endif; ?>
<?php endif; ?>
\hline
<?php $i=($newSection)? ($compare)? 2 : 1 : 0; else: $i++;endif; ?>
<?php if (preg_match('/total/i', $current)): ?>\hline<?php endif; ?><?php if ($isTotal): ?>\rowcolor{gray!40} <?php endif; if (preg_match('/total/i', $current)) {unset($values[1]); echo 'TOTAL général & '; } echo implode(' & ', $values); ?> \tabularnewline \hline
<?php  endforeach;?>
\end{tabularx}
\end{table}
<?php
use_helper('Float');
?>
~~~~<?php echo $produit->produit_libelle.' \textbf{\begin{tiny}'.$produit->origine_libelle.'\end{tiny}}'; ?> &
                            \multicolumn{1}{r|}{<?php echoArialFloat($produit->volume*-1); ?>} &
                            \multicolumn{1}{r|}{<?php echoArialFloat($produit->cotisation_taux); ?>} & 
                            \multicolumn{1}{r|}{<?php echoArialFloat($produit->montant_ht); ?>} & 
                            \multicolumn{2}{c}{<?php echo $produit->echeance_code; ?>}\\
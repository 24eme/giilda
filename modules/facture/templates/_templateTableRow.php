<?php
use_helper('Float');
$produit->origine_libelle = str_replace("&#039;", "'", $produit->origine_libelle);    
?>
~~~~\truncate{124mm}{\small{\textbf{<?php echo $produit->produit_libelle.'} '.str_replace("&", "\&", $produit->origine_libelle); ?> }} &
                            \multicolumn{1}{r|}{\small{<?php echoArialFloat($produit->volume*-1); ?>}} &
                            \multicolumn{1}{r|}{\small{<?php echoArialFloat($produit->cotisation_taux); ?>}} & 
                            \multicolumn{1}{r|}{\small{<?php echoArialFloat($produit->montant_ht); ?>}}&
                            \multicolumn{2}{c}{\small{<?php echo $produit->echeance_code; ?>}} \\
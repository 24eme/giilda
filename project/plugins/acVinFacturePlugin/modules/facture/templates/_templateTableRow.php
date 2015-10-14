<?php
use_helper('Float');
use_helper('Display');
$produit->libelle = str_replace("&#039;", "'", $produit->libelle);    
?>
~~~~\truncate{124mm}{\small{\textbf{<?php echo $produit->libelle.'} '.escape_string_for_latex($produit->libelle); ?> }} &
                            \multicolumn{1}{r|}{\small{<?php echoArialFloat($produit->quantite); ?>}} &
                            \multicolumn{1}{r|}{\small{<?php echoArialFloat($produit->prix_unitaire); ?>}} & 
                            \multicolumn{1}{r|}{\small{<?php echoArialFloat($produit->montant_ht); ?>}}&
                            \multicolumn{2}{c}{\small{<?php //echo $produit->echeance_code; ?>}} \\
<?php
use_helper('Date');
use_helper('Display');

$coordonneesBancaires = $facture->getCoordonneesBancaire();
$infosInterpro = $facture->getInformationsInterpro();
?>
\def\InterproAdresse{<?php echo $facture->emetteur->adresse; ?> \\
		       <?php echo $facture->emetteur->code_postal.' '.$facture->emetteur->ville; ?>}
\def\InterproContact{\\<?php echo $facture->emetteur->telephone;?>
                                             <?php if($facture->emetteur->exist('email') && $facture->emetteur->email): ?>
                                                    \\ Email : <?php echo $facture->emetteur->email; ?>
                                              <?php endif;?>}

\def\FactureReglement{ <?php echo $factureConfiguration->getReglement(); ?> }
\def\TVA{19.60}
\def\FactureNum{<?php echo $facture->numero_piece_comptable; ?>}
\def\FactureDate{<?php echo format_date($facture->date_facturation,'dd/MM/yyyy'); ?>}
\def\FactureRefClient{<?php echo $facture->numero_adherent; ?><?php if($factureConfiguration->getPdfDiplayCodeComptable()): ?> / <?php echo $facture->code_comptable_client ?><?php endif ?>}
\def\FactureRefCodeComptableClient{<?php echo ($factureConfiguration->getPdfDiplayCodeComptable())? $facture->code_comptable_client : $facture->numero_adherent; ?>}
\newcommand{\CutlnPapillon}{
  	\multicolumn{2}{|c|}{ ~~~~~~~~~~~~~~~~~~~~~~~ } &
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\
}

\newcommand{\CutlnPapillonEntete}{
      &  &  \multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\
}

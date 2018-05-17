<?php
use_helper('Date');
use_helper('Display');

?>
\def\InterproAdresse{<?php echo $facture->emetteur->adresse; ?> \\
		       <?php echo $facture->emetteur->code_postal.' '.$facture->emetteur->ville; ?> - France}
\def\InterproContact{\\Votre contact : <?php echo $facture->emetteur->service_facturation.' - '. $facture->emetteur->telephone;?>
											 <?php if($facture->emetteur->exist('email')): ?>
											        \\ Email : <?php echo $facture->emetteur->email; ?>
											 <?php endif;?>}

\def\FactureReglement{ <?php echo FactureConfiguration::getInstance()->getReglement(); ?> }
\def\TVA{19.60}
\def\FactureNum{<?php echo $facture->numero_interloire; ?>}
\def\FactureDate{<?php echo format_date($facture->date_facturation,'dd/MM/yyyy'); ?>}
\def\NomRefClient{<?php echo $facture->identifiant; ?>}
\def\FactureRefClient{<?php echo $facture->identifiant; ?>}
\def\FactureRefCodeComptableClient{<?php echo (FactureConfiguration::getInstance()->getPdfDiplayCodeComptable())? $facture->code_comptable_client : $facture->numero_adherent; ?>}
\newcommand{\CutlnPapillon}{
  	\multicolumn{3}{c}{\Rightscissors \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline  \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline \Cutline}
\\
}

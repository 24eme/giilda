<?php
use_helper('DRMPdf');
use_helper('Display');
?>

\def\InterloireAdresse{<?php echo getAdresseInterloire(); ?>} 
\def\InterloireContact{<?php echo getDrmContact($drm); ?>} 

\def\DRMSocieteRaisonSociale{<?php echo $drm->societe->raison_sociale; ?>}
\def\DRMSocieteAdresse{<?php echo getDrmSocieteAdresse($drm); ?>}   

\def\DRMAdresseChai{<?php echo getDrmEtablissementAdresse($drm); ?>}   
\def\DRMAdresseCompta{} 

\def\DRMNumAccise{<?php echo $drm->declarant->no_accises; ?>} 
\def\DRMCvi{<?php echo $drm->declarant->cvi; ?>}              

\def\DRMSiret{<?php echo $drm->societe->siret; ?>}
\def\DRMIdentifiantIL{<?php echo $drm->identifiant; ?>}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}
\fancyhf{}

\lhead{
Raison sociale : \textbf{\DRMSocieteRaisonSociale} \\
Adresse du siège de l’Entrepôt : \textbf{\DRMAdresseChai} \\
Numéro identification Interloire : \textbf{\DRMIdentifiantIL} \\
CVI : \textbf{\DRMCvi} \\
Siret/Siren : \textbf{\DRMSiret} \\
Numéro d'Accise : \textbf{\DRMNumAccise} \\
 }
 
\rhead{\includegraphics[scale=1]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo_new.jpg"; ?>}  \\
\vspace{-2cm}
\InterloireAdresse
 \begin{small} \textbf{\InterloireContact} \\ 
 \end{small}  
 }
 
\rfoot{page \thepage\ / \pageref{LastPage}}

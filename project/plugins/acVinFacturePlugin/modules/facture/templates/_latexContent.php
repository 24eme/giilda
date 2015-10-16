<?php
$avoir = ($facture->total_ht <= 0);
include_partial('facture/templateEntete', array('facture' => $facture));
?>
\begin{document}
<?php
include_partial('facture/templateTitreAdresse', array('facture' => $facture, 'avoir' => $avoir));
include_partial('facture/templateNumPage', array('nb_page' => $nb_pages));
include_partial('facture/templateHeadTable');
$line_nb_current_page = FactureLatex::NB_LIGNES_ENTETE * ($nb_pages > 1);
$current_avg_nb_lines_per_page = floor($nb_lines / $nb_pages);
$max_line_nb_current_page = FactureLatex::MAX_LIGNES_PERPAGE - FactureLatex::NB_LIGNES_ENTETE;
$current_total_line_nb = 0;
$current_nb_pages = 0;
foreach ($facture->lignes as $type => $typeLignes) {
    $line_nb_current_page++;
    ?>
    \small{\textbf{<?php echo format_date($facture->date_emission, 'dd/MM/yyyy'); ?> }} &
    \small{\textbf{<?php echo $typeLignes->libelle; ?> }} &
    \multicolumn{1}{r|}{~} &
    \multicolumn{1}{r|}{~} &
    \multicolumn{1}{r}{~}
    \\ 
    <?php
    foreach ($typeLignes->details as $prodHash => $produit) {
        include_partial('facture/templateTableRow', array('produit' => $produit->getRawValue(), 'facture' => $facture));
        $line_nb_current_page++;
        if ($line_nb_current_page > $current_avg_nb_lines_per_page || $line_nb_current_page >= $max_line_nb_current_page) {
            include_partial('facture/templateEndTableWithMention', array('add_blank_lines' => ($max_line_nb_current_page - $line_nb_current_page), 'avoir' => $avoir));
            echo "\\newpage\n";
            include_partial('facture/templateNumPage', array('nb_page' => $nb_pages));
            include_partial('facture/templateHeadTable');
            $current_total_line_nb += $line_nb_current_page;
            $line_nb_current_page = 0;
            $current_nb_pages++;
            $max_line_nb_current_page = FactureLatex::MAX_LIGNES_PERPAGE;
            $current_avg_nb_lines_per_page = ($nb_lines - $current_total_line_nb) / ($nb_pages - $current_nb_pages);
        }
    }
}
$nb_blank = FactureLatex::MAX_LIGNES_PERPAGE - $line_nb_current_page - FactureLatex::NB_LIGNES_REGLEMENT;
$nb_echeances = count($facture->getEcheances());
if ($nb_echeances)
    $nb_blank += - FactureLatex::NB_LIGNES_PAPILLONS_PAR_ECHEANCE * $nb_echeances - FactureLatex::NB_LIGNES_PAPILLONS_FIXE;
if (!$current_nb_pages)
    $nb_blank -= FactureLatex::NB_LIGNES_ENTETE;

include_partial('facture/templateEndTableWithMention', array('add_blank_lines' => $nb_blank, 'end_document' => true, 'avoir' => $avoir));
include_partial('facture/templateReglement', array('facture' => $facture, 'avoir' => $avoir));
if ($nb_echeances)
    include_partial('facture/templateEcheances', array('echeances' => $facture->getEcheances(), 'societe' => $facture->getSociete()));
?>
\end{document}

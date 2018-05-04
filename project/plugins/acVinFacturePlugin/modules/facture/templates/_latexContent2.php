<?php
use_helper('Float');
use_helper('Display');
$avoir = ($facture->total_ht <= 0);
include_partial('facture/templateEntete', array('facture' => $facture)); ?>
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

// foreach ($facture->lignes as $type => $typeLignes) {
//   include_partial('facture/templateTableTypeRow', array('type' => $type));
//   $line_nb_current_page++;
//   $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);
//   foreach ($produits as $prodHash => $p) {
//     foreach ($p as $produit) {
//       include_partial('facture/templateTableRow', array('produit' => $produit->getRawValue()));
//       $line_nb_current_page++;
//       if ($line_nb_current_page > $current_avg_nb_lines_per_page || $line_nb_current_page >= $max_line_nb_current_page) {
// 	include_partial('facture/templateEndTableWithMention', array('add_blank_lines' => ($max_line_nb_current_page - $line_nb_current_page), 'avoir' => $avoir));
	// echo "\\newpage\n";
include_partial('facture/templateNumPage', array('nb_page' => $nb_pages));
?>
\centering
\fontsize{8}{10}\selectfont
    \begin{tikzpicture}
		\node[inner sep=1pt] (tab1){
                        \renewcommand{\arraystretch}{1.2}
			\begin{tabular}{p{125mm} |p{12mm}|p{12mm}|p{18mm}|p{6mm}p{0mm}}
  			\rowcolor{lightgray}
                        \centering \small{\textbf{Libellé}} &
   			\centering \small{\textbf{Volume en hl}} &
                        \centering \small{\textbf{C.V.O. en \texteuro{}/hl}} &
   			\centering \small{\textbf{Montant HT en \texteuro{}}} &
   			\centering \small{\textbf{Code Ech.}} &
   			 \\
  			\hline
                        ~ & ~ & ~ & ~ & ~ &\\
<?php
    //Pour chaque ligne de facture :
    foreach ($facture->lignes as $type => $typeLignes) {
        $line_nb++;
        $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);
        foreach ($produits as $prodHash => $p) {
          foreach ($p as $produit) {
              include_partial('facture/templateTableRow', array('produit' => $produit->getRawValue()));

            for( ; $line_nb <= FactureLatex::MAX_LIGNES_PERPAGE - 1; $line_nb++): ?>
            ~ & ~ & ~ & ~ &\\
      <?php endfor; ?>

	$current_total_line_nb += $line_nb_current_page;
	$line_nb_current_page = 0;
	$current_nb_pages++;
	$max_line_nb_current_page = FactureLatex::MAX_LIGNES_PERPAGE;
        $current_avg_nb_lines_per_page = ($nb_lines - $current_total_line_nb) / ($nb_pages - $current_nb_pages);
      }
    }
  }
}
$nb_blank = FactureLatex::MAX_LIGNES_PERPAGE - $line_nb_current_page - FactureLatex::NB_LIGNES_REGLEMENT;
$nb_echeances = count($facture->echeances);
if ($nb_echeances)
  $nb_blank +=  - FactureLatex::NB_LIGNES_PAPILLONS_PAR_ECHEANCE * $nb_echeances - FactureLatex::NB_LIGNES_PAPILLONS_FIXE;
if (!$current_nb_pages)
  $nb_blank -=  FactureLatex::NB_LIGNES_ENTETE;

include_partial('facture/templateEndTableWithMention', array('add_blank_lines' => $nb_blank, 'end_document' => true, 'avoir' => $avoir));




include_partial('facture/templateReglement', array('facture' => $facture, 'avoir' => $avoir));
if ($nb_echeances)
  include_partial('facture/templateEcheances', array('echeances' => $facture->echeances));
?>
\end{document}

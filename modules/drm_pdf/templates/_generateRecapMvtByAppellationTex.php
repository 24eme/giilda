\quad{\setlength{\extrarowheight}{1pt}
\begin{center}
      \begin{large}
      \textbf{DRM en Droits <?php echo $libelleDetail; ?>}
      \end{large}
\end{center}
<?php
$entete = '\begin{tabular}{C{68mm} |';
$size_col = 30;
$nb_appellations = count($produitsDetailsByCertifications->produitsByAppellation) ;

foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit){
  $entete .='C{' . $size_col . 'mm}|';
}
$entete .='}';
$maxCol = 1 + count($produitsDetailsByCertifications->produitsByAppellation);
?>
    <?php
    /*
     * Début du Tabular
     */
    ?>
    <?php echo $entete; ?>


    <?php
    /*
     * Entête des Produits
     */
    ?>
    \cline{2-<?php echo $maxCol; ?>}
    &
    <?php
    $counter = 0;
      foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit): ?>
        \multicolumn{1}{>{\columncolor[rgb]{0,0,0}}C{<?php echo $size_col; ?>mm}|}{ \small{\color{white}{\textbf{<?php echo  $libelleAppellation; ?>}}}}
        <?php echo ($counter < $nb_appellations - 1) ? "&" : '';  $counter++;  ?>
    <?php endforeach; ?>
    \\
    \hline

    <?php
    /*
     * STOCK DÉBUT DE MOIS
     */
    ?>
    \rowcolor{gray}
    \multicolumn{1}{|c|}{ \small{\color{white}{\textbf{STOCK DÉBUT DE MOIS}} }} &
    <?php
      $counter = 0;
      foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit): ?>
        \multicolumn{1}{r|}{  \small{\color{white}{\textbf{<?php echoFloatWithHl($produit->total_debut_mois); ?>}}}}
        <?php echo ($counter < $nb_appellations - 1) ? "&" : '';  $counter++;  ?>
    <?php endforeach; ?>
    \\
    \hline

    <?php
    /*
     * LES ENTREES
     */
    ?>
    <?php foreach ($mvtsEnteesForPdf as $cpt_entree => $entree): ?>
        <?php $entreeKey = $entree->key; ?>
        <?php if (!$cpt_entree): ?>
            \multicolumn{1}{|c}{\multirow{<?php echo count($mvtsEnteesForPdf); ?>}{20mm}{\small{\textbf{ENTREES DU MOIS}}}} &
        <?php endif; ?>

        \multicolumn{1}{|l|}{  \small{<?php echo $entree->libelle; ?>} } &
        <?php
$counter=0;
            foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit):  ?>
            \multicolumn{1}{r|}{  \small{<?php echoFloatWithHl($produit->entrees->$entreeKey); ?>}}
            <?php echo ($counter < $nb_appellations - 1) ? "&" : '';  $counter++;  ?>
        <?php endforeach; ?>
        \\
        <?php if ((count($mvtsEnteesForPdf) - 1) != $cpt_entree): ?>
            \cline{1-<?php echo $maxCol; ?>}
        <?php endif; ?>
    <?php endforeach; ?>
    \hline

    <?php
    /*
     * TOTAL ENTREES
     */
    ?>
    \rowcolor{lightgray}
    \multicolumn{1}{|r|}{ \small{\textbf{TOTAL ENTREES}} } &
    <?php
      $counter=0;
      foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit):  ?>
        \multicolumn{1}{r|}{   \small{\textbf{<?php echoFloatWithHl($produit->total_entrees); ?>}} }
        <?php echo ($counter < $nb_appellations - 1) ? "&" : ''; $counter++;  ?>
    <?php endforeach; ?>
    \\
    \hline

    <?php
    /*
     * LES SORTIES
     */
    ?>
    <?php foreach ($mvtsSortiesForPdf as $cpt_sortie => $sortie): ?>
        <?php $sortieKey = $sortie->key; ?>
        <?php if (!$cpt_sortie): ?>
            \multicolumn{1}{|c}{\multirow{<?php echo count($mvtsSortiesForPdf); ?>}{20mm}{\small{\textbf{SORTIES DU MOIS}}}} &
        <?php endif; ?>

        \multicolumn{1}{|l|}{  \small{<?php echo $sortie->libelle; ?>} } &
  <?php
    $counter=0;
   foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit):  ?>
            \multicolumn{1}{r|}{  \small{\color{black}{<?php echoFloatWithHl($produit->sorties->$sortieKey); ?>}}}
            <?php echo ($counter < $nb_appellations - 1) ? "&" : '';  $counter++;  ?>
        <?php endforeach; ?>
        \\
        <?php if ((count($mvtsSortiesForPdf) - 1) != $cpt_entree): ?>
            \cline{1-<?php echo $maxCol; ?>}
        <?php endif; ?>
    <?php endforeach; ?>
    \hline

    <?php
    /*
     * TOTAL SORTIES
     */
    ?>
    \rowcolor{lightgray}
    \multicolumn{1}{|r|}{ \small{\textbf{TOTAL SORTIES}} } &
  <?php
  $counter=0;
  foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit):  ?>
        \multicolumn{1}{r|}{   \small{\textbf{<?php echoFloatWithHl($produit->total_sorties); ?>}} }
        <?php echo ($counter < $nb_appellations - 1) ? "&" : '';  $counter++;  ?>
    <?php endforeach; ?>
    \\
    \hline

    <?php
    /*
     * STOCK FIN DE MOIS
     */
    ?>
    \rowcolor{gray}
    \multicolumn{1}{|c|}{ \small{\color{white}{\textbf{STOCK FIN DE MOIS}} }} &
  <?php
  $counter=0;
  foreach($produitsDetailsByCertifications->produitsByAppellation as $libelleAppellation => $produit):  ?>
        \multicolumn{1}{r|}{  \small{\color{white}{\textbf{<?php echoFloatWithHl($produit->stocks_fin->final); ?>}}}}
        <?php echo ($counter < $nb_appellations - 1) ? "&" : '';  $counter++;  ?>
    <?php endforeach; ?>
    \\
    \hline
    \end{tabular}
    \newpage

<?php
use_helper('Date');
use_helper('DRM');
use_helper('Orthographe'); 
use_helper('DRMPdf');
use_helper('Display');
$mvtsEnteesForPdf = $drmLatex->getMvtsEnteesForPdf();
$mvtsSortiesForPdf = $drmLatex->getMvtsSortiesForPdf();
?>

\begin{center}
\begin{large}
\textbf{DRM <?php echo getFrPeriodeElision($drm->periode); ?>}
\end{large}
\end{center}


<?php foreach ($drm->declaration->getProduitsDetailsByCertifications(true) as $certification => $produitsDetailsByCertifications) : ?>
    <?php
    $libelleCertif = $produitsDetailsByCertifications->certification->getLibelle();
    $nb_produits = count($produitsDetailsByCertifications->produits);
    if ($nb_produits == 0) {
        continue;
    }
    $nb_pages = (int) ($nb_produits / DRMLatex::NB_PRODUITS_PER_PAGE) + 1;
    $nb_produits_per_page = (int) ($nb_produits / $nb_pages) + 1;
    $nb_produits_displayed = 0;
    $produits_for_certifs = array_values($produitsDetailsByCertifications->produits->getRawValue());
    ?>
    \quad{\setlength{\extrarowheight}{1pt}
    <?php
    for ($index_page = 0; $index_page < $nb_pages; $index_page++):

        $index_first_produit = $index_page * $nb_produits_per_page;
        if ($index_page == $nb_pages - 1) {
            $nb_produits_per_page = $nb_produits - $nb_produits_displayed;
        }
        $size_col = sprintFloat(180.0 / floatval($nb_produits_per_page));
        $entete = '\begin{tabular}{C{20mm} p{48mm} |';
        for ($cpt_col = 0; $cpt_col < $nb_produits_per_page; $cpt_col++) {
            $entete .='C{' . $size_col . 'mm}|';
        }
        $entete .='}';
        if ($index_page > 0) {
            $libelleCertif .= ' (Suite)';
        }
        $maxCol = 2 + $nb_produits_per_page;
        $index_last_produit = $index_first_produit + $nb_produits_per_page - 1;
        $produits_for_page = array();
        foreach (range($index_first_produit, $index_last_produit) as $indexProduit) {
            $produits_for_page[] = $produits_for_certifs[$indexProduit];
            $nb_produits_displayed++;
        }
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
        \cline{3-<?php echo $maxCol; ?>} 			 
        &
        \begin{large}
        \textbf{Produits <?php echo $libelleCertif; ?>}
        \end{large} &
        <?php
        foreach ($produits_for_page as $counter => $produit):
            ?>
            \multicolumn{1}{>{\columncolor[rgb]{0,0,0}}C{<?php echo $size_col; ?>mm}|}{ \small{\color{white}{\textbf{<?php echo $produit->getCepage()->getConfig()->formatProduitLibelle(); ?>}}}} 
            <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
        <?php endforeach; ?>  
        \\	
        \hline
        
        <?php
        /*
         * STOCK DÉBUT DE MOIS
         */
        ?>
        \rowcolor{gray}
        \multicolumn{2}{|c|}{ \small{\color{white}{\textbf{STOCK DBUT DE MOIS}} }} &
        <?php foreach ($produits_for_page as $counter => $produit): ?>
        \multicolumn{1}{r|}{  \small{\color{white}{\textbf{<?php echoFloat($produit->total_debut_mois); ?> hl}}}} 
            <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
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
            <?php else: ?>
                \multicolumn{1}{|c}{~} &
            <?php endif; ?>
                
            \multicolumn{1}{|l|}{  \small{<?php echo $entree->libelle; ?>} } &
            <?php foreach ($produits_for_page as $counter => $produit): ?>
            \multicolumn{1}{r|}{  \small{<?php echoFloat($produit->entrees->$entreeKey); ?> hl}}
                <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
            <?php endforeach; ?>  
            \\
            <?php if ((count($mvtsEnteesForPdf) - 1) != $cpt_entree): ?>
                \cline{2-<?php echo $maxCol; ?>} 	
            <?php endif; ?>     
        <?php endforeach; ?>       
        \hline

        <?php
        /*
         * TOTAL ENTREES
         */
        ?>
        \rowcolor{lightgray}
        \multicolumn{2}{|r|}{ \small{\textbf{TOTAL ENTREES}} } &
        <?php foreach ($produits_for_page as $counter => $produit): ?>
            \multicolumn{1}{r|}{   \small{\textbf{<?php echoFloat($produit->total_entrees); ?> hl}} }
            <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
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
            <?php else: ?>
                \multicolumn{1}{|c}{~} &
            <?php endif; ?>

            \multicolumn{1}{|l|}{  \small{<?php echo $sortie->libelle; ?>} } &
            <?php foreach ($produits_for_page as $counter => $produit): ?>
            \multicolumn{1}{r|}{  \small{\color{black}{<?php echoFloat($produit->sorties->$sortieKey); ?> hl}}} 
                <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
            <?php endforeach; ?>  
            \\
            <?php if ((count($mvtsSortiesForPdf) - 1) != $cpt_entree): ?>
                \cline{2-<?php echo $maxCol; ?>} 	
            <?php endif; ?>    
        <?php endforeach; ?>       
        \hline

        <?php
        /*
         * TOTAL SORTIES
         */
        ?>
        \rowcolor{lightgray}
        \multicolumn{2}{|r|}{ \small{\textbf{TOTAL SORTIES}} } &
        <?php foreach ($produits_for_page as $counter => $produit): ?>
            \multicolumn{1}{r|}{   \small{\textbf{<?php echoFloat($produit->total_sorties); ?> hl}} }
            <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
        <?php endforeach; ?>  
        \\
        \hline
        
        <?php
        /*
         * STOCK FIN DE MOIS
         */
        ?>
        \rowcolor{gray}
        \multicolumn{2}{|c|}{ \small{\color{white}{\textbf{STOCK FIN DE MOIS}} }} &
        <?php foreach ($produits_for_page as $counter => $produit): ?>
        \multicolumn{1}{r|}{  \small{\color{white}{\textbf{<?php echoFloat($produit->stocks_fin->revendique); ?> hl}}}} 
            <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
        <?php endforeach; ?>  
        \\	
        \hline        
        \end{tabular}
        \newpage
    <?php endfor; ?>
    \newpage
<?php endforeach; ?>

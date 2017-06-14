<?php
use_helper('Date');
use_helper('DRM');
use_helper('Orthographe');
use_helper('DRMPdf');
use_helper('Display');
$mvtsEnteesForPdf = $drmLatex->getMvtsEnteesForPdf($detailsNodes);
$mvtsSortiesForPdf = $drmLatex->getMvtsSortiesForPdf($detailsNodes);
?>

\begin{center}
\begin{Large}
\textbf{DRM <?php echo $libelleDetail; ?> <?php echo getFrPeriodeElision($drm->periode); ?>}
\end{Large}
\end{center}

<?php if ($drm->type_creation == DRMClient::DRM_CREATION_NEANT): ?>
    \begin{center}
    \begin{Large}
    \textbf{Aucun mouvement à déclarer ce mois-ci}
    \end{Large}
    \end{center}
<?php else: ?>

    <?php foreach ($drm->declaration->getProduitsDetailsByCertifications(true,$detailsNodes) as $certification => $produitsDetailsByCertifications) : ?>
        <?php
        $libelleCertif = $produitsDetailsByCertifications->certification_libelle;
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
            $size_col = 30;
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
                \multicolumn{1}{>{\columncolor[rgb]{0,0,0}}C{<?php echo $size_col; ?>mm}|}{ \small{\color{white}{\textbf{<?php echo $produit->getLibelle("%format_libelle%"); ?>}}}}
                <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
            <?php endforeach; ?>
            \\
            \hline

            <?php
            /*
             * STOCK DÉBUT DE MOIS
             */
            ?>
            \multicolumn{2}{|>{\columncolor[rgb]{0.6,0.6,0.6}}c|}{ \small{\color{white}{\textbf{STOCK DÉBUT DE MOIS}} }} &
            <?php foreach ($produits_for_page as $counter => $produit): ?>
                \multicolumn{1}{>{\columncolor[rgb]{0.6,0.6,0.6}}r|}{  \small{\color{white}{\textbf{<?php echoFloatWithHl($produit->total_debut_mois); ?>}}}}
                <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
            <?php endforeach; ?>
            \\
            \hline

            <?php
            /*
             * LES ENTREES
             */
            ?>
            <?php
            $cpt_entree = 0;
            foreach ($mvtsEnteesForPdf as $libelle_entree => $entree):
                ?>
                <?php $entreeKey = $entree->key; ?>
                <?php if (!$cpt_entree): ?>
                    \multicolumn{1}{|c}{\multirow{<?php echo count($mvtsEnteesForPdf); ?>}{20mm}{\small{\textbf{ENTREES DU MOIS}}}} &
                <?php else: ?>
                    \multicolumn{1}{|c}{~} &
                <?php endif; ?>
                \multicolumn{1}{|l|}{  \small{<?php echo $entree->libelle; ?>} } &
                <?php foreach ($produits_for_page as $counter => $produit): ?>
                    \multicolumn{1}{r|}{  \small{<?php if ($produit->entrees->exist($entreeKey)) echoFloatWithHl($produit->entrees->$entreeKey); ?>}}
                    <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
                <?php endforeach; ?>
                \\
                <?php if ((count($mvtsEnteesForPdf) - 1) != $cpt_entree): ?>
                    \cline{2-<?php echo $maxCol; ?>}
                <?php endif; ?>
                <?php $cpt_entree++; ?>
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
                \multicolumn{1}{r|}{   \small{\textbf{<?php echoFloatWithHl($produit->total_entrees); ?>}} }
                <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
            <?php endforeach; ?>
            \\
            \hline

            <?php
            /*
             * LES SORTIES
             */
            ?>
            <?php
            $cpt_sortie = 0;
            foreach ($mvtsSortiesForPdf as $libelle_sortie => $sortie):
                ?>
                <?php $sortieKey = $sortie->key; ?>
                <?php if (!$cpt_sortie): ?>
                    \multicolumn{1}{|c}{\multirow{<?php echo count($mvtsSortiesForPdf); ?>}{20mm}{\small{\textbf{SORTIES DU MOIS}}}} &
                <?php else: ?>
                    \multicolumn{1}{|c}{~} &
                <?php endif; ?>
                \multicolumn{1}{|l|}{  \small{<?php echo $sortie->libelle; ?>} } &
                <?php foreach ($produits_for_page as $counter => $produit): ?>
                    \multicolumn{1}{r|}{  \small{\color{black}{ <?php if ($produit->sorties->exist($sortieKey)) echoFloatWithHl($produit->sorties->$sortieKey); ?> }}}
                    <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
                <?php endforeach; ?>
                \\
                <?php if ((count($mvtsSortiesForPdf) - 1) != $cpt_sortie): ?>
                    \cline{2-<?php echo $maxCol; ?>}
                <?php endif; ?>
                <?php $cpt_sortie++; ?>
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
                \multicolumn{1}{r|}{   \small{\textbf{<?php echoFloatWithHl($produit->total_sorties); ?>}} }
                <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
            <?php endforeach; ?>
            \\
            \hline

            <?php
            /*
             * STOCK FIN DE MOIS
             */
            ?>
            \multicolumn{2}{|>{\columncolor[rgb]{0.6,0.6,0.6}}c|}{ \small{\color{white}{\textbf{STOCK FIN DE MOIS}} }} &
            <?php foreach ($produits_for_page as $counter => $produit): ?>
                \multicolumn{1}{>{\columncolor[rgb]{0.6,0.6,0.6}}r|}{  \small{\color{white}{\textbf{<?php echoFloatWithHl($produit->stocks_fin->revendique); ?>}}}}
                <?php echo ($counter < count($produits_for_page) - 1) ? "&" : ''; ?>
            <?php endforeach; ?>
            \\
            \hline
            \end{tabular}
            <?php if (($nb_pages > 1) && (($nb_pages - 1) == $index_page)) : ?>
                \newpage
            <?php endif; ?>
        <?php endfor; ?>
        \newpage
    <?php endforeach; ?>

<?php endif; ?>

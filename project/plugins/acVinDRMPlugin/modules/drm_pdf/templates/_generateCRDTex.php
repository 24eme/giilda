<?php
$cpt_crds_annexes = $drm->nbTotalCrdsTypes();
if ($drm->exist('documents_annexes')) {
    $cpt_crds_annexes+=count($drm->documents_annexes);
}
if ($drm->exist('releve_non_apurement')) {
    $cpt_crds_annexes+= count($drm->releve_non_apurement);
}
$hasAnnexes = $drm->exist('documents_annexes') && count($drm->documents_annexes);
$hasNonApurement = $drm->exist('releve_non_apurement') && count($drm->releve_non_apurement);
?>
<?php if ($cpt_crds_annexes): ?>
    <?php foreach ($drm->getAllCrdsByRegimeAndByGenre() as $regime_crd => $crdsByGenre) : ?>
        \begin{center}
        \begin{large}
        \textbf{Compte Capsules}
        \end{large}
        \end{center}
        \begin{large}
        <?php foreach (DRMClient::getInstance()->getAllRegimesCrdsChoices(false, true) as $key => $libelle): ?>
          <?php echo $libelle; ?>~:~<?php echo getCheckBoxe(strpos($regime_crd, $key) !== false); ?>~~~~~~~~~~~~
        <?php endforeach; ?>
        \end{large}
        ~ \\ ~ \\
        \begin{tabular}{C{30mm} |C{10mm}|C{22mm}|C{22mm}|C{22mm}|C{20mm}|C{22mm}|C{22m}|C{22mm}|C{20mm}|C{10mm}|}

        \cline{3-8}
        \multicolumn{1}{c}{~} &
        \multicolumn{1}{c}{~} &
        \multicolumn{4}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Entrées}}}} &
        \multicolumn{4}{|c}{\cellcolor[gray]{0.3}\small{\color{white}{\textbf{Sorties}}}} &
        \multicolumn{1}{|c}{~}
        \\
        \hline
        \multicolumn{1}{|C{30mm}}{\cellcolor[gray]{0.7}\small{\textbf{CRD}}} &
        \multicolumn{1}{|C{10mm}}{\cellcolor[gray]{0.7}\small{\textbf{Stock}}} &

        \multicolumn{1}{|C{22mm}}{\cellcolor[gray]{0.7}\small{\textbf{Achats}}} &
        \multicolumn{1}{|C{22mm}}{\cellcolor[gray]{0.7}\small{\textbf{Retours}}} &
        \multicolumn{1}{|C{22mm}}{\cellcolor[gray]{0.7}\small{\textbf{Excédents}}} &
        \multicolumn{1}{|C{20mm}}{\cellcolor[gray]{0.7}\small{\textbf{Autres}}} &
        \multicolumn{1}{|C{22mm}}{\cellcolor[gray]{0.7}\small{\textbf{Utilisés}}} &
        \multicolumn{1}{|C{22mm}}{\cellcolor[gray]{0.7}\small{\textbf{Destructions}}} &
        \multicolumn{1}{|C{22mm}}{\cellcolor[gray]{0.7}\small{\textbf{Manquants}}} &
        \multicolumn{1}{|C{20mm}}{\cellcolor[gray]{0.7}\small{\textbf{Autres}}} &
        \multicolumn{1}{|C{10mm}|}{\cellcolor[gray]{0.7}\small{\textbf{Stock fin de mois}}}
        \\
        \hline

        <?php foreach ($crdsByGenre as $genre_crd => $crds): ?>
            <?php foreach ($crds as $key_crd => $crd): ?>
                \multicolumn{1}{|l}{\small{\textbf{<?php echo $genre_crd . ' ' . $crd->getLibelle(); ?>}}} &
                \multicolumn{1}{|r}{\small{\textbf{<?php echo $crd->stock_debut; ?>}}} &

                \multicolumn{1}{|r}{\small{\textbf{<?php echo $crd->entrees_achats; ?>}}} &
                \multicolumn{1}{|r}{\small{<?php echo $crd->entrees_retours; ?>}} &
                \multicolumn{1}{|r}{\small{<?php echo $crd->entrees_excedents; ?>}} &
                \multicolumn{1}{|r}{\small{<?php echo $crd->entrees_autres; ?>}} &
                \multicolumn{1}{|r}{\small{\textbf{<?php echo $crd->sorties_utilisations; ?>}}} &
                \multicolumn{1}{|r}{\small{<?php echo $crd->sorties_destructions; ?>}} &
                \multicolumn{1}{|r}{\small{<?php echo $crd->sorties_manquants; ?>}} &
                \multicolumn{1}{|r}{\small{<?php echo $crd->sorties_autres; ?>}} &
                \multicolumn{1}{|r|}{\small{\textbf{<?php echo $crd->stock_fin; ?>}}}

                \\
                \hline

            <?php endforeach; ?>
        <?php endforeach; ?>
        \end{tabular}
    <?php endforeach; ?>
<?php endif; ?>
\newpage

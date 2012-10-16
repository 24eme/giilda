<?php
$max_rows =  (FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE + FactureLatex::MAX_LIGNE_TABLE_LASTPAGE);
$fillTables = (($max_rows * 3/4) < $nb_ligne);
$nbPapillonsLignes = count($facture->echeances) * 4 ;

$maxLigneForFirstPage = $nb_ligne/2 ;

include_partial('templateEntete', array('facture' => $facture));
?>
\begin{document}
<?php
include_partial('templateTitreAdresse', array('facture' => $facture)); 
include_partial('templateNumPage', array('nb_page' => $nb_page));
include_partial('templateHeadTable');
$firstPage = true;
$nb_ligne_current = 0;

foreach ($facture->lignes as $type => $typeLignes) :
    $nb_ligne_current ++;
//    if(!($nb_ligne_current > FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE)) :
    include_partial('templateTableTypeRow', array('type' => $type));
//    endif;    
    $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);                 
    foreach ($produits as $prodHash => $p) :   
        foreach ($p as $produit):
            if($fillTables):
                if($firstPage && ($nb_ligne_current > FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE)):
                    include_partial('templateEndTableWithoutMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE, 'nb_lignes' => $nb_ligne_current));
                    ?>
                    \newpage
                    <?php
                    include_partial('templateNumPage', array('nb_page' => $nb_page));
                    include_partial('templateHeadTable');
                    include_partial('templateTableTypeRow', array('type' => $type));
                    $firstPage = false;
                    $nb_ligne_current = $nbPapillonsLignes;
                endif;
            else :
                if($firstPage && ($nb_ligne_current > $maxLigneForFirstPage)):
                    include_partial('templateEndTableWithoutMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE, 'nb_lignes' => $nb_ligne_current));
                    ?>
                    \newpage
                    <?php
                    include_partial('templateNumPage', array('nb_page' => $nb_page));
                    include_partial('templateHeadTable');
                    include_partial('templateTableTypeRow', array('type' => $type));
                    $firstPage = false;
                    $nb_ligne_current = $nbPapillonsLignes;
                endif;
            endif;
            include_partial('templateTableRow', array('produit' => $produit->getRawValue()));
            $nb_ligne_current ++;
    endforeach;
    endforeach;
endforeach;
include_partial('templateEndTableWithMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_LASTPAGE, 'nb_lignes' => $nb_ligne_current));
include_partial('templateReglement', array('facture' => $facture)); 
include_partial('templateEcheances', array('echeances' => $facture->echeances)); 
?>
\end{document}

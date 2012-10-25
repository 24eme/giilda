<?php
$max_rows =  (FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE + FactureLatex::MAX_LIGNE_TABLE_LASTPAGE);
$fillTables = (($max_rows * 3/4) < $nb_ligne);
$nbPapillonsLignes = count($facture->echeances) * 4 ;

$maxLigneForFirstPage = $nb_ligne/2 ;

include_partial('facture/templateEntete', array('facture' => $facture));
?>
\begin{document}
<?php
include_partial('facture/templateTitreAdresse', array('facture' => $facture)); 
include_partial('facture/templateNumPage', array('nb_page' => $nb_page));
include_partial('facture/templateHeadTable');
$firstPage = true;
$nb_ligne_current = 0;

foreach ($facture->lignes as $type => $typeLignes) :
    $nb_ligne_current ++;
//    if(!($nb_ligne_current > FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE)) :
    include_partial('facture/templateTableTypeRow', array('type' => $type));
//    endif;    
    $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);                 
    foreach ($produits as $prodHash => $p) :   
        foreach ($p as $produit):
            if($fillTables):
                if($firstPage && ($nb_ligne_current > FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE)):
                    include_partial('facture/templateEndTableWithoutMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE, 'nb_lignes' => $nb_ligne_current));
                    ?>
                    \newpage
                    <?php
                    include_partial('facture/templateNumPage', array('nb_page' => $nb_page));
                    include_partial('facture/templateHeadTable');
                    include_partial('facture/templateTableTypeRow', array('type' => $type));
                    $firstPage = false;
                    $nb_ligne_current = $nbPapillonsLignes;
                endif;
            else :
                if($firstPage && ($nb_ligne_current > $maxLigneForFirstPage)):
                    include_partial('facture/templateEndTableWithoutMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_FIRSTPAGE, 'nb_lignes' => $nb_ligne_current));
                    ?>
                    \newpage
                    <?php
                    include_partial('facture/templateNumPage', array('nb_page' => $nb_page));
                    include_partial('facture/templateHeadTable');
                    include_partial('facture/templateTableTypeRow', array('type' => $type));
                    $firstPage = false;
                    $nb_ligne_current = $nbPapillonsLignes;
                endif;
            endif;
            include_partial('facture/templateTableRow', array('produit' => $produit->getRawValue()));
            $nb_ligne_current ++;
    endforeach;
    endforeach;
endforeach;
include_partial('facture/templateEndTableWithMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_LASTPAGE, 'nb_lignes' => $nb_ligne_current));
include_partial('facture/templateReglement', array('facture' => $facture)); 
include_partial('facture/templateEcheances', array('echeances' => $facture->echeances)); 
?>
\end{document}

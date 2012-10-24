<?php include_partial('facture/templateEntete', array('facture' => $facture)); ?>

\begin{document}
    <?php 
    include_partial('facture/templateTitreAdresse', array('facture' => $facture)); 
    include_partial('facture/templateNumPage', array('nb_page' => $nb_page));
    include_partial('facture/templateHeadTable');
    $nb_ligne_current = 0;
    $nb_ligne_current += count($facture->lignes);
    foreach ($facture->lignes as $type => $typeLignes) :
        include_partial('facture/templateTableTypeRow', array('type' => $type));
        $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);
        foreach ($produits as $prodHash => $p) :   
            foreach ($p as $produit):
                $nb_ligne_current ++;
                include_partial('facture/templateTableRow', array('produit' => $produit->getRawValue()));
        endforeach;
        endforeach;
    endforeach;
    include_partial('facture/templateEndTableWithMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_ONEPAGE, 'nb_lignes' => $nb_ligne_current)); 
    include_partial('facture/templateReglement', array('facture' => $facture)); 
    include_partial('facture/templateEcheances', array('echeances' => $facture->echeances)); 
    ?>
\end{document}

<?php include_partial('templateEntete', array('facture' => $facture)); ?>

\begin{document}
    <?php 
    include_partial('templateTitreAdresse', array('facture' => $facture)); 
    include_partial('templateNumPage', array('nb_page' => $nb_page));
    include_partial('templateHeadTable');
    $nb_ligne_current = 0;
    $nb_ligne_current += count($facture->lignes);
    foreach ($facture->lignes as $type => $typeLignes) :
        include_partial('templateTableTypeRow', array('type' => $type));
        $produits = FactureClient::getInstance()->getProduitsFromTypeLignes($typeLignes);
        foreach ($produits as $prodHash => $p) :   
            foreach ($p as $produit):
                $nb_ligne_current ++;
                include_partial('templateTableRow', array('produit' => $produit->getRawValue()));
        endforeach;
        endforeach;
    endforeach;
    include_partial('templateEndTableWithMention', array('max_lignes' => FactureLatex::MAX_LIGNE_TABLE_ONEPAGE, 'nb_lignes' => $nb_ligne_current)); 
    include_partial('templateReglement', array('facture' => $facture)); 
    include_partial('templateEcheances', array('echeances' => $facture->echeances)); 
    ?>
\end{document}

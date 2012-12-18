function(doc) {

    if (doc.type != 'DS') {
        return;
    }

    if (doc.statut != "valide") {
        return;
    }

    for (hash_produit in doc.declarations) {
        var produit = doc.declarations[hash_produit];
        var societe = null;
        emit([doc.campagne, societe, doc.identifiant, produit.produit_hash, doc.periode, doc._id], [produit.stock_revendique, doc.declarant.nom, produit.produit_libelle]);
    }
}
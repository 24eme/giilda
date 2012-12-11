function(doc) {
    if ((doc.type != "Vrac") || (doc.valide.statut == null)) {
        
        return;
    }

    var societe = null;

    emit([doc.campagne, doc.type_transaction, societe, doc.vendeur_identifiant, doc.produit, doc.numero_contrat], doc.volume_propose - doc.volume_enleve);
}
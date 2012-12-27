function(doc) {
    if ((doc.type != "Vrac") || (doc.valide.statut == null)) {
        
        return;
    }

    original = "NON";
    if (doc.attente_original) {
        original = "OUI";
    }
    
    prix_definitif = 0;

    archive = doc.numero_contrat;
    if (doc.numero_archive) {
	   archive = doc.numero_archive;
    }
    libelle_produit = " libelle en attente ";
    emit([doc._id], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve, doc.prix_unitaire, prix_definitif, doc.prix_variable, doc.produit_libelle, "NON", original, doc.type_contrat, doc.date_signature, doc.date_campagne, doc.valide.date_saisie, doc.millesime, doc.categorie_vin, doc.domaine, doc.part_variable, doc.cvo_repartition, doc.cvo_nature]);
}

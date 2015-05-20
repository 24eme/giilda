function(doc) {
    if ((doc.type != "Vrac") || (doc.valide.statut == null) || (doc.valide.statut == 'BROUILLON')) {
        
        return;
    }

    var original = "OUI";
    if (doc.attente_original) {
        original = "NON"
    }
    
    var prix_variable = "NON";
    if (doc.prix_variable) {
        prix_variable = "OUI";
    }

    var interne = "NON";
    if (doc.interne) {
        interne = "OUI";
    }

    var archive = doc.numero_contrat;
    if (doc.numero_archive) {
	   archive = doc.numero_archive;
    }
    teledeclare = 0;
    if (doc.teledeclare && doc.teledeclare===true) {
	   teledeclare = 1;
    }

    emit([teledeclare, doc.campagne, doc._id], [doc.campagne, doc.valide.statut, doc._id, doc.numero_contrat, archive, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.produit_libelle, doc.volume_propose, doc.volume_enleve, doc.prix_initial_unitaire_hl, doc.prix_unitaire_hl, prix_variable, interne, original, doc.type_contrat, doc.date_signature, doc.date_campagne, doc.valide.date_saisie, doc.millesime, doc.categorie_vin, doc.domaine, doc.part_variable, doc.cvo_repartition, doc.cvo_nature]);
}
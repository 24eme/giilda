function(doc) {
  	if ((doc.type != "Vrac") || (doc.valide.statut == null)) {
    	
    	return;
  	}
	//Dernier champ pour les contrats internes
	original = "NON";
	if (doc.original) {
		origina = "OUI";
	}
        prix_definitif = 0;
  	emit([doc._id], [doc.valide.statut, doc._id, doc.acheteur_identifiant, doc.acheteur.nom, doc.vendeur_identifiant, doc.vendeur.nom, doc.mandataire_identifiant,doc.mandataire.nom, doc.type_transaction, doc.produit, doc.volume_propose, doc.volume_enleve, doc.prix_unitaire, prix_definitif, doc.prix_variable, "NON", original, doc.type_contrat, doc.date_signature, doc.date_stats, doc.valide.date_saisie, doc.millesime, doc.domaine, doc.part_variable, doc.cvo_repartition, doc.cvo_nature]);
}

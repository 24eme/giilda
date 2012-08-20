function(doc) {
  	if (doc.type != "Vrac") {

 		return;
 	}
        if ((doc.valide.statut != "SOLDE") && (doc.valide.statut != "NONSOLDE")) {
 		return;
 	}
    	emit(['M',doc.vendeur_identifiant, doc.acheteur_identifiant, doc.mandataire_identifiant, doc.type_transaction, doc.produit, doc.volume_propose],[doc.numero_contrat, doc.valide.statut, doc.millesime , doc.volume_propose]);
        emit(['',doc.vendeur_identifiant, doc.acheteur_identifiant, doc.type_transaction, doc.produit, doc.volume_propose],[doc.numero_contrat, doc.valide.statut, doc.millesime , doc.volume_propose]);
}
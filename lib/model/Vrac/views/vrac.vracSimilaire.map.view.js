function(doc) {
  	if (doc.type != "Vrac") {
    	
    	return;
    }
  	
  	if((doc.valide.statut == null) || (doc.valide.statut == "ANNULE")) {
     
    	return;
 	}

  	emit([doc.vendeur_identifiant, doc.acheteur_identifiant, doc.mandataire_identifiant, doc.type_transaction, doc.produit, doc.volume_propose],  [ doc._id, doc.valide.statut, doc.millesime, doc.volume_propose]);
}
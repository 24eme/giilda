function(doc) {
  	if (doc.type != "Vrac") {

 		return;
 	}
    	emit([doc.valide.statut, doc.vendeur_identifiant, doc.produit, doc.type_transaction],[doc.numero_contrat, doc.acheteur.nom, doc.volume_propose, doc.volume_enleve, doc.vendeur.nom]);
}
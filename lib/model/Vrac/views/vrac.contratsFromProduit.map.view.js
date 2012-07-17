function(doc) {
  	if (doc.type != "Vrac") {

 		return;
 	}
    	emit([doc.valide.statut, doc.vendeur_identifiant, doc.vendeur.nom, doc.produit],[doc.numero_contrat, doc.volume_propose, doc.volume_enleve]);
}
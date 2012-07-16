function(doc) {
  	if (doc.type != "Vrac") {

 		return;
 	}
  	
  	if(doc.valide.statut == "NONSOLDE") {
    	
    	emit([doc.vendeur_identifiant, doc.produit],[doc.numero_contrat, doc.volume_propose, doc.volume_enleve, doc.valide.statut]);
	}
}
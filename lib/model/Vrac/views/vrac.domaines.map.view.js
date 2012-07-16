function(doc) {
  	if (doc.type != "Vrac") {
    	
    	return;
    }
  	
  	if (doc.domaine) {
  		
  		emit([doc.vendeur_identifiant, doc.domaine],  1);
  	}
}
function(doc) {
    if (doc.type != "SV12") {
    	return;
  	}
        
        emit([doc.negociant_identifiant, doc.periode, doc.identifiant, doc.version, doc.valide.date_saisie, doc.valide.statut, doc._id], 1);
}
function(doc) {
    if (doc.type != "SV12") {
    	return;
  	}
        
        emit([doc.negociant_identifiant, doc.periode, doc.identifiant, doc.version, doc.valide.date_saisie, doc._id], [doc.identifiant, doc.valide.date_saisie, doc.periode, doc.negociant_identifiant, doc.negociant.nom, doc.negociant.cvi, doc.negociant.commune, doc.valide.statut]);
}
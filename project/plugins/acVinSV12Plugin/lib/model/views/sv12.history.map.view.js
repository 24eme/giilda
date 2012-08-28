function(doc) {
  	if (doc.type != "SV12") {    	
    	return;
  	}

  	emit([doc.valide.statut], [doc.identifiant, doc.valide.date_saisie, doc.periode, doc.negociant_identifiant, doc.negociant.nom, doc.negociant.cvi, doc.negociant.commune, doc.valide.statut] );
}
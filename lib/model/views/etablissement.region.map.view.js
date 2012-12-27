function(doc) {

	if (doc.type != "Etablissement") {
      	
      	return ;     
    }     
  	emit([doc.famille, doc.region, doc.siege.commune, doc.siege.code_postal, doc.cvi, doc.nom, doc.identifiant], 1);
}
function(doc) {

	if (doc.type != "Etablissement") {
      	
      	return ;     
    }     

  	emit([doc.famille, doc.commune, doc.code_postal, doc.cvi, doc.nom, doc.identifiant], 1);
}
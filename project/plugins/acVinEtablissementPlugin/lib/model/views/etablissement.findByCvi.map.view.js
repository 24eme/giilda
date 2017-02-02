function(doc) {
  	if (doc.type == "Etablissement") {
  		emit([doc.cvi],[doc.identifiant, doc.nom, doc.siege.commune]);
      if (doc.no_accises) {
        emit([doc.no_accises],[doc.identifiant, doc.nom, doc.siege.commune]);
      }
 	}
}

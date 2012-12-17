function(doc) {
  	if (doc.type == "Etablissement") {

        return;
    }
  	emit([doc.interpro, 
		  doc.famille, 
          doc.id_societe,
		  doc._id,
          doc.statut,
		  doc.nom, 
	  	  doc.identifiant, 
	  	  doc.cvi, 
	  	  doc.siege.commune, 
	      doc.siege.code_postal, 
          doc.region], null);
}

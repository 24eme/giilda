function(doc) {
  	if (doc.type == "Societe") {
  		emit([doc.interpro, 
  			  doc._id, 
			  doc.type_societe,
		  	  doc.identifiant, 
		  	  doc.raison_sociale, 
		  	  doc.siret,
		  	  doc.commune, 
		          doc.code_postal], null);
 	}
}
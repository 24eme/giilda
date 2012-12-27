function(doc) {
  	if (doc.type != "Etablissement") {

        return;
    }
    
    emit([doc.interpro, doc.statut, doc.famille, doc.id_societe, doc._id, doc.nom, doc.identifiant, doc.cvi, doc.region], [doc.siege.adresse, doc.siege.commune, doc.siege.code_postal]);
    if (doc.cooperative) {
	emit([doc.interpro, doc.statut, "cooperative", doc.id_societe, doc._id, doc.nom, doc.identifiant, doc.cvi, doc.region], [doc.siege.adresse, doc.siege.commune, doc.siege.code_postal]);
    }
}

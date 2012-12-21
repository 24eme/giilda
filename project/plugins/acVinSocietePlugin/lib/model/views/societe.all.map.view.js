function(doc) {
    if (doc.type == "Societe") {
        emit([doc.interpro, 
            doc.statut,
            doc.type_societe,
            doc._id, 
            doc.raison_sociale, 
            doc.identifiant, 
            doc.siret,
            doc.commune, 
            doc.code_postal], null);
    }
}
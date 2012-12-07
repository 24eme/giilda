function(doc) {
    if (doc.type == "Societe") {
        emit([doc.interpro, 
            doc.raison_sociale, 
            doc._id, 
            doc.type_societe,
            doc.identifiant, 
            doc.siret,
            doc.commune, 
            doc.code_postal], null);
    }
}
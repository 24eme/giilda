function(doc) {
    if (doc.type != "Generation") {
        return;
    }
    var region = (doc.arguments && doc.arguments.region)? doc.arguments.region : null;
    emit([doc.type_document, region, doc.date_emission, doc.identifiant], [doc.nb_documents, doc.documents, doc.somme, doc.statut, doc.libelle]);
}

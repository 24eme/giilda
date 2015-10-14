function(doc) {
    if (doc.type != "Generation") {
        return;
    }
    emit([doc.type_document, doc.date_emission, doc.identifiant], [doc.nb_documents, doc.documents, doc.somme, doc.statut, doc.libelle]);
}
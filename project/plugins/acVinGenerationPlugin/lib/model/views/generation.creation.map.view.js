 function(doc) {
    if (doc.type != "Generation") {
        return;
    }
   emit([doc.statut, doc.type_document, doc.identifiant], [doc.date_emission, doc.nb_documents, doc.documents, doc.somme]);
}
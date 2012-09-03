function(doc) {
    if (doc.type != "Generation") {
        return;
    }
    if(doc.type_document != "Facture")
    {
        return;
    }
    emit([doc.identifiant], [doc.date_emission, doc.nb_documents, doc.documents, doc.somme]);
}
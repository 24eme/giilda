function(doc) {
    if (doc.type != "Generation") {
        return;
    }
    var interpro = (doc.arguments && doc.arguments.interpro)? doc.arguments.interpro : null;
    emit([doc.type_document, interpro, doc.date_emission, doc.identifiant], [doc.nb_documents, doc.documents, doc.somme, doc.statut, doc.libelle]);
}

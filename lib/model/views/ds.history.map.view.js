function(doc) {
    if (doc.type != "DS") {
        return;
    }

    emit([doc.identifiant, doc.campagne, doc.statut], [doc._id, doc.declarant.cvi]);

}
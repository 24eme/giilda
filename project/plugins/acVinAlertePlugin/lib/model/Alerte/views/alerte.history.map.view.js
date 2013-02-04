function(doc) {
    if (doc.type != "Alerte") {
        return;
    }
    var dernierChangement = {};
    if(doc.statuts.length <= 0){
        dernierChangement.date = null;
        dernierChangement.statut = null;
    }
    else
    {
        dernierChangement = doc.statuts[doc.statuts.length - 1];

    }
    emit([doc.type_alerte, dernierChangement.statut, dernierChangement.date, doc.id_document, doc.date_creation, doc.identifiant],[doc.declarant_nom, doc.libelle_document]);
}

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
    
    emit([doc.identifiant, doc.region, doc.type_alerte, dernierChangement.statut, doc.campagne],[ doc.id_document, doc.declarant_nom, doc.date_creation,dernierChangement.date, doc.libelle_document]);
}
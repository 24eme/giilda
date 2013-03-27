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
    
    emit([doc.identifiant, dernierChangement.statut, doc.type_relance, doc.type_alerte, doc.region, doc.campagne, doc.date_relance],[ doc.id_document, doc.declarant_nom, doc.date_creation,dernierChangement.date, doc.libelle_document]);
}
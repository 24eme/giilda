function(doc) {
    if (doc.type != "DRM") {
        
        return;     
    }
    
    emit([doc.identifiant, doc.campagne, doc.periode, doc.version, doc.valide.date_saisie, doc.douane.envoi, doc.douane.accuse, doc._id, doc.numero_archive], 1);
}
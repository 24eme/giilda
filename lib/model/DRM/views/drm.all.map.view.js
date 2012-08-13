function(doc) {
    if (doc.type == "DRM") {
        rect = null ; 
        
        if (doc.rectificative) { 
            rect = doc.rectificative;
        }

        emit([doc.identifiant, doc.campagne, doc.periode, rect, doc.valide.date_saisie, doc.douane.envoi, doc.douane.accuse, doc._id], 1);
    }
}
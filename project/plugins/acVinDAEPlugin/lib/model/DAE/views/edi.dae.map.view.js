function(doc) {
    if (doc.type != "DRM") {
        
        return;     
    }
    var ciel = (doc.ciel && doc.ciel.transfere)? 1 : 0; 
    var ciel_valide = (doc.ciel && doc.ciel.valide)? 1 : 0;
    emit([ciel, (doc.declarant.no_accises).toUpperCase(), doc.periode], ciel_valide);
}
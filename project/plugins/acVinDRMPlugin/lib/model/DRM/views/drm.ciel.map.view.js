function(doc) {
    if (doc.type != "DRM") {
        
        return;     
    }
    var ciel = (doc.transmission_douane && doc.transmission_douane.success)? 1 : 0; 
    emit([ciel, doc.declarant.no_accises, doc.periode], null);
}
function(doc) {
    if (doc.type != "DRM") {

        return;
    }

    if (!doc.valide.date_saisie) {

        return;
    }

    var campagne = doc.campagne.substring(0,4);
    campagne = (campagne - 1) + '-' + campagne;

    for(key in doc.mouvements) {
        var mouv = doc.mouvements[key];
        emit([doc.identifiant, campagne, doc._id, mouv.produit_hash, mouv.type_hash], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.detail_libelle]);
    }
}
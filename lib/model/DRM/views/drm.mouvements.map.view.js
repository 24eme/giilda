function(doc) {
    if (doc.type != "DRM") {

        return;
    }

    if (!doc.valide.date_saisie) {

        return;
    }

    for(key in doc.mouvements) {
        var mouv = doc.mouvements[key];
        emit([doc.identifiant, doc.campagne, doc.periode, doc._id, mouv.produit_hash, mouv.type_hash], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.detail_libelle, doc.valide.date_saisie, doc.version]);
    }
}
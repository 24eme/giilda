function(doc) {
    if (doc.type != "DRM") {

        return;
    }

    if (!doc.valide.date_saisie) {

        return;
    }

    for(key in doc.mouvements) {
        var mouv = doc.mouvements[key];
        emit([mouv.facture, mouv.facturable, doc.identifiant, mouv.produit_hash, mouv.type_hash], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.detail_libelle]);
    }
}
function(doc) {
    if (doc.type != "DRM") {

        return;
    }

    if (!doc.valide.date_saisie) {

        return;
    }

    for(key in doc.mouvements) {
        var mouv = doc.mouvements[key];
        mouv.cvo = 5.0;
        mouv.facturable = 1;
        emit([mouv.facture, mouv.facturable, doc.identifiant, doc.type, doc.periode, 'Vins', mouv.produit_hash, mouv.type_hash, mouv.detail_identifiant], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.detail_libelle, mouv.cvo, 'DRM-'+doc.identifiant+'-'+doc.periode]);
    }
}
function(doc) {
    if (doc.type != "DRM" && doc.type != 'SV12') {

        return;
    }

    if (!doc.valide.date_saisie) {

        return;
    }

    for(identifiant in doc.mouvements) {
        for (key in doc.mouvements[identifiant]) {
            var mouv = doc.mouvements[identifiant][key];
            emit([doc.type, identifiant, doc.campagne, doc.periode, doc._id, mouv.produit_hash, mouv.type_hash, mouv.vrac_numero, mouv.detail_identifiant], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.vrac_destinataire, mouv.detail_libelle, mouv.date_version, mouv.version]);
        }
    }
}
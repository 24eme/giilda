function(doc) {
    if (doc.type != "DRM" && doc.type != "SV12") {

        return;
    }

    if (!doc.valide.date_saisie) {

        return;
    }

    for(identifiant in doc.mouvements) {
        for(key in doc.mouvements[identifiant]) {
            var mouv = doc.mouvements[identifiant][key];
            emit([mouv.facture, mouv.facturable, 'tours', identifiant, doc.type, mouv.categorie, mouv.produit_hash, doc.periode, mouv.vrac_numero, mouv.type_hash, mouv.detail_identifiant], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.cvo, mouv.date, mouv.vrac_destinataire, mouv.detail_libelle, doc.type+'-'+doc.identifiant+'-'+doc.periode, doc._id+':'+key]);
        } 
   }
}

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
	mouv.facture = 0;
	mouv.categorie = 'propriete';
	doc.region = 'tours';
        mouv.date = '2012/12/14';
        emit([mouv.facture, mouv.facturable, doc.region, doc.identifiant, doc.type, mouv.categorie, mouv.produit_hash, doc.periode, mouv.type_hash, mouv.detail_identifiant], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.cvo, mouv.date, mouv.detail_libelle, 'DRM-'+doc.identifiant+'-'+doc.periode, key]);
    }
}
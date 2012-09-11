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
            doc.region = 'tours';
	if(doc.type == "SV12")
	{
		doc.identifiant = doc.identifiant.substr(0,6);
		mouv.cvo = 4.5;
	}
	    var vrac_id = (mouv.type_hash == 'sorties/vrac' || mouv.type_hash == 'raisins' || mouv.type_hash == 'mouts')? mouv.detail_identifiant : null;
            emit([mouv.facture, mouv.facturable, doc.region, doc.identifiant, doc.type, mouv.categorie, mouv.produit_hash, doc.periode, vrac_id, mouv.type_hash, mouv.detail_identifiant], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.cvo, mouv.date, mouv.detail_libelle, doc.type+'-'+doc.identifiant+'-'+doc.periode, key]);
        } 
   }
}
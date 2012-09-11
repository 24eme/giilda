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
            emit([mouv.facture, mouv.facturable, doc.region, doc.identifiant, doc.type, mouv.categorie, mouv.produit_hash, doc.periode, mouv.vrac_numero, mouv.type_hash, mouv.detail_identifiant], [mouv.produit_libelle, mouv.type_libelle, mouv.volume, mouv.cvo, mouv.date, mouv.vrac_destinataire, mouv.detail_libelle, doc.type+'-'+doc.identifiant+'-'+doc.periode, key]);
        } 
   }
}
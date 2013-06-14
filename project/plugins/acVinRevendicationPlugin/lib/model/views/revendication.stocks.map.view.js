function(doc) {
    if (doc.type != "Revendication") {
        return;     
    }

    for(identifiant in doc.datas) {
        revs = doc.datas[identifiant];
        for(code_douane in revs.produits) {
            rev = revs.produits[code_douane];
            var volume = 0;
	    var flag = false;
            for(volume_key in rev.volumes) {
		if(rev.volumes[volume_key].statut != "SUPPRIME"){
                	volume += rev.volumes[volume_key].volume;
			flag = true;
		}
            }
	    if(flag){
              emit([doc.campagne, identifiant, rev.produit_hash, doc.odg, doc._id], [volume, revs.declarant_nom, rev.libelle_produit_csv])
            }
	}
    }

}
function(doc) {
    if (doc.type != "Revendication") {
        return;     
    }

    for(identifiant in doc.datas) {
        revs = doc.datas[identifiant];
        for(code_douane in revs.produits) {
            rev = revs.produits[code_douane];
            var volume = 0;
            for(volume_key in rev.volumes) {
                volume += rev.volumes[volume_key].volume;
            }
            emit([doc.campagne, identifiant, rev.produit_hash, doc.odg, doc._id], [volume, revs.declarant_nom, rev.libelle_produit_csv])
        }
    }

}
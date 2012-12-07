function(doc) {
    if (doc.type != "Revendication") {
        return;     
    }

    for(identifiant in doc.datas) {
        revs = doc.datas[identifiant];
        var societe = null;
        for(code_douane in revs.produits) {
            rev = revs.produits[code_douane];
            for(id_ligne in rev.volumes) {
                var ligne = rev.volumes[id_ligne];
                emit([doc.campagne, doc.odg, societe, identifiant, rev.produit_hash, rev.statut, id_ligne], [ligne.volume, ligne.date_insertion, rev.libelle_produit_csv, revs.declarant_cvi, revs.declarant_nom, revs.commune]);
            }
        }
    }
}
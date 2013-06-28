function(doc) {
    if (doc.type != "Revendication") {
        return;     
    }

    for(identifiant in doc.datas) {
        revs = doc.datas[identifiant];
        for(code_douane in revs.produits) {
            rev = revs.produits[code_douane];
            for(id_ligne in rev.volumes) {
                var ligne = rev.volumes[id_ligne];
                emit([identifiant, doc.campagne, doc.odg, rev.produit_hash, ligne.statut, id_ligne, code_douane], [ligne.volume, ligne.date_insertion, rev.libelle_produit_csv, revs.declarant_cvi, revs.declarant_nom, revs.commune, rev.date_certification, ligne.bailleur_identifiant, ligne.bailleur_nom, rev.produit_libelle]);
            }
        }
    }
}
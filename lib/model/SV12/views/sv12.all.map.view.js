function(doc) {
    if (doc.type != "SV12") {
        return;
    }
        
    emit([doc.identifiant, doc.campagne, doc.periode, doc.version], [doc.declarant.nom, doc.declarant.cvi, doc.declarant.commune, doc.valide.statut, doc.valide.date_saisie, doc.totaux.volume_raisins, doc.totaux.volume_mouts]);
}
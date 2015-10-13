function(doc) {
    if (doc.type != "Facture") {
            return;
    }
    
    var versement_comptable = 1;

    if(!doc.versement_comptable) {
        versement_comptable = 0;
    }

    if(doc.date_paiement && !doc.versement_comptable_paiement) {
        versement_comptable = 0;
    }

    emit([versement_comptable, doc.identifiant, doc._id], [doc.date_facturation, doc.origines, doc.total_ttc, doc.statut, doc.numero_archive, doc.numero_interloire, doc.total_ht, doc.declarant.nom, doc.date_paiement]);  
}
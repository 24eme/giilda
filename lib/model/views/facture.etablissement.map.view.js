function(doc) {
  if (doc.type != "Facture") {
    return;
  }
  emit([doc.client_identifiant, doc.versement_comptable, doc._id], [doc.date_emission, doc.origines, doc.total_ttc, doc.statut]);
}


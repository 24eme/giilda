function(doc) {
  if (doc.type != "Facture") {
    return;
  }
  emit([doc.versement_comptable, doc.identifiant, doc._id], [doc.date_emission, doc.origines, doc.total_ttc, doc.statut]);
}


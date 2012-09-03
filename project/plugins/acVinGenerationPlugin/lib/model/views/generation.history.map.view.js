function(doc) {
  if (doc.type != "Generation") {
    return;
  }
  emit([doc.client_identifiant, doc._id], [doc.date_emission, doc.origines, doc.total_ttc]);
}


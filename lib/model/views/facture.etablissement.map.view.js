function(doc) {
  if (doc.type != "Facture") {
    return;
  }
  for(o in doc.origines) {
    emit([doc.client_identifiant, doc._id, o], doc);
  }
}


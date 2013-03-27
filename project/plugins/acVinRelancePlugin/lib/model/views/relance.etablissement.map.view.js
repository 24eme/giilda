function(doc) {
  if (doc.type != "Relance") {
    return;
  }
  emit([doc.identifiant, doc.type_relance, doc.reference, doc.date_creation], [doc.origines]);
}

function(doc) {
  if (doc.type && doc.type != "Compte") {
	return ;
  }
  for (type in doc.tags) {
    for(idtag in doc.tags[type]) {
	    emit([type, doc.tags[type][idtag]], doc.origines);
    }
  }
}

function(doc) {
  if (doc.type && doc.type != "Compte") {
	return ;
  }

  var getLibelleWithAdresse = function(doc) {
    var libelle = doc.nom_a_afficher;
    if (doc.adresse||doc.adresse_complementaire||doc.code_postal||doc.commune||doc.pays) {
        libelle += ' —';
    }
    if (doc.adresse) {
       libelle += ' '+doc.adresse;
    }
    if (doc.adresse_complementaire) {
       libelle +=  ' '+doc.adresse_complementaire;
    }
    if (doc.code_postal) {
       libelle += ' '+doc.code_postal;
    }
    if (doc.commune) {
       libelle += ' '+doc.commune;
    }
    if (doc.pays) {
       libelle += ' ('+doc.pays+')';
    }
    return libelle;
  }

  for (type in doc.tags) {
    for(idtag in doc.tags[type]) {
      var libelle = getLibelleWithAdresse(doc);
	    emit([type, doc.tags[type][idtag], libelle], doc.origines);
    }
  }
}

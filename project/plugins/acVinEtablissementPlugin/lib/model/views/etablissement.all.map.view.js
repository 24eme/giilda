function(doc) {
  	if (doc.type != "Etablissement") {
        return;
    }

    liaisons = array();
    if (doc.liaisons_operateurs) {
        for (key in doc.liaisons_operateurs) {
            liaisons.push(doc.liaisons_operateurs[key].id_etablissement.replace("ETABLISSEMENT-", ""));
        }
    }

    emit([doc.interpro, doc.statut, doc.famille, doc.id_societe, doc._id, doc.nom, doc.identifiant, doc.cvi, doc.region], [doc.raison_sociale, doc.siege.adresse, doc.siege.commune, doc.siege.code_postal, doc.no_accises, doc.carte_pro, doc.email, doc.telephone, doc.fax, doc.recette_locale.id_douane, doc.recette_locale.nom, liaisons.join('|')]);
    if (doc.cooperative && doc.cooperative != "0") {
	    emit([doc.interpro, doc.statut, "COOPERATIVE", doc.id_societe, doc._id, doc.nom, doc.identifiant, doc.cvi, doc.region], [doc.raison_sociale, doc.siege.adresse, doc.siege.commune, doc.siege.code_postal, doc.no_accises, doc.carte_pro, doc.email, doc.telephone, doc.fax, doc.recette_locale.id_douane, doc.recette_locale.nom, liaisons.join('|')]);
    }
}

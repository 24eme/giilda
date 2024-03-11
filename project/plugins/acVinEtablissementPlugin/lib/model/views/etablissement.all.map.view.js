function(doc) {
  	if (doc.type != "Etablissement") {

        return;
    }

    emit([doc.interpro, doc.statut, doc.famille, doc.id_societe, doc._id, doc.nom, doc.identifiant, doc.cvi, doc.region], [doc.raison_sociale, doc.adresse, doc.commune, doc.code_postal, doc.no_accises, doc.carte_pro, doc.email, doc.telephone, doc.fax, doc.recette_locale.id_douane, doc.recette_locale.nom]);
}

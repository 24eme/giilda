function(doc) {
  	if (doc.type != "Etablissement") {

        return;
    }

    var exclu = null;
    if (doc.exclusion_stats) {
      exclu = "OUI";
    }

    emit([doc.interpro, doc.statut, doc.famille, doc.id_societe, doc._id, doc.nom, doc.identifiant, doc.cvi, doc.region], [doc.raison_sociale, doc.siege.adresse, doc.siege.commune, doc.siege.code_postal, doc.no_accises, doc.carte_pro, doc.email, doc.telephone, doc.fax, doc.recette_locale.id_douane, doc.recette_locale.nom, doc.mois_stock_debut, doc.num_interne, doc.siege.adresse_complementaire, exclu]);
}

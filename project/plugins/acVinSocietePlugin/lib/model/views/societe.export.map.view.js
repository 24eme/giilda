function(doc) {
    if (doc.type != "Societe") {
        
        return;
    }

    cooperative = "NON";
    if (doc.cooperative) {
        cooperative = "OUI";
    }

    emit([doc.interpro, 
          doc.statut, 
          doc.type_societe,
          doc._id, 
          doc.identifiant], 
         [doc.code_comptable_client, 
          doc.code_comptable_fournisseur,
          doc.raison_sociale,
          doc.raison_sociale_abregee,
          cooperative,
          doc.siret,
          doc.code_naf,
          doc.no_tva_intracommunautaire,
          doc.enseignes.join("|"),
          doc.siege.adresse,
          doc.siege.code_postal,
          doc.siege.commune,
          doc.siege.pays
          ]);
}

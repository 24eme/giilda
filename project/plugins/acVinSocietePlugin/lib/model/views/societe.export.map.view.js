function(doc) {
     if (doc.type != "Societe") {

         return;
     }

     cooperative = "NON";
     if (doc.cooperative && doc.cooperative!="0") {
         cooperative = "OUI";
     }

     type_fournisseur = null;
     if (doc.type_fournisseur) {
       type_fournisseur = doc.type_fournisseur.join("|");
     }

     enseignes = null;
     if (doc.enseignes) {
       enseignes = doc.enseignes.join("|")
     }

     adresse = null;
     if (doc.siege.adresse) {
       adresse = doc.siege.adresse.split(",").join(" - ");
     }

     adresse_complementaire = null;
     if (doc.siege.adresse_complementaire) {
       adresse_complementaire = doc.siege.adresse_complementaire.split(",").join(" - ")
     }

     emit([doc.interpro,
           doc.statut,
           doc.type_societe,
           doc._id,
           doc.identifiant],
          [doc.code_comptable_client,
           doc.code_comptable_fournisseur,
           type_fournisseur,
           doc.raison_sociale,
           doc.raison_sociale_abregee,
           cooperative,
           doc.siret,
           doc.code_naf,
           doc.no_tva_intracommunautaire,
           enseignes,
           adresse,
           adresse_complementaire,
           doc.siege.code_postal,
           doc.siege.commune,
           doc.siege.pays,
           doc.telephone,
           doc.fax,
           doc.email
           ]);
 }

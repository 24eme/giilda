function(doc) {
     if (!doc.type || doc.type != "Compte") {
   	   return ;
     }
     if(!doc.mot_de_passe) {
	      return ;
     }
     var login = doc.identifiant;
     if(doc.login) {
       login = doc.login;
     }
     emit([login], doc.mot_de_passe);
     if (doc.alternative_logins) {
         for (key in doc.alternative_logins) {
	     var login = doc.alternative_logins[key];
	     if(login) {
             emit([login], doc.mot_de_passe);
	     }
         }
     }
  }

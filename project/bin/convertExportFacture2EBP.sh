echo "Document - N° document;Document - Date;Document - Code client;Document - Nom du client;Ligne - Code article;Ligne - Description commerciale en clair;Ligne - Quantité;Ligne - Taux de TVA;Ligne - PV HT"

cat | grep ";LIGNE;" | awk -F ";" '
function trunc(len, str) { return sprintf("%-" len "s", sprintf("%." len "s", str)) }
{
    numero_facture = sprintf("FA%08d", $4);
    date_facturation = gensub(/([0-9]{4})-([0-9]{2})-([0-9]{2})/, "\\3/\\2/\\1", "g", $2);
    code_comptable = $25
    raison_sociale = $16
    code_article = "DRM15"
    libelle = $5
    if($18) {
        libelle = libelle "\\n" $18
        code_article = "VRAC15"
    }
    quantite=$21
    tauxtva=20
    prixunitaireht=$22
    print numero_facture ";" date_facturation ";" code_comptable ";" raison_sociale ";" code_article ";" libelle ";" quantite ";" tauxtva ";" prixunitaireht
}' | grep -E "^FA[0-9]+"

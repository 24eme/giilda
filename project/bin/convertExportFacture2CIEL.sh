cat | awk -F ";" '
function trunc(len, str) { return sprintf("%-" len "s", sprintf("%." len "s", str)) }
{
    numero_facture = trunc(7, $4);
    code_journal = trunc(2, $1);
    date_facturation = trunc(8, gensub(/-/, "", "g", $2));
    date_echeance = trunc(8, gensub(/-/, "", "g", $9));
    if(!$9) { date_echeance = date_facturation } compte = trunc(8, $6);
    if($7) { compte = trunc(8, $7) } libelle = trunc(18, "Facture no " numero_facture);
    montant = trunc(12, $11); sens = trunc(1, $10);
    ressortissant = trunc(30, $16);
    libelle_complet = trunc(55, $5);
    type_ligne = trunc(10, $15);
    print numero_facture code_journal date_facturation date_echeance trunc(12,"") compte trunc(3, "") libelle trunc(2, "") montant sens trunc(5, "") ressortissant trunc(3, "") libelle_complet trunc(3, "") type_ligne
}' | grep -E "^[0-9]+"

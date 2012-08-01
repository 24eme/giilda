<?php
$fileName = 'test';
$ext = 'tex';

$cmdCompileLatex = 'pdflatex -synctex=1 -interaction=nonstopmode ./data/'.$fileName.'.'.$ext;

//
//shell_exec($cmdCompileLatex);
//echo '<pre>'.$cmdCompileLatex.'</pre>';
//exit;

$statut = creerFichier('/data',$fileName,$ext,  $srcPdf);

$output = shell_exec($cmdCompileLatex);


echo "<pre>$output</pre>"; exit;

function creerFichier($fichierChemin, $fichierNom, $fichierExtension, $fichierContenu, $droit=""){
$fichierCheminComplet = $_SERVER["DOCUMENT_ROOT"].$fichierChemin."/".$fichierNom;
if($fichierExtension!=""){
$fichierCheminComplet = $fichierCheminComplet.".".$fichierExtension;
}
 
// création du fichier sur le serveur
$leFichier = fopen($fichierCheminComplet, "w");
fwrite($leFichier, html_entity_decode(htmlspecialchars_decode($fichierContenu),HTML_ENTITIES));
fclose($leFichier);
 
// la permission
if($droit==""){
$droit="0777";
}
 
// on vérifie que le fichier a bien été créé
$t_infoCreation['fichierCreer'] = false;
if(file_exists($fichierCheminComplet)==true){
$t_infoCreation['fichierCreer'] = true;
}
 
// on applique les permission au fichier créé
$retour = chmod($fichierCheminComplet,intval($droit,8));
$t_infoCreation['permissionAppliquer'] = $retour;
 
return $t_infoCreation;
}
?>

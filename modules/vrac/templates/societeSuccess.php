<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal">

    <h2 class="titre_societe">
        Espace de <?php echo $societe->raison_sociale; ?>
    </h2>
    <div class="clearfix">
        <ul class="stats_contrats">
            <li>
                <?php echo $contratsSocietesWithInfos->infos->attente_signature; ?> contrats à signer
            </li>
            <li>
                <?php echo $contratsSocietesWithInfos->infos->brouillon; ?> contrats en brouillon
            </li>
            <li>
                <?php echo $contratsSocietesWithInfos->infos->valide; ?> contrats validés
            </li>
        </ul>

        <div id="etablissement_<?php echo $etablissementPrincipal->identifiant; ?>" class="infos_etablissement">
            <div id="num_etb">
                <span>N° :</span> <?php echo $societe->identifiant; ?>
            </div>
            <div id="cp_etb">
                <span>Code postal :</span> <?php echo $societe->siege->code_postal; ?>
            </div>
            <div id="commune_etb">
                <span>Commune :</span> <?php echo $societe->siege->commune; ?>
            </div>
        </div>

    </div>

     <?php include_partial('teledeclarationActionsButtons', array('compte' => $compte, 'etablissementPrincipal' => $etablissementPrincipal, 'societe' => $societe)); ?>

    

    <?php include_partial('contratsTable', array('contrats' => $contratsSocietesWithInfos->contrats, 'societe' => $societe)); ?>


   <?php include_partial('teledeclarationActionsButtons', array('compte' => $compte, 'etablissementPrincipal' => $etablissementPrincipal, 'societe' => $societe)); ?>


</section>

<?php

include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));

?>
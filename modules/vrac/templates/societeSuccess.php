<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal">

    <h2 class="titre_societe">
        Espace de <?php echo $societe->raison_sociale; ?>
    </h2>
    <div>
        <div id="actions_etablissement_<?php echo $etablissementPrincipal->identifiant; ?>" class="">           
            <div id="nb_contrat_a_signe">
                <?php echo $contratsSocietesWithInfos->infos->attente_signature; ?> contrats à signer 
            </div>
            <div id="nb_contrat_brouillon">
                <?php echo $contratsSocietesWithInfos->infos->brouillon; ?> contrats en brouillon
            </div>
            <div id="nb_contrat_valide">
                <?php echo $contratsSocietesWithInfos->infos->valide; ?> contrats validés
            </div>
        </div>

        <div id="etablissement_<?php echo $etablissementPrincipal->identifiant; ?>" class="">
            <h3><?php echo $societe->raison_sociale; ?></h3>
            <ul id="liste_statuts_nb" class="">    

            </ul>
            <div id="num_etb">
                N° <?php echo $societe->identifiant; ?>
            </div>
            <div id="cp_etb">
                Code postal: <?php echo $societe->siege->code_postal; ?>
            </div>
            <div id="commune_etb">
                Commune: <?php echo $societe->siege->commune; ?>
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
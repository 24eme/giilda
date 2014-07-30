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
            <?php if($contratsSocietesWithInfos->infos->attente_signature > 0) :?>
            <li>
                <?php echo $contratsSocietesWithInfos->infos->attente_signature; ?> contrat(s) à signer
            </li>
            <?php endif; ?>
            <?php if($contratsSocietesWithInfos->infos->brouillon > 0) :?>
            <li>
                <?php echo $contratsSocietesWithInfos->infos->brouillon; ?> contrat(s) en brouillon
            </li>
            <?php endif; ?>
            <?php if($contratsSocietesWithInfos->infos->valide > 0) :?>
            <li>
                <?php echo $contratsSocietesWithInfos->infos->valide; ?> contrat(s) validé(s) (en attente de Visa)
            </li>
            <?php endif; ?>
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
    <div class="ligne_btn txt_droite">
    <?php if($etablissementPrincipal->isCourtier() || $etablissementPrincipal->isNegociant()): ?>      
    <a class="btn_orange btn_majeur" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissementPrincipal->identifiant)); ?>">
        Saisir Un Nouveau contrat
    </a>
    <?php endif; ?>
    </div>

    <?php include_partial('contratsTable', array('contrats' => $contratsSocietesWithInfos->contrats, 'societe' => $societe)); ?>

    <div class="ligne_btn txt_droite">     
        <a class="btn_majeur" href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'all')); ?>">
        Voir tout l'historique
    </a>
    </div>
   

</section>

<?php

include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));

?>
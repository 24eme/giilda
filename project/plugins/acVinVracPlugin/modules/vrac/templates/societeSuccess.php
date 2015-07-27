<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal" class="contrat_teledeclaration_societe">

    <h2 class="titre_societe titre_societe_teledeclaration">
        Espace contrat de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)
    </h2>
    <?php include_partial('vrac/bloc_statuts_contrats',array('societe' => $societe, 'contratsSocietesWithInfos' => $contratsSocietesWithInfos, 'etablissementPrincipal' => $etablissementPrincipal)) ?>
    
    
    <div class="btn_block">
        <a class="btn_majeur lien_history" href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous')); ?>">
            Voir tout l'historique
        </a>
        <?php if ($etablissementPrincipal->isCourtier() || $etablissementPrincipal->isNegociant()): ?>      
            <a class="btn_orange btn_majeur lien_nouveau" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissementPrincipal->identifiant)); ?>">
                Saisir Un Nouveau contrat
            </a>
        <?php endif; ?>
    </div>

    <?php include_partial('contratsTable', array('contrats' => $contratsSocietesWithInfos->contrats, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'limit' => 10)); ?>


    <?php include_partial('popup_notices'); ?> 

</section>


<?php
include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
?>
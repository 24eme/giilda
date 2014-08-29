<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal" class="contrat_teledeclaration_societe">

    <h2 class="titre_societe titre_societe_teledeclaration">
        Espace de <?php echo $societe->raison_sociale; ?>
    </h2>
    <div class="clearfix">
        <ul class="stats_contrats">
            <?php
            $action_size_class = ' actions_3 ';
            if ($societe->isViticulteur() || $societe->isCourtier()):
                $action_size_class = ' actions_2 ';
            endif;
            if (!$societe->isViticulteur()):
                ?>
                <li class="stats_contrats_brouillon <?php echo $action_size_class; ?>"> 
                    <div class="action <?php echo ($contratsSocietesWithInfos->infos->brouillon) ? "actif" : ""; ?>">
                        <h2>  Brouillon </h2>
                        <?php if ($contratsSocietesWithInfos->infos->brouillon): ?>
                            <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_CONTRAT_BROUILLON))) ?>">
                            <?php endif; ?>
                            <?php echo $contratsSocietesWithInfos->infos->brouillon; ?> contrat(s) en brouillon
                            <?php if ($contratsSocietesWithInfos->infos->brouillon): ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>
            <?php
            if (!$societe->isCourtier()):
                ?>
                <li class="stats_contrats_a_signer <?php echo $action_size_class; ?>">
                    <div class="action <?php echo ($contratsSocietesWithInfos->infos->a_signer) ? "actif" : ""; ?>">
                        <h2>  A Signer </h2>
                        <?php if ($contratsSocietesWithInfos->infos->a_signer): ?>
                            <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_MOI))) ?>">
                            <?php endif; ?>
                            <?php echo $contratsSocietesWithInfos->infos->a_signer; ?> contrat(s) à signer
                            <?php if ($contratsSocietesWithInfos->infos->a_signer): ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>
            <li class="stats_contrats_en_attente <?php echo $action_size_class; ?>">
                <div class="action <?php echo ($contratsSocietesWithInfos->infos->en_attente) ? "actif" : ""; ?>">
                    <h2>  En Attente </h2>
                    <?php if ($contratsSocietesWithInfos->infos->en_attente): ?>
                        <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_AUTRES))) ?>">
                        <?php endif; ?>
                        <?php echo $contratsSocietesWithInfos->infos->en_attente; ?> contrat(s) en attente
                        <?php if ($contratsSocietesWithInfos->infos->en_attente): ?>
                        </a>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    </div>
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



</section>

<?php
include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
?>
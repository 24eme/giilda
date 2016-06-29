<?php
use_helper('Vrac');
use_helper('Float');
?>
<ol class="breadcrumb">
    <li><a href="<?php echo url_for('vrac_societe', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="active">Contrats</a></li>
</ol>

<section id="principal" class="vrac">

    <h2 class="titre_societe titre_societe_teledeclaration">
        Espace contrats de <?php echo $societe->raison_sociale; ?>
    </h2>
    <br/>
        <div class="row">
            <?php
            $panel_size = ' col-xs-4 ';
            if ($societe->isViticulteur() || $societe->isCourtier()):
                $panel_size = ' col-xs-6 ';
              endif;
            if (!$societe->isViticulteur()):
                ?>
                <div class="<?php echo $panel_size; ?>" >
                <div class="panel panel-default">
                    <div class="panel-heading <?php echo ($contratsSocietesWithInfos->infos->brouillon) ? "actif" : ""; ?>">Brouillon</div>
                        <div class="panel-body">
                        <?php if ($contratsSocietesWithInfos->infos->brouillon): ?>
                            <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_CONTRAT_BROUILLON))) ?>">
                            <?php endif; ?>
                            <?php echo $contratsSocietesWithInfos->infos->brouillon; ?> contrat(s) en brouillon
                            <?php if ($contratsSocietesWithInfos->infos->brouillon): ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                </div>
            <?php endif; ?>
            <?php if (!$societe->isCourtier()): ?>
            <div class="<?php echo $panel_size; ?>" >
                <div class="panel panel-default">
                    <div class="panel-heading  <?php echo ($contratsSocietesWithInfos->infos->a_signer) ? "actif" : ""; ?>">A Signer</div>
                        <div class="panel-body">
                        <?php if ($contratsSocietesWithInfos->infos->a_signer): ?>
                            <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_MOI))) ?>">
                            <?php endif; ?>
                            <?php echo $contratsSocietesWithInfos->infos->a_signer; ?> contrat(s) à signer
                            <?php if ($contratsSocietesWithInfos->infos->a_signer): ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="<?php echo $panel_size; ?>" >
            <div class="panel panel-default">
                <div class="panel-heading  <?php echo ($contratsSocietesWithInfos->infos->en_attente) ? "actif" : ""; ?>">En Attente</div>
                    <div class="panel-body">
                    <?php if ($contratsSocietesWithInfos->infos->en_attente): ?>
                        <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_AUTRES))) ?>">
                        <?php endif; ?>
                        <?php echo $contratsSocietesWithInfos->infos->en_attente; ?> contrat(s) en attente
                        <?php if ($contratsSocietesWithInfos->infos->en_attente): ?>
                        </a>
                    <?php endif; ?>
                </div>
                  </div>
            </div>
        </div>

    <div class="row">
      <div class="col-xs-12">
        <a class="btn btn-default" href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous')); ?>">
            Voir tout l'historique
        </a>
        <?php if ($etablissementPrincipal->isCourtier() || $etablissementPrincipal->isNegociant()): ?>
            <a class="btn btn-warning pull-right" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etablissementPrincipal->identifiant)); ?>">
                Saisir Un Nouveau contrat
            </a>
        <?php endif; ?>
    </div>
</div>
<br/>
    <?php include_partial('contratsTable', array('contrats' => $contratsSocietesWithInfos->contrats, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'limit' => 10)); ?>


    <?php include_partial('popup_notices'); ?>

</section>


<?php
include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
?>

<!-- #principal -->
<div id="principal" class="clearfix">
    <h2 class="titre_societe">Espace de télédéclaration de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)</h2>

    <div id="mon_espace" >
        <?php if ($hasTeledeclarationVrac && $hasTeledeclarationDrm): ?> 
            <div class="cols">

                <div class="col_50">
                <?php endif; ?>
                <?php if ($hasTeledeclarationVrac): ?>
                    <div class="block_teledeclaration espace_contrat">
                        <div class="title">ESPACE CONTRAT</div>
                        <div class="panel">
                            <?php include_partial('vrac/bloc_statuts_contrats', array('societe' => $societe, 'contratsSocietesWithInfos' => $contratsSocietesWithInfos, 'etablissementPrincipal' => $etablissement,'accueil' => true)) ?>

                            <div class="acces">
                                <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>" class="btn_majeur">Acceder aux contrats</a>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
                <?php if ($hasTeledeclarationVrac && $hasTeledeclarationDrm): ?>
                </div>
                <div class="col_50">
                <?php endif; ?>
                <?php if ($hasTeledeclarationDrm): ?>
                    <?php include_component('drm', 'monEspaceDrm', array('etablissement' => $etablissement, 'campagne' => $campagne, 'isTeledeclarationMode' => $isTeledeclarationMode, 'btnAccess' => true, 'accueil_drm' => false)); ?>

                <?php endif; ?> 
                <?php if ($hasTeledeclarationVrac && $hasTeledeclarationDrm): ?> 
                </div>
            </div>
        <?php endif; ?> 

    </div>
<?php
     include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissement, 'isTeledeclarationMode' => true));
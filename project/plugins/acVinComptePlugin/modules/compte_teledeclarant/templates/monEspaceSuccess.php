<!-- #principal -->
<div id="principal" class="clearfix">
    <h2 class="titre_societe">Espace de télédéclaration de <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant; ?>)</h2>

    <div id="mon_espace" >
                <?php if ($hasTeledeclarationVrac): ?>
                  <?php if ($nbTeledeclarations > 1): ?><div class="cols"><div class="col_50"><?php endif; ?>
                    <div class="block_teledeclaration espace_contrat">
                        <div class="title">ESPACE CONTRAT</div>
                        <div class="panel">
                            <?php include_partial('vrac/bloc_statuts_contrats', array('societe' => $societe, 'contratsSocietesWithInfos' => $contratsSocietesWithInfos, 'etablissementPrincipal' => $etablissement,'accueil' => true)) ?>

                            <div class="acces">
                                <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>" class="btn_majeur">Acceder aux contrats</a>
                            </div>
                        </div>
                    </div>
                    <?php if ($nbTeledeclarations > 1): ?></div><?php endif; ?>
                <?php endif; ?>
                <?php if ($hasTeledeclarationDrm): ?>
                  <?php if ($nbTeledeclarations > 1): ?><div class="col_50"><?php endif; ?>
                    <?php include_component('drm', 'monEspaceDrm', array('etablissement' => $etablissement, 'campagne' => $campagne, 'isTeledeclarationMode' => $isTeledeclarationMode, 'btnAccess' => true, 'accueil_drm' => false)); ?>
                    <?php if ($nbTeledeclarations > 1): ?></div><?php endif; ?>
                    <?php if ($nbTeledeclarations > 2): ?></div><?php endif; ?>
                <?php endif; ?>
                <?php if ($hasTeledeclarationFacture): ?>
                  <?php if ($nbTeledeclarations%2): ?>
                  <br/>
                  <?php endif; ?>
                  <?php if (!($nbTeledeclarations%2)): ?><br/><div class="col_50"><?php endif; ?>
                  <div class="block_teledeclaration espace_facture">
                    <div class="title">ESPACE FACTURE</div>
                    <div class="panel">
                      <div class="etablissements_drms">
                        <?php include_partial('facture/bloc_factures', array('societe' => $societe, 'facturesSocietesWithInfos' => $facturesSocietesWithInfos, 'etablissementPrincipal' => $etablissement,'accueil' => true)) ?>
                      </div>
                      <div class="acces">
                        <a href="<?php echo url_for('facture_teledeclarant', array('identifiant' => $identifiant)); ?>" class="btn_majeur">Acceder aux factures</a>
                      </div>
                    </div>
                  </div>
                  <?php if (!($nbTeledeclarations%2)): ?></div><?php endif; ?>
                <?php endif; ?>

                <?php if ($hasTeledeclarationDrev): ?>
                  <?php if ($nbTeledeclarations%2): ?>
                  <br/>
                  <?php endif; ?>
                  <?php if (!($nbTeledeclarations%2)): ?><div class="col_50"><?php endif; ?>
                  <?php
                  $url = null;
                  if($sf_user->getCompte()){
                    $url="/odg/declarations/".$sf_user->getCompte()->getIdentifiant()."?usurpation=".intval($sf_user->isUsurpationCompte())."&login=".$sf_user->getCompte()->getSociete()->getMasterCompte()->identifiant;
                  }else{
                    $url="/odg/declarations/".$etablissement->identifiant."?usurpation=".intval($sf_user->isUsurpationCompte())."&login=".$etablissement->getSociete()->getMasterCompte()->identifiant;
                  }
                  ?>
                  <div class="block_teledeclaration espace_drev">
                    <div class="title">ESPACE DREV</div>
                    <div class="panel">



                    <ul style="" class="etablissements_drms">
                      <li>
                          <div class="etablissement_drms">
                              <h2> Drev <?php echo date('Y'); ?> </h2>
                              <ul class="block_drm_espace">
                                  <li class="statut_toCreate">
                                    <a class="drm_nouvelle_teledeclaration" href="<?php echo $url; ?>"><span class="center">Acceder à la drev de <?php echo date('Y'); ?></span></a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                    </ul>

                        <div class="acces">
                          <a href="<?php echo $url; ?>" class="btn_majeur">Acceder aux drev</a>
                        </div>
                      </div>
                    </div>

                <?php endif; ?>
                <?php if ($nbTeledeclarations%2): ?>
                </div>
                </div>
                <?php endif; ?>
    </div>
</div>
<?php
     include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissement, 'isTeledeclarationMode' => true));

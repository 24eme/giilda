    <div class="clearfix">
        <ul class="stats_contrats">
            <?php
            $action_size_class = ' actions_3 ';
            if ($societe->isViticulteur() || $societe->isCourtier()):
                $action_size_class = ' actions_2 ';
            endif;
              if ($societe->isNegociant() && $societe->getMasterCompte()->hasDroit(Roles::TELEDECLARATION_DRM) && isset($accueil) && $accueil):
                $action_size_class = ' actions_2 ';
            endif;
            if (!$societe->isViticulteur()):
                ?>
                <li class="stats_contrats_brouillon <?php echo $action_size_class; ?>"> 
                    <div class="action <?php echo ($contratsSocietesWithInfos->infos->brouillon) ? "actif" : ""; ?>">
                        <h2><img src="/images/pictos/pi_fichier_brouillon.png" width="40" height="40"/><span>Brouillon</span></h2>
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
                        <h2><img src="/images/pictos/pi_stylo_a_signer.png" width="40" height="40"/><span>À Signer</span></h2>
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
                    <h2><img src="/images/pictos/pi_contrat_en_attente.png" width="40" height="40"/><span>En Attente</span></h2>
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

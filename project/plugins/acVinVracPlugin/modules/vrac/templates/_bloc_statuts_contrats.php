
    <ul class="list-group">
        <?php
        if (!$societe->isViticulteur()):
            ?>
            <li class="list-group-item ">
                 <div class="row" >
              <h4><img src="/images/pictos/pi_fichier_brouillon.png" width="40" height="40"/><span>Brouillon</span></h4>
            </div>
                <div class="col-xs-12" <?php echo (!$contratsSocietesWithInfos->infos->brouillon) ? "style='opacity:0.5'" : ""; ?>>
                    <?php if ($contratsSocietesWithInfos->infos->brouillon): ?>
                        <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_CONTRAT_BROUILLON))) ?>">
                        <?php endif; ?>
                        <?php echo $contratsSocietesWithInfos->infos->brouillon; ?> contrat(s) en brouillon
                        <?php if ($contratsSocietesWithInfos->infos->brouillon): ?>
                        </a>
                    <?php endif; ?>
                </div>
                </div>
            </li>
        <?php endif; ?>
        <?php
        if (!$societe->isCourtier()):
            ?>
            <li class="list-group-item ">
               <div class="row text-center" >
              <h4 ><img src="/images/pictos/pi_stylo_a_signer.png" width="40" height="40"/><span>A Signer</span></h4>
  </div>
                <div class="row text-center" >
                <div class="col-xs-12" <?php echo (!$contratsSocietesWithInfos->infos->a_signer) ? "style='opacity:0.5'" : ""; ?>>
                    <?php if ($contratsSocietesWithInfos->infos->a_signer): ?>
                        <a href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_MOI))) ?>">
                        <?php endif; ?>
                        <?php echo $contratsSocietesWithInfos->infos->a_signer; ?> contrat(s) à signer
                        <?php if ($contratsSocietesWithInfos->infos->a_signer): ?>
                        </a>
                    <?php endif; ?>
                </div>
               </div>
            </li>
        <?php endif; ?>
        <li class="list-group-item ">
          <div class="row text-center" >
          <h4><img src="/images/pictos/pi_contrat_en_attente.png" width="40" height="40"/><span>En Attente</span></h4>
        </div>
        <div class="row text-center" >
            <div class="col-xs-12" <?php echo (!$contratsSocietesWithInfos->infos->en_attente) ? "style='opacity:0.5'" : ""; ?>>
                <?php if ($contratsSocietesWithInfos->infos->en_attente): ?>
                    <a  href="<?php echo url_for('vrac_history', array('identifiant' => $etablissementPrincipal->identifiant, 'campagne' => ConfigurationClient::getInstance()->getCurrentCampagne(), 'etablissement' => 'tous', 'statut' => strtolower(VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_AUTRES))) ?>">
                    <?php endif; ?>
                    <?php echo $contratsSocietesWithInfos->infos->en_attente; ?> contrat(s) en attente
                    <?php if ($contratsSocietesWithInfos->infos->en_attente): ?>
                    </a>
                <?php endif; ?>
            </div>
          </div>
        </li>
    </ul>

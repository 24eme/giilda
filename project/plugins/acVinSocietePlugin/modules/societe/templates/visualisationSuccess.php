<?php
use_helper('Display');
?>
<!-- #principal -->
<section id="principal">
  <p id="fil_ariane"><a href="<?php echo url_for('societe'); ?>">Page d'accueil</a> &gt; <strong><?php echo $societe->raison_sociale; ?></strong></p>

  <!-- #contenu_etape -->
  <section id="contacts">

    <div id="visu_societe">
      <h2><?php echo $societe->raison_sociale; ?></h2>

      <div class="btn_haut">
        <?php if ($modification || $reduct_rights) : ?>
          <a href="<?php echo url_for('compte_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel interlocuteur</a>
          &nbsp;
          <?php if (!$reduct_rights && $societe->canHaveChais()) : ?>
            <a href="<?php echo url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel Etablissement</a>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <?php include_partial('visualisationPanel', array('societe' => $societe, 'modification' => $modification, 'reduct_rights' => $reduct_rights)); ?>

      <?php if ($societe->getMasterCompte()->exist('droits') && $societe->getMasterCompte()->hasDroit(Roles::TELEDECLARATION)): ?>
        <div id="detail_societe_coordonnees" class="form_section ouvert">
          <h3>Télédeclaration</h3>
          <div class="form_contenu">
            <div class="form_ligne">
              <label for="teledeclaration_login" class="label_liste">
                Login :
              </label>
              <?php echo $societe->identifiant; ?>
            </div>
            <?php
            if ($societe->isTransaction()):
              if ($societe->getEtablissementPrincipal() && $societe->getEtablissementPrincipal()->getEmailTeledeclaration() && $societe->getMasterCompte()->isTeledeclarationActive()) :
                ?>
                <div class="form_ligne">
                  <label for="teledeclaration_email" class="label_liste">
                    Email :
                  </label>
                  <?php echo $societe->getEtablissementPrincipal()->getEmailTeledeclaration(); ?>
                </div>
              <?php endif; ?>
            <?php else: ?>
              <?php if ($societe->getEmailTeledeclaration() && $societe->getMasterCompte()->isTeledeclarationActive()) :
                ?>
                <div class="form_ligne">
                  <label for="teledeclaration_email" class="label_liste">
                    Email :
                  </label>
                  <?php echo $societe->getEmailTeledeclaration(); ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>

            <?php if ($societe->getMasterCompte()->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU) : ?>
              <div class="form_ligne">
                <label for="teledeclaration_mot_de_passe" class="label_liste">
                  Code de création :
                </label>
                <?php echo str_replace('{TEXT}', '', $societe->getMasterCompte()->mot_de_passe); ?>
              </div>
          <?php elseif(preg_match('/\{OUBLIE\}/', $societe->getMasterCompte()->mot_de_passe)): ?>
                <div class="form_ligne">
                  <label for="teledeclaration_email" class="label_liste">
                    Code de création :
                  </label>
                  <?php $lien = 'https://'.sfConfig::get('app_routing_context_production_host').url_for("compte_teledeclarant_mot_de_passe_oublie_login", array("login" => $societe->identifiant, "mdp" => str_replace("{OUBLIE}", "", $societe->getMasterCompte()->mot_de_passe))); ?>
                  En procédure de mot de passe oublié
                </div>
                <pre>Lien de réinitialisation de mot de passe reçu dans le mail :
<?php echo $lien; ?></pre>
            <?php else: ?>
              <div class="form_ligne">
                <label for="teledeclaration_email" class="label_liste">
                  Code de création :
                </label>
                Compte déjà crée
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

      <div id="detail_societe_sepa" class="form_section ouvert">
        <h3>Informations bancaires <?php if($societe->exist("sepa") && $societe->sepa->exist("date_activation") && $societe->getOrAdd("sepa")->getOrAdd("date_activation")): ?><span class="btn_majeur btn_vert btn_label" style="">actif</span><?php else: ?><span class="btn_majeur btn_orange btn_label" style="">non actif</span><?php endif; ?></h3>
        <div class="form_contenu">
          <?php if($societe->getOrAdd("sepa")->getOrAdd("date_activation")): ?>
          <div class="form_ligne">
            <label for="teledeclaration_sepa_date_activation" class="label_liste">
              Date activation :
            </label>
            <?php echo  Date::francizeDate($societe->getSepaDateActivation()); ?>
          </div>
          <div class="form_ligne">
            <label for="teledeclaration_sepa_date_effectif" class="label_liste">
              Date effective :
            </label>
            <?php echo  Date::francizeDate($societe->getSepaDateEffectif()); ?>
          </div>
        <?php endif; ?>
        <?php
        if(!$societe->sepa->exist('date_activation') || ! $societe->sepa->date_activation): ?>
          <div class="form_ligne">
              <form method="POST" action="<?php echo url_for('societe_sepa_activate', $societe);?>">
                  <?php if ($societe->exist('sepa') && $societe->sepa->exist('date_saisie') && $societe->sepa->date_saisie) : ?>
                      <strong>Les informations bancaires ont été saisies par le ressortissant.<?php if($compta_rights): ?><br/>Activer le prélèvement automatique :<?php endif; ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;
                      <?php if($compta_rights): ?><input type="submit" class="btn_majeur btn_contact" value="Activer" style="float:right;" onclic0k='return confirm("Souhaitez-vous confirmer l&apos;activation des prélèvements automatiques pour cette société ?")' /><?php endif; ?>
                  <?php else: ?>
                      Les informations bancaires n'ont pas été saisies.
                   <?php endif; ?>
              </form>
          </div>
        <?php endif; ?>
        </div>
      </div>

      <div id="detail_societe_coordonnees" class="form_section ouvert">
        <h3>Coordonnées de la société</h3>
        <div class="form_contenu">
          <?php include_partial('compte/coordonneesVisualisation', array('compte' => $societe->getMasterCompte())); ?>
        </div>
      </div>

      <?php if (count($etablissements)): ?>
      <?php endif; ?>
      <?php
      foreach ($etablissements as $etablissementId => $etb) :
        include_partial('etablissement/visualisation', array('etablissement' => $etb->etablissement, 'ordre' => $etb->ordre, 'fromSociete' => true));
      endforeach;
      ?>
    </div>
  </section>
</section>

<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
  <h2>Action</h2>
  <div class="contenu">
    <div class="btnRetourAccueil">
      <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
    </div>
    <?php if ($modification) : ?>
      <div class="btnRetourAccueil">
        <a href="<?php echo url_for('compte_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel interlocuteur</span></a>
      </div>
      <?php if (!$reduct_rights && $societe->canHaveChais()) : ?>
        <div class="btnRetourAccueil">
          <a href="<?php echo url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel etablissement</span></a>
        </div>
      <?php endif; ?>
      <?php if (!$reduct_rights && $societe->getMasterCompte()->hasDroit(Roles::TELEDECLARATION_DREV_ADMIN)) : ?>
        <div class="btnConnexion">
            <a href="<?php echo url_for('compte_teledeclarant_debrayage', array('identifiant' => $societe->getMasterCompte()->identifiant)); ?>" class="btn_majeur lien_connexion"><span>Connexion à la télédecl.</span></a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
<?php
end_slot();
?>

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

      <div id="detail_societe_sepa" class="form_section ouvert">
        <h3>Informations bancaires <?php if($societe->getOrAdd("sepa")->getOrAdd("date_activation")): ?><span class="btn_majeur btn_vert btn_label" style="">actif</span><?php else: ?><span class="btn_majeur btn_orange btn_label" style="">non actif</span><?php endif; ?></h3>
        <div class="form_contenu">
          <div class="form_ligne">
            <label for="teledeclaration_sepa_nom_bancaire" class="label_liste">
              Nom bancaire :
            </label>
            <?php echo ($societe->exist('sepa') && $societe->sepa->exist('nom_bancaire'))? $societe->sepa->nom_bancaire : ""; ?>
          </div>
          <div class="form_ligne">
            <label for="teledeclaration_sepa_iban" class="label_liste">
              Iban :
            </label>
            <?php echo ($societe->exist('sepa') && $societe->sepa->exist('iban'))? formatIban($societe->sepa->iban) : ""; ?>
          </div>
          <div class="form_ligne">
            <label for="teledeclaration_sepa_bic" class="label_liste">
              Bic :
            </label>
            <?php echo ($societe->exist('sepa') && $societe->sepa->exist('bic'))? $societe->sepa->bic : ""; ?>
          </div>
          <div class="form_ligne">
              <form method="POST" action="<?php echo url_for('societe_sepa_activate', $societe);?>">
                <strong>Activer le prélèvement automatique :</strong>&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php if ($societe->exist('sepa') && $societe->sepa->getOrAdd('nom_bancaire') && $societe->sepa->getOrAdd('iban') && $societe->sepa->getOrAdd('bic') ) : ?>
                      <input type="submit" class="btn_majeur btn_contact" value="Activer" style="float:right;" onclick='return confirm("Souhaitez-vous confirmer l&apos;activation des prélèvements automatiques pour cette société ?")' />
                  <?php else: ?>
                      En attente de saisie par l'utilisateur
                   <?php endif; ?>
              </form>
          </div>
        </div>
      </div>

      <div id="detail_societe_coordonnees" class="form_section ouvert">
        <h3>Coordonnées de la société</h3>
        <div class="form_contenu">
          <?php include_partial('compte/coordonneesVisualisation', array('compte' => $societe->getMasterCompte())); ?>
        </div>
      </div>

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
    <?php endif; ?>
  </div>
</div>
<?php
end_slot();
?>

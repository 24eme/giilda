<?php
use_helper('Float');
$service_prelevement_ouvert = false;
?>
<!-- #principal -->
<section id="principal">
  <?php if($service_prelevement_ouvert): ?>
    <fieldset id="espace_prelevement">
      <div id="mon_espace">
        <div class="espace_prelevement">
          <div class="panel">
            <ul style="height: auto" class="societe_prelevement">
              <li style="height: auto">
                <div class="adhesion_prelevement">
                  <?php if(!$hasTeledeclarationPrevelement && !$societe->hasInfosSepa()): ?>
                  <h2>Adhérer au prélèvement automatique</h2>
                  <form method="post">
                      <?php echo $adhesionPrelevementForm->renderHiddenFields(); ?>
                      <?php echo $adhesionPrelevementForm->renderGlobalErrors(); ?>
                      <p>InterLoire vous propose un nouveau mode de règlement de vos factures : le <strong>prélèvement automatique.</strong>
                        <br/>Un paiement simple et confortable.</p>
                        <br/>
                      <ul>
                        <li><strong>Facile :</strong> le prélèvement intervient automatiquement à la date d’échéance de votre facture</li>
                        <li><strong>Pratique :</strong> vous vous libérez des formalités liées à vos règlements</li>
                        <li><strong>Sécurisé :</strong> vous évitez tout risque d’oubli, de retard ou d’aléas postaux</li>
                      </ul>
                      <br/>
                      <br/>
                      <?php echo $adhesionPrelevementForm['facture_adhesion_prelevement']->render(array('required' => 'true')); ?>
                      <label for="drm_legal_signature_terms">J'adhère au prélèvement automatique.</label>
                      <br/>
                      <div class="ligne_btn txt_droite">
                          <button  type="submit" class="btn_validation right" ><span>Continuer</span></button>
                      </div>
                  </form>
                <?php elseif($societe->hasInfosSepa()): ?>
                      <h2>VOTRE MANDAT DE PRÉLÈVEMENT AUTOMATIQUE</h2>
                      <br/>
                      <p style="padding-left: 35px;">
                        Vous avez souscrit au prélèvement automatique.<br/><br/>
                        Si vos coordonnées bancaires ont changé, cliquer ici pour régénérer un nouveau mandat SEPA.</p>
                      <div class="ligne_btn txt_droite">
                        <a href="<?php echo url_for('facture_sepa_visualisation',array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur" ><span>NOUVEAU MANDAT</span></a>
                      </div>
                <?php endif; ?>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </fieldset>
  <?php endif; ?>
    <?php
    include_partial('historiqueFactures', array('identifiant' => $identifiant, 'factures' => $factures, 'isTeledeclarationMode' => true, 'campagneForm' => $campagneForm));
    ?>
  </section>
  <!-- fin #principal -->

  <?php
  include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour_espace' => true));
  ?>

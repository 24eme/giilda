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
                  <h2>Pour adhérer au prélèvement automatique de vos Factures</h2>
                  <form method="post">
                      <?php echo $adhesionPrelevementForm->renderHiddenFields(); ?>
                      <?php echo $adhesionPrelevementForm->renderGlobalErrors(); ?>

                      <?php echo $adhesionPrelevementForm['facture_adhesion_prelevement']->render(array('required' => 'true')); ?>
                      <label for="drm_legal_signature_terms">Je m'engage fournir mes identifiants bancaires ainsi que mon RIB afin qu'Interloire puisse effectuer des prélèvements bancaires correspondant à mes factures.</label>
                      <br/>
                      <div class="ligne_btn txt_droite">
                          <button  type="submit" class="btn_validation right" ><span>Continuer</span></button>
                      </div>
                  </form>
                <?php elseif(!$hasTeledeclarationPrevelement && $societe->hasInfosSepa()): ?>
                      <h2>Vous avez rempli l'adhésion au prélèvement automatique de vos Factures</h2>
                      <br/>
                      <p>Cliquez sur "votre Sepa" pour accéder/éditer vos informations bancaires</p>
                      <div class="ligne_btn txt_droite">
                        <a href="<?php echo url_for('facture_sepa_visualisation',array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur" ><span>Votre Sepa</span></a>
                      </div>
                <?php elseif($hasTeledeclarationPrevelement):
                    $date_activation = $societe->getSepa()->getDateActivation();
                    $date_debut_facture = date("Y-m-t", strtotime(Date::addDelaiToDate('+30 days', $date_activation)));
                  ?>
                      <h2>Prélèvement automatique</h2>
                      <br/>
                      <p><strong>Vous avez adhéré au prélèvement automatique le <?php echo Date::francizeDate($date_activation); ?>.
                        <?php if($date_debut_facture > (date("Y-m-d"))): ?><br/>Celui-ci sera effectif pour les factures arrivant à échéance le <?php echo Date::francizeDate($date_debut_facture); ?> .<?php endif; ?></strong></p>
                      <div class="ligne_btn txt_droite">
                        <a href="<?php echo url_for('facture_sepa_visualisation',array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn_majeur" ><span>Votre Sepa</span></a>
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

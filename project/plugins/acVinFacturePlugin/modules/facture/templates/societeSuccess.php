<?php
use_helper('Float');
?>
<!-- #principal -->
<section id="principal">
  <?php if(!$hasTeledeclarationPrevelement): ?>
    <fieldset id="espace_prelevement">
      <div id="mon_espace">
        <div class="espace_prelevement">
          <div class="panel">
            <ul style="height: auto" class="societe_prelevement">
              <li style="height: auto">
                <div class="adhesion_prelevement">
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

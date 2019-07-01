<?php
use_helper('Float');
use_helper('Display');
?>
<section id="principal" class="drm">
    <div id="sepa">
        <div class="sepa_panel" style="min-height: 50px;">
          <div class="title"><span class="text">informations bancaires liées à votre société</span></div>
          <div class="panel">
            <div class="ligne_form">
                <label>Raison Sociale :</label>
                <strong><?php echo $societe->raison_sociale; ?></strong>
            </div>
            <div class="ligne_form">
              <label>Nom bancaire :</label>
                <?php echo $societe->sepa->nom_bancaire; ?>
            </div>
            <div class="ligne_form">
                <label>Iban :</label>
                <?php echo formatIban($societe->sepa->iban); ?>
            </div>
            <div class="ligne_form">
              <label>Bic :</label>
                <?php echo $societe->sepa->bic; ?>
            </div>
            <br/>
            <div id="btn_etape_dr">
              <a href="<?php echo url_for('facture_teledeclarant', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_etape_prec"><span>Retour à mon espace</span></a>
              <a style="margin-left: 70px;" href="<?php echo url_for('facture_sepa_pdf', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_majeur btn_pdf center" id="drm_pdf"><span>Télécharger le PDF</span></a>
              <a href="<?php echo url_for('facture_sepa_modification', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_majeur btn_orange " style="float: right;">Modifier</a>
            </div>
          </div>
          </div>
    </div>
  </section>

  <?php
  include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour_espace' => true));
  ?>

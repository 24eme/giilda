<?php
use_helper('Float');
use_helper('Display');
?>
<section id="principal" class="drm">
  <div id="sepa">
    <div class="sepa_panel" style="min-height: 50px;">
      <div class="title"><span class="text">MES COORDONNÉES BANCAIRES</span></div>
      <div class="panel">
        <div class="ligne_form">
          <label>Raison Sociale :</label>
          <strong><?php echo $societe->raison_sociale; ?></strong>
        </div>
        <div class="ligne_form">
          <label>Titulaire du compte : </label>
          XXXXX
        </div>
        <div class="ligne_form">
          <label>Iban :</label>
          XXXX XXXX XXXX XXXX XXXX XXX
        </div>
        <div class="ligne_form">
          <label>Bic :</label>
          XXXXXXXX
        </div>
        <br/>
        <p class="encart_sepa">Afin de finaliser votre adhésion au prélèvement automatique, merci de bien vouloir IMPRIMER LE MANDAT SEPA et de le retourner signé accompagné d’un RIB par voie postale au service Recouvrement d’InterLoire.<br/>
          A réception, nous vous confirmerons la date de votre premier prélèvement.</p>
          <br/>
          <div id="btn_etape_dr">
            <a href="<?php echo url_for('facture_teledeclarant', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_etape_prec"><span>Retour à mon espace</span></a>
            <a style="margin-left: 70px; background-position: 0 -78px;" href="<?php echo url_for('facture_sepa_pdf', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_vert btn_majeur btn_pdf center" id="drm_pdf"><span>IMPRIMER LE MANDAT SEPA</span></a>
            <a href="<?php echo url_for('facture_sepa_modification', array('identifiant' => $etablissementPrincipal->getIdentifiant())) ?>" class="btn_majeur btn_orange " style="float: right;">Modifier</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php
  include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour_espace' => true));
  ?>

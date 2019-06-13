<?php
use_helper('Float');
?>
<!-- #principal -->
<section id="principal">

    <?php
    include_partial('historiqueFactures', array('identifiant' => $identifiant, 'factures' => $factures, 'isTeledeclarationMode' => true, 'campagneForm' => $campagneForm));
    ?>
</section>
<!-- fin #principal -->

<?php
include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'retour_espace' => true));
?>

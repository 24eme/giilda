<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h1>La saisie est terminée !</h1>
<h2>N° d'enregistrement deu contrat   <span><?php echo $vrac['numero_contrat']; ?></span></h2>
<form id="vrac_recapitulatif" method="post" action="<?php echo url_for('vrac_soussigne') ?>">
 <div class="btnValidation">
    	<span>&nbsp;</span>
        <input class="btn_valider" type="submit" value="Terminer la saisie" />
    </div>

</form>
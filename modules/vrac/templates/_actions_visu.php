<div id="action" class="bloc_col">
        <h2>Action</h2>

        <div class="contenu">
            <div class="btnNouveau">
                <a href="<?php echo url_for('vrac_nouveau'); ?>" class="btn_majeur btn_noir"><span>Saisir nouveau</span></a>
            </div>
        </div>
        <div class="contenu">
            <div class="btnRetourAccueil">
                <a href="<?php echo url_for('vrac'); ?>" class="btn_majeur btn_noir"><span>Retour à l'accueil</span></a>
            </div>
        </div>
        <div class="contenu">
            <div class="btnNouveau">
                <a href="<?php echo url_for('vrac_recherche',array('identifiant' => preg_replace('/ETABLISSEMENT-/', '',$vrac->vendeur_identifiant))); ?>" class="btn_majeur btn_noir"><span>Historique vendeur</span></a>
            </div>
        </div>
        <div class="contenu">
            <div class="btnNouveau">
                <a href="<?php echo url_for('vrac_recherche',array('identifiant' => preg_replace('/ETABLISSEMENT-/', '',$vrac->acheteur_identifiant))); ?>" class="btn_majeur btn_noir"><span>Historique acheteur</span></a>
            </div>
        </div>
</div>
	

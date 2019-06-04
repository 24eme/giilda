<section id="principal">
    <h2>Annuaire de vos contacts</h2>

    <div class="fond">
        <div class="annuaire clearfix">
            <div class="bloc_annuaire">
                <div style="text-align: right; margin: 10px 0;">
                    <a href="<?php echo url_for('annuaire_selectionner', array('type' => 'recoltants', 'identifiant' => $etablissement->identifiant)) ?>" class="btn_vert btn_majeur">Ajouter un viticulteur</a>
                </div>

                <?php include_partial(
                    'annuaire/tableauAnnuaire',
                    ['personnalite' => 'Viticulteur', 'etablissement' => $etablissement, 'type' => 'recoltants', 'annuaire' => $annuaire]
                ) ?>

            </div>
            <?php if ($isCourtierResponsable): ?>
                <div class="bloc_annuaire">
                    <div style="text-align: right; margin: 10px 0;">
                        <a href="<?php echo url_for('annuaire_selectionner', array('type' => 'negociants', 'identifiant' => $etablissement->identifiant)) ?>" class="btn_vert btn_majeur">Ajouter un négociant</a>
                    </div>

                <?php include_partial(
                    'annuaire/tableauAnnuaire',
                    ['personnalite' => 'Négociant', 'etablissement' => $etablissement, 'type' => 'negociants', 'annuaire' => $annuaire]
                ) ?>

                </div>                
                <div class="bloc_annuaire">
                    <div style="text-align: right; margin: 10px 0;">
                        <a href="<?php echo url_for('annuaire_commercial_ajouter', array('identifiant' => $etablissement->identifiant)) ?>" class="btn_vert btn_majeur">Ajouter un commercial</a>
                    </div>

                <?php include_partial(
                    'annuaire/tableauAnnuaire',
                    ['personnalite' => 'Commerciaux', 'etablissement' => $etablissement, 'type' => 'commerciaux', 'annuaire' => $annuaire]
                ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <a class="btn_orange btn_majeur" href="<?php echo url_for('vrac_societe', array('identifiant' => $etablissement->identifiant)) ?>">Retourner à l'espace contrats</a>
    <?php include_partial('vrac/popup_notices'); ?> 
</section>

<?php
include_partial('vrac/colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
?>


<?php
use_helper('Float');
?>
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $societe->raison_sociale ?></strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?>
    </section>
    <br />

    <?php
    include_partial('historiqueFactures', array('identifiant' => $societe->identifiant, 'factures' => $factures, 'campagneForm' => $campagneForm));
    ?>
    <hr />
    <h2>Génération de facture</h2>
    <br />
    <?php include_partial('facture/mouvements', array('mouvements' => $mouvements, 'societe' => $societe, 'form' => $form)) ?>
</section>
<!-- fin #principal -->

<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('facture'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
        <?php if ($sf_user->isUsurpationCompte()): ?>
            <div class="ligne_btn txt_centre">
                <a class="deconnexion btn_majeur btn_orange" href="<?php echo url_for('facture_dedebrayage') ?>">Revenir sur VINSI</a>
            </div>
        <?php endif; ?>
        <?php if (!$isTeledeclarationMode && $societe->getMasterCompte()->hasDroit(Roles::TELEDECLARATION_FACTURE)): ?>
            <div class="ligne_btn txt_centre" style="margin:0px">
                <div class="btnConnexion">
                    <a href="<?php echo url_for('facture_debrayage', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur lien_connexion"><span>Connexion à la télédecl.</span></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
end_slot();
?>

<script type="text/javascript">

    $(document).ready( function()
    {
        $('#generation_facture').bind('click', function()
        {
            $('form#generation_form').submit();
	    return false;
        });
    });

</script>

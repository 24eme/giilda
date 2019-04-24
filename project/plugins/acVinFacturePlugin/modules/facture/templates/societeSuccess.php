<?php
use_helper('Float');
?>
<!-- #principal -->
<section id="principal">

    <?php
    include_partial('historiqueFactures', array('societe' => $societe, 'factures' => $factures, 'isTeledeclarationMode' => true));
    ?>
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
                <a class="deconnexion btn_majeur btn_orange" href="<?php echo url_for('vrac_dedebrayage') ?>">Revenir sur VINSI</a>
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

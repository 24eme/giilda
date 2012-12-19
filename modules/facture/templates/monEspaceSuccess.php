<?php
use_helper('Float');
?>    
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <?php include_component('facture', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
    </section>
    <br />

    <?php
    include_partial('historiqueFactures', array('etablissement' => $etablissement, 'factures' => $factures));
    ?>
    <hr />
    <h2>Génération de facture</h2>
    <br />
    <?php include_partial('facture/mouvements', array('mouvements' => $mouvements, 'etablissement' => $etablissement, 'form' => $form)) ?>
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
        });
    });
    
</script>


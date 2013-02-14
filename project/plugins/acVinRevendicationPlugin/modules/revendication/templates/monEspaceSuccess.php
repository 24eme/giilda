
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><strong>Page d'accueil</strong></p>
    <!-- #contenu_etape -->
    <section id="contenu_etape">

        <?php include_component('revendication', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>

        <form method="post">
            <?php echo $formCampagne->renderGlobalErrors() ?>
            <?php echo $formCampagne->renderHiddenFields() ?>
            <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
        </form>

        <?php include_component('revendication', 'editionList', array('revendications' => $revendications, 'campagne' => $campagne, 'odg' => $odg, 'retour' => 'etablissement')); ?>
    </section>
</section>

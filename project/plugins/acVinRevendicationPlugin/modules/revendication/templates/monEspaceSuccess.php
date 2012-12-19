
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><strong>Page d'accueil</strong></p>
    <!-- #contenu_etape -->
    <section id="contenu_etape">

        <?php include_component('revendication', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>

        <?php include_partial('revendication/editionList', array('revendications' => $revendications, 'retour' => 'etablissement')); ?>
    </section>
</section>

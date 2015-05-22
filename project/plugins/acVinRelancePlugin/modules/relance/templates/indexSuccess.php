 <!-- #principal -->
    <section id="principal" class="relance">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('relance', 'chooseEtablissement'); ?>
            <?php include_partial('historiqueGeneration', array('generations' => $generations)); ?>
            <?php include_component('relance','generationMasse'); ?>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
    
   
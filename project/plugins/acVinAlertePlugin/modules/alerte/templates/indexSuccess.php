 <!-- #principal -->
    <section id="principal" class="alerte">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_partial('consultation_alertes', array('form' => $form)); ?>
			
            <?php include_partial('liste_alertes', array('alertesHistorique' => $alertesHistorique,'modificationStatutForm' => $modificationStatutForm)); ?>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
    
   
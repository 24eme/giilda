   
    <!-- #principal -->
    <section id="principal" class="generation_facturation">
        <p id="fil_ariane"><strong><?php echo link_to("Page d'accueil", strtolower($type)); ?>  
                                    <?php 
                                    if(isset($identifiant))
                                        {
                                        echo '>'.link_to($nom, strtolower($type).'_etablissement', array('identifiant' => $identifiant));
                                        }
                                    ?>
                            </strong> > Visualisation d'un générations d'impression</p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Visualisation d'une génération d'impression</h2>
            <?php 
            $params = array('generation' => $generation, 'type' => $type);
            $params = array_merge(array('identifiant' => $identifiant),$params);
            include_partial('generations', $params); 
            ?>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
 
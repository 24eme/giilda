<?php

$periode = '2012';

?>

<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('sv12', 'chooseEtablissement'); ?>
            
            <?php include_partial('negociant_information',array('etablissement' => $etablissement)); ?>
            
            <div>
                <span>Saisir une nouvelle déclaration</span>
                <a href="<?php echo url_for('sv12_nouvelle', array('identifiant' => $etablissement->identifiant, 'periode' => $periode)) ?>">Démarrer</a>
            </div>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
    
    <!-- #colonne -->
    <aside id="colonne">
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>
            
            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                    <li class="contact"><a href="#">Contacter le support</a></li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- fin #colonne -->
</div>
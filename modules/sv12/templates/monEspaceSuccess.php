<?php

$periode = '2012';

?>


<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('sv12', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
            <br />
            <?php include_partial('negociant_information',array('etablissement' => $etablissement)); ?>
            <br />
            <a class="btn_majeur btn_nouveau" href="<?php echo url_for('sv12_nouvelle', array('identifiant' => $etablissement->identifiant, 'periode' => $periode)) ?>">Créer une SV12</a>
            <br />
            <br />

            <?php include_partial('sv12/list', array('list' => $list)) ?>
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
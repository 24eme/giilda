<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts > </strong> <?php echo $societe->raison_sociale; ?></p>

        <!-- #contenu_etape -->
        <section id="contacts">
            <div id="creation_societe">
            <h2><?php echo $societe->raison_sociale; ?></h2>
            <div class="btn_etape">
                <a href="<?php echo url_for('societe_addContact', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_vert">Nouveau Contact
                </a>
                &nbsp;
                <?php if($societe->canHaveChais()) : ?>  
                <a href="<?php echo url_for('societe_addEtablissement', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_jaune">Nouvel Etablissement
                </a>
                <?php endif;?>
            </div>
            <?php
            include_partial('visualisationPanel', array('societe' => $societe));?>
                <?php if(count($etablissements)) : ?>
                <h2>Coordonnées de la société </h2>
                <?php endif; ?>
                <?php
            foreach ($etablissements as $etablissementId => $etb) :
                include_partial('etablissement/visualisation', array('etablissement' => $etb->etablissement, 'ordre' => $etb->ordre));
            endforeach;
            ?>

        </section>
    </section>
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
         <?php
      //      include_component('societe', 'getInterlocuteurs', array('identifiant' => $societe->identifiant));
        ?>
    </aside>
</div>
<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Edition des volumes revendiqués :</h2>
            <div class="generation_facture_options">
                <a class="btn_majeur btn_valider" href="<?php echo url_for('revendication_edition', array('odg' => $revendication->odg, 'campagne' => $revendication->campagne)); ?>">Editer les volumes revendiqués</a>
            </div>
            
            <h2>Erreurs de l'import :</h2>
                <ul>
                    <li>
                        <span>
                            Nombre d'erreurs total : <label><?php echo count($revendication->erreurs); ?></label>
                        </span>
                    </li>
                    <?php foreach ($erreursByType->erreurs as $type => $erreursType) : ?>
                    <li>
                    <?php foreach ($erreursType as $unmatched_data => $erreur) : ?>
                          <div class="generation_facture_options">
                              <label>
                                  <?php if($type == RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS): ?>
                                       <a href="<?php echo url_for('revendication_add_alias_to_configuration',array('odg' => $revendication->odg, 'campagne' => $revendication->campagne, 'alias' => $unmatched_data)); ?>"><?php echo $erreur->libelle_erreur; ?></a>
                                  <?php else : 
                                      echo $erreur->libelle_erreur;
                                      endif;
                                   ?>                                      
                              </label>
                        </div>
                        <br/>
                            
                        <div class="bloc_col">
                             Lignes impactées par l'erreur précédente : 
                        <?php foreach ($erreur->lignes as $numLigne) :
                            echo '<a href="#'.$numLigne.'">'.$numLigne.'</a> ';
                        endforeach; ?>
                            </div>
                       <br/>
                    <?php
                    endforeach;
                    ?>
                    </li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            <h2>Tableau d'erreurs :</h2>
            <fieldset>
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>N° de ligne</th>
                            <th>Libellé de l'erreur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($revendication->erreurs as $key => $value) :
                            ?>
                            <tr id="<?php echo $value->num_ligne; ?>" >
                                <td rowspan="2"><?php echo $value->num_ligne; ?></td>
                                <td><?php echo $value->libelle_erreur; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo str_replace('#', '; ', $value->ligne); ?></td>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </fieldset>

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

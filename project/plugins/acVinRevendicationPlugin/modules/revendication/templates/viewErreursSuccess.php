<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Erreurs de l'import :</h2>
            <div class="generation_facture_options">
                <ul>
                    <li>
                        <span>
                            Nombre d'erreurs total : <label><a href="#2734"><?php echo count($revendication->erreurs); ?></a></label>
                        </span>
                    </li>
                    <li>
                        <span>
                            Nombre total de CVI non reconnus : <label><?php echo $erreursByType->{RevendicationErreurs::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS}; ?></label>
                        </span>
                        <span>
                            CVI non reconnus :                             
                                <?php
                                foreach ($erreursByType->erreurs[RevendicationErreurs::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS] as $cviArray) :
                                    foreach ($cviArray as $numLigne):
                                    ?>
                                        <label><a href="#<?php echo $numLigne;?>"><?php echo $numLigne;?>&nbsp;</a></label>
                                    <?php
                                    endforeach;
                                endforeach;
                                ?>                            
                        </span>
                    </li>
                    <li>
                        <span>
                            Nombre total de Produits non reconnus : <label><?php echo $erreursByType->{RevendicationErreurs::ERREUR_TYPE_PRODUIT_NOT_EXISTS}; ?></label>
                        </span>
                        <span>
                            Produits non reconnus :                            
                                    <ul>
                                <?php
                                foreach ($erreursByType->erreurs[RevendicationErreurs::ERREUR_TYPE_PRODUIT_NOT_EXISTS] as $prodName => $prodArray) :
                                    ?>
                                    <li> 
                                        
                                    <?php
                                    echo $prodName;
                                    foreach ($prodArray as $numLigne):
                                    ?>
                                        <label><a href="#<?php echo $numLigne;?>"><?php echo $numLigne;?>&nbsp;</a></label>
                                    <?php
                                    endforeach;
                                    ?>
                                    </li>
                                    <?php
                                endforeach;
                                ?>                            
                                    </ul>
                        </span>
                    </li>
                </ul>
            </div>
            <h2>Tableau d'erreurs :</h2>
            <fieldset>
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>Numéro de ligne</th>
                            <th>Libellé de l'erreur</th>
                            <th>Raw Ligne</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($revendication->erreurs as $key => $value) :
                            ?>
                            <tr id="<?php echo $value->num_ligne; ?>">
                                <td><?php echo $value->num_ligne; ?></td>
                                <td><?php echo $value->libelle_erreur; ?></td>
                                <td><?php echo $value->ligne; ?></td>
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

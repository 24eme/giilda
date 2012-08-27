<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <strong><?php echo $sv12->negociant->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Déclaration Récapitulative Mensuelle</h2>
            <div id="recap_infos_header">
                <div>
                    <label>Négociant : </label>
                    <?php echo $sv12->negociant->nom; ?>
                </div>
                <div>
                    <label>CVI : </label>
                    <?php echo $sv12->negociant->cvi; ?>
                </div>
                <div>
                    <label>Commune : </label>
                    <?php echo $sv12->negociant->commune; ?>
                </div>
            </div>        
            <form name="sv12_update" method="POST" action="<?php echo url_for('sv12_update', $sv12); ?>" >
                <?php 
                echo $form->renderHiddenFields();
                echo $form->renderGlobalErrors();
                ?>
            <fieldset id="edition_sv12">
                <legend>Saisie des volume</legend>
                    <table class="table_recap">
                    <thead>
                    <tr>
                        <th style="width: 200px;">Viticulteur </th>
                        <th>Appelation</th>
                        <th>Contrat</th>
                        <th>Volume</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sv12->contrats as $contrat) :
                        ?>   
                        
                        <tr>
                            <td>
                                <?php echo $contrat->vendeur_nom.' ('.$contrat->vendeur_identifiant.')'; ?>
                            </td>
                            <td>
                                <?php echo $contrat->produit_libelle; ?>
                            </td>

                            <td>
                                <?php echo $contrat->contrat_numero.' ('.$contrat->volume_prop.' hl)'; ?>
                            </td>

                            <td>            
                                <?php
                                    echo $form[$contrat->contrat_numero]->renderError();
                                    echo $form[$contrat->contrat_numero]->render();
                                ?>
                            </td>
                        </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                    </table> 
            </fieldset>
                <input type="submit" value="Valider" />
            </form>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    
    <?php include_partial('colonne', array('negociant' => $sv12->negociant)); ?>
    <!-- fin #principal -->
</div>
    
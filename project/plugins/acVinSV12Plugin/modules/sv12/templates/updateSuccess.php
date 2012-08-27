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
                        <?php foreach ($contrats as $contrat) :
                            $elt = $contrat->getRawValue()->value;
                            $num_contrat = preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]);
                        ?>   
                        
                        <tr>
                            <td>
                                <?php echo $elt[VracClient::VRAC_VIEW_VENDEUR_NOM].' ('.$elt[VracClient::VRAC_VIEW_VENDEUR_ID].')'; ?>
                            </td>
                            <td>
                                <?php echo ConfigurationClient::getCurrent()->get($elt[VracClient::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat(); ?>
                            </td>

                            <td>
                                <?php echo $num_contrat.' ('.$elt[VracClient::VRAC_VIEW_VOLPROP].' hl)'; ?>
                            </td>

                            <td>            
                                <?php
                                    echo $form[$num_contrat]->renderError();
                                    echo $form[$num_contrat]->render();
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
        
        <div class="bloc_col" id="infos_contact">
            <h2>Infos contact</h2>
            
            <div class="contenu">
                <ul>
                    <li id="infos_contact_negociant">
                        <a href="#">Coordonnées négociant</a>
                        <ul>
                            <li class="nom">Nom du négociant</li>
                            <li class="tel">00 00 00 00 00</li>
                            <li class="fax">00 00 00 00 00</li>
                            <li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    
    <!-- fin #colonne -->
    </aside>
    <!-- fin #principal -->
</div>
    
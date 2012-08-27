<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('sv12') ?>">Page d'accueil</a> &gt; <strong><?php echo $sv12->negociant->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Déclaration SV12</h2>
            
            <?php include_partial('negociant_infos',array('sv12' => $sv12)); ?>
            <form name="sv12_recapitulatif" method="POST" action="<?php echo url_for('sv12_recapitulatif', $sv12); ?>" >
            
            <h2>Récapitulatif</h2>
            
            <fieldset id="recapitulatif_sv12">
                    <table class="table_recap">
                    <thead>
                    <tr>
                        <th>Appelation</th>
                        <th>Volume de raisins</th>
                        <th>Volume de moûts</th>
                        <th>Total</th>                        
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sv12ByProduitsTypes->rows as $sv12Prod) :  ?>
                        <tr>
                            <td>
                                <?php echo $sv12Prod->appelation; ?>
                            </td>
                            <td>
                                <?php echo $sv12Prod->volume_raisins.' hl'; ?>
                            </td>

                            <td>
                                <?php echo $sv12Prod->volume_mouts.' hl'; ?>
                            </td>

                            <td>     
                               <?php echo $sv12Prod->volume_total.' hl'; ?>
                            </td>
                        </tr>
                        <?php
                        endforeach;
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total : </td>
                            <td><?php echo $sv12ByProduitsTypes->volume_raisins.' hl'; ?></td>
                            <td><?php echo $sv12ByProduitsTypes->volume_mouts.' hl'; ?></td>
                            <td><?php echo $sv12ByProduitsTypes->volume_total.' hl'; ?></td>
                        </tr>
                    </tbody>
                    </table> 
            </fieldset>
                
                
            <fieldset id="mouvement_sv12">
                <legend>Détail des mouvements</legend>
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
                                <?php echo $contrat->contrat_numero; ?>
                            </td>

                            <td>     
                               <?php echo $contrat->volume; ?>
                            </td>
                        </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                    </table> 
            </fieldset>
                <a href="<?php echo url_for('sv12_update',$sv12); ?>" class="btn_majeur btnRetour">Précedent</a>                
                <a href="<?php echo url_for('sv12_brouillon',$sv12); ?>" class="btn_majeur btnModification">Enregistrer le brouillon</a>
                <button type="submit" class="btn_majeur btn_vert btnValidation">Valider</button>
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
    